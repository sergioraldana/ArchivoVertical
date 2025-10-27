<?php

namespace App;

use Filament\Support\Contracts\HasLabel;

enum TipoFuente: string implements HasLabel
{
    case Periodico = 'periodico';
    case Revista = 'revista';
    case SitioWeb = 'sitio_web';
    case Libro = 'libro';
    case Documento = 'documento';
    case Otro = 'otro';

    public function getLabel(): string
    {
        return match ($this) {
            self::Periodico => 'Periódico',
            self::Revista => 'Revista',
            self::SitioWeb => 'Sitio Web',
            self::Libro => 'Libro',
            self::Documento => 'Documento',
            self::Otro => 'Otro',
        };
    }
}
