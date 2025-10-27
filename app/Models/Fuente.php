<?php

namespace App\Models;

use App\TipoFuente;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Mattiverse\Userstamps\Traits\Userstamps;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Fuente extends Model
{
    use LogsActivity, Userstamps;

    protected $fillable = [
        'nombre',
        'tipo',
    ];

    protected function casts(): array
    {
        return [
            'tipo' => TipoFuente::class,
        ];
    }

    public function secciones(): HasMany
    {
        return $this->hasMany(Seccion::class);
    }

    public function anios(): HasMany
    {
        return $this->hasMany(Anio::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nombre', 'tipo'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
