<?php

namespace App\Console\Commands;

use App\Models\Anio;
use App\Models\Autor;
use App\Models\Carpeta;
use App\Models\Fuente;
use App\Models\Item;
use App\Models\Numero;
use App\Models\Seccion;
use App\Models\Tema;
use App\Support\SystemUser;
use App\TipoFuente;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ImportarArchivoCSV extends Command
{
    protected $signature = 'archivo:importar-csv {archivo? : Ruta al archivo CSV}';

    protected $description = 'Importa datos del archivo vertical desde un archivo CSV';

    private array $stats = [
        'carpetas' => 0,
        'fuentes' => 0,
        'anios' => 0,
        'numeros' => 0,
        'secciones' => 0,
        'autores' => 0,
        'temas' => 0,
        'items' => 0,
        'errores' => 0,
    ];

    private array $carpetasCache = [];

    public function handle(): int
    {
        $archivo = $this->argument('archivo') ?? base_path('Archivo Vertical Histórico Hoja 1.csv');

        if (! file_exists($archivo)) {
            $this->error("❌ El archivo no existe: {$archivo}");

            return self::FAILURE;
        }

        $this->info("📂 Importando desde: {$archivo}");
        $this->newLine();

        if (! $this->confirm('¿Deseas continuar con la importación?', true)) {
            $this->warn('Importación cancelada.');

            return self::SUCCESS;
        }

        $this->newLine();
        $this->info('🚀 Iniciando importación...');
        $this->newLine();

        $inicio = microtime(true);

        try {
            // Autenticar como usuario sistema
            if (! Auth::check()) {
                $systemUser = SystemUser::get();
                if (! $systemUser) {
                    $this->error('❌ Usuario sistema no encontrado.');
                    $this->warn('   Ejecute primero: php artisan db:seed --class=SystemUserSeeder');

                    return self::FAILURE;
                }
                Auth::login($systemUser);
            }

            DB::beginTransaction();

            $this->procesarCSV($archivo);

            DB::commit();

            $duracion = round(microtime(true) - $inicio, 2);

            $this->newLine();
            $this->info('✅ Importación completada exitosamente');
            $this->newLine();

            $this->mostrarEstadisticas($duracion);

            return self::SUCCESS;
        } catch (\Exception $e) {
            DB::rollBack();

            $this->newLine();
            $this->error('❌ Error durante la importación: '.$e->getMessage());
            $this->error('   Línea: '.$e->getLine());
            $this->error('   Archivo: '.$e->getFile());

            return self::FAILURE;
        }
    }

    private function procesarCSV(string $archivo): void
    {
        $handle = fopen($archivo, 'r');

        if ($handle === false) {
            throw new \Exception('No se pudo abrir el archivo CSV');
        }

        // Saltar encabezado
        fgetcsv($handle);

        // Primera pasada: analizar carpetas por letra y tema
        $this->analizarCarpetas($archivo);

        // Segunda pasada: importar datos
        $bar = $this->output->createProgressBar();
        $bar->setFormat(' %current% registros procesados | %elapsed:6s% | %memory:6s%');

        $lineaNumero = 2; // Empezamos en 2 porque la línea 1 es el encabezado

        while (($fila = fgetcsv($handle)) !== false) {
            try {
                // Saltar filas vacías (a partir de la línea 167 en el CSV)
                if (empty(array_filter($fila))) {
                    continue;
                }

                $this->procesarFila($fila);
                $bar->advance();
            } catch (\Exception $e) {
                $this->stats['errores']++;
                $this->newLine(2);
                $this->warn("⚠️  Error en línea {$lineaNumero}: ".$e->getMessage());
            }

            $lineaNumero++;
        }

        $bar->finish();
        fclose($handle);
    }

    private function analizarCarpetas(string $archivo): void
    {
        $handle = fopen($archivo, 'r');
        fgetcsv($handle); // Saltar encabezado

        $temasPorLetra = [];

        while (($fila = fgetcsv($handle)) !== false) {
            if (empty(array_filter($fila))) {
                continue;
            }

            [$no, $letra, $correlativo, $origen, $anio, $epoca, $fecha, $numero, $pagina, $seccion, $titulo, $subtitulo, $autores, $tema1] = array_pad($fila, 14, null);

            if (empty($letra) || empty($tema1) || $tema1 === '-') {
                continue;
            }

            $letra = strtoupper(trim($letra));
            $tema1 = trim($tema1);

            if (! isset($temasPorLetra[$letra])) {
                $temasPorLetra[$letra] = [];
            }

            $temasPorLetra[$letra][] = $tema1;
        }

        // Determinar el nombre de cada carpeta basado en el tema más común
        foreach ($temasPorLetra as $letra => $temas) {
            $this->carpetasCache[$letra] = $this->determinarNombreCarpeta($letra, $temas);
        }

        fclose($handle);
    }

    private function determinarNombreCarpeta(string $letra, array $temas): string
    {
        // Contar frecuencia de cada tema
        $frecuencias = array_count_values($temas);
        arsort($frecuencias);

        // Obtener el tema más común
        $temaMasComun = array_key_first($frecuencias);

        // Limpiar el nombre del tema
        $nombre = $temaMasComun;

        // Eliminar sufijos comunes
        $nombre = preg_replace('/ - Guatemala$/i', '', $nombre);
        $nombre = trim($nombre);

        // Capitalizar cada palabra correctamente
        $nombre = mb_convert_case($nombre, MB_CASE_TITLE, 'UTF-8');

        return $nombre ?: "Carpeta {$letra}";
    }

    private function procesarFila(array $fila): void
    {
        // Extraer datos del CSV
        [$no, $letra, $correlativo, $origen, $anio, $epoca, $fecha, $numero, $pagina, $seccion, $titulo, $subtitulo, $autores, $tema1, $tema2, $tema3, $tema4, $tema5] = array_pad($fila, 18, null);

        // Validar campos obligatorios mínimos
        if (empty($letra) || empty($titulo)) {
            throw new \Exception('Faltan campos obligatorios (letra o título)');
        }

        // 1. Crear/Obtener Carpeta
        $carpeta = $this->obtenerOCrearCarpeta($letra, $correlativo);

        // 2. Crear/Obtener Fuente
        $fuente = $this->obtenerOCrearFuente($origen);

        // 3. Crear/Obtener Año (si existe fuente y año)
        $anioModel = null;
        if ($fuente && ! empty($anio)) {
            $anioModel = $this->obtenerOCrearAnio($anio, $fuente);
        }

        // 4. Crear/Obtener Número (si existe año y número)
        $numeroModel = null;
        if ($anioModel && ! empty($numero)) {
            $numeroModel = $this->obtenerOCrearNumero($numero, $anioModel, $fecha);
        }

        // 5. Crear/Obtener Sección (si existe fuente y sección)
        $seccionModel = null;
        if ($fuente && ! empty($seccion) && $seccion !== '-') {
            $seccionModel = $this->obtenerOCrearSeccion($seccion, $fuente);
        }

        // 6. Crear Item
        $item = $this->crearItem([
            'titulo' => $titulo,
            'subtitulo' => $subtitulo,
            'fecha' => $this->parsearFecha($fecha),
            'pagina' => $pagina,
            'carpeta_id' => $carpeta->id,
            'numero_id' => $numeroModel?->id,
            'seccion_id' => $seccionModel?->id,
        ]);

        // 7. Asociar Autores
        if (! empty($autores) && $autores !== '-') {
            $this->asociarAutores($item, $autores);
        }

        // 8. Asociar Temas
        $temas = array_filter([$tema1, $tema2, $tema3, $tema4, $tema5], fn ($t) => ! empty($t) && $t !== '-');
        if (! empty($temas)) {
            $this->asociarTemas($item, $temas);
        }
    }

    private function obtenerOCrearCarpeta(string $letra, ?string $correlativo): Carpeta
    {
        $letra = strtoupper($letra);
        $nombreCarpeta = $this->carpetasCache[$letra] ?? "Carpeta {$letra}";

        $carpeta = Carpeta::firstOrCreate(
            ['codigo' => $letra],
            ['nombre' => $nombreCarpeta]
        );

        if ($carpeta->wasRecentlyCreated) {
            $this->stats['carpetas']++;
        }

        return $carpeta;
    }

    private function obtenerOCrearFuente(?string $origen): ?Fuente
    {
        if (empty($origen) || $origen === '-') {
            return null;
        }

        // Normalizar nombre
        $nombreNormalizado = trim($origen);

        // Determinar tipo basado en el nombre
        $tipo = $this->determinarTipoFuente($nombreNormalizado);

        $fuente = Fuente::firstOrCreate(
            ['nombre' => $nombreNormalizado],
            ['tipo' => $tipo->value]
        );

        if ($fuente->wasRecentlyCreated) {
            $this->stats['fuentes']++;
        }

        return $fuente;
    }

    private function determinarTipoFuente(string $nombre): TipoFuente
    {
        $nombre = strtolower($nombre);

        if (str_contains($nombre, 'revista')) {
            return TipoFuente::Revista;
        }

        if (str_contains($nombre, 'diario') || str_contains($nombre, 'prensa') || str_contains($nombre, 'siglo') || str_contains($nombre, 'grafico') || str_contains($nombre, 'hora')) {
            return TipoFuente::Periodico;
        }

        return TipoFuente::Periodico; // Por defecto
    }

    private function obtenerOCrearAnio(string $anio, Fuente $fuente): Anio
    {
        $anioModel = Anio::firstOrCreate(
            [
                'anio' => trim($anio),
                'fuente_id' => $fuente->id,
            ]
        );

        if ($anioModel->wasRecentlyCreated) {
            $this->stats['anios']++;
        }

        return $anioModel;
    }

    private function obtenerOCrearNumero(string $numero, Anio $anio, ?string $fecha): Numero
    {
        $numeroModel = Numero::firstOrCreate(
            [
                'numero' => trim($numero),
                'anio_id' => $anio->id,
            ],
            [
                'fecha_publicacion' => $this->parsearFecha($fecha),
            ]
        );

        if ($numeroModel->wasRecentlyCreated) {
            $this->stats['numeros']++;
        }

        return $numeroModel;
    }

    private function obtenerOCrearSeccion(string $seccion, Fuente $fuente): Seccion
    {
        $seccionModel = Seccion::firstOrCreate(
            [
                'nombre' => trim($seccion),
                'fuente_id' => $fuente->id,
            ]
        );

        if ($seccionModel->wasRecentlyCreated) {
            $this->stats['secciones']++;
        }

        return $seccionModel;
    }

    private function crearItem(array $datos): Item
    {
        $item = Item::create($datos);
        $this->stats['items']++;

        return $item;
    }

    private function asociarAutores(Item $item, string $autoresStr): void
    {
        $nombresAutores = array_map('trim', explode(',', $autoresStr));

        foreach ($nombresAutores as $nombre) {
            if (empty($nombre)) {
                continue;
            }

            $autor = Autor::firstOrCreate(['nombre' => $nombre]);

            if ($autor->wasRecentlyCreated) {
                $this->stats['autores']++;
            }

            $item->autores()->attach($autor->id);
        }
    }

    private function asociarTemas(Item $item, array $temas): void
    {
        foreach ($temas as $nombreTema) {
            $nombreTema = trim($nombreTema);

            if (empty($nombreTema)) {
                continue;
            }

            $tema = Tema::firstOrCreate(['nombre' => $nombreTema]);

            if ($tema->wasRecentlyCreated) {
                $this->stats['temas']++;
            }

            $item->temas()->attach($tema->id);
        }
    }

    private function parsearFecha(?string $fecha): ?string
    {
        if (empty($fecha) || $fecha === '-') {
            return null;
        }

        try {
            // Intentar parsear formato d/m/Y
            $carbon = Carbon::createFromFormat('d/m/Y', trim($fecha));

            return $carbon->format('Y-m-d');
        } catch (\Exception $e) {
            // Si falla, retornar null
            return null;
        }
    }

    private function mostrarEstadisticas(float $duracion): void
    {
        $this->table(
            ['Entidad', 'Cantidad Creada'],
            [
                ['Carpetas', $this->stats['carpetas']],
                ['Fuentes', $this->stats['fuentes']],
                ['Años', $this->stats['anios']],
                ['Números', $this->stats['numeros']],
                ['Secciones', $this->stats['secciones']],
                ['Autores', $this->stats['autores']],
                ['Temas', $this->stats['temas']],
                ['Items', $this->stats['items']],
                ['Errores', $this->stats['errores']],
            ]
        );

        $this->newLine();
        $this->info("⏱️  Tiempo total: {$duracion} segundos");
    }
}
