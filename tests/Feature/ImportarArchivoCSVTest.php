<?php

use App\Models\Carpeta;
use App\Models\Fuente;
use App\Models\Item;

it('puede importar datos desde un archivo CSV', function () {
    // Crear un archivo CSV de prueba
    $contenidoCSV = <<<'CSV'
No.,Letra,Correlativo,Origen,Año,Epoca,Fecha,Número,Página,Sección,Título,Subtítulo,Autores,Tema 1,Tema 2,Tema 3,Tema 4,Tema 5
1,Z,1.37,Prensa Libre,LXXIV,,10/11/2024,24754,9,Plt,100 años del Parque Zoológico Nacional la Aurora,,-,Zoológico - Guatemala,Zoologico - La Aurora,100 años,,,
CSV;

    $archivoTemporal = tmpfile();
    $rutaArchivo = stream_get_meta_data($archivoTemporal)['uri'];
    fwrite($archivoTemporal, $contenidoCSV);

    // Ejecutar el comando
    $this->artisan('archivo:importar-csv', ['archivo' => $rutaArchivo])
        ->expectsConfirmation('¿Deseas continuar con la importación?', 'yes')
        ->assertSuccessful();

    // Verificar que se crearon los registros
    expect(Carpeta::where('codigo', 'Z')->exists())->toBeTrue();
    expect(Fuente::where('nombre', 'Prensa Libre')->exists())->toBeTrue();
    expect(Item::where('titulo', '100 años del Parque Zoológico Nacional la Aurora')->exists())->toBeTrue();

    fclose($archivoTemporal);
});

it('maneja errores cuando el archivo no existe', function () {
    $this->artisan('archivo:importar-csv', ['archivo' => 'archivo_inexistente.csv'])
        ->assertFailed();
});

it('puede cancelar la importación', function () {
    $contenidoCSV = "No.,Letra,Correlativo,Origen\n1,Z,1.1,Test";
    $archivoTemporal = tmpfile();
    $rutaArchivo = stream_get_meta_data($archivoTemporal)['uri'];
    fwrite($archivoTemporal, $contenidoCSV);

    $this->artisan('archivo:importar-csv', ['archivo' => $rutaArchivo])
        ->expectsConfirmation('¿Deseas continuar con la importación?', 'no')
        ->assertSuccessful();

    fclose($archivoTemporal);
});
