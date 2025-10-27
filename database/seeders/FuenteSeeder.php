<?php

namespace Database\Seeders;

use App\Models\Fuente;
use App\Support\SystemUser;
use App\TipoFuente;
use Illuminate\Database\Seeder;

class FuenteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SystemUser::actAs(function () {
            $this->crearFuentes();
        });
    }

    private function crearFuentes(): void
    {
        $fuentes = [
            ['nombre' => 'Prensa Libre', 'tipo' => TipoFuente::Periodico],
            ['nombre' => 'Diario de Centro América', 'tipo' => TipoFuente::Periodico],
            ['nombre' => 'Siglo 21', 'tipo' => TipoFuente::Periodico],
            ['nombre' => 'El Gráfico', 'tipo' => TipoFuente::Periodico],
            ['nombre' => 'La Hora', 'tipo' => TipoFuente::Periodico],
            ['nombre' => 'Nuestro Diario', 'tipo' => TipoFuente::Periodico],
            ['nombre' => 'Revista', 'tipo' => TipoFuente::Revista],
        ];

        foreach ($fuentes as $fuente) {
            Fuente::create($fuente);
        }
    }
}
