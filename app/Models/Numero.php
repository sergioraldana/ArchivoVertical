<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Mattiverse\Userstamps\Traits\Userstamps;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Numero extends Model
{
    use LogsActivity, Userstamps;

    protected $fillable = [
        'anio_id',
        'numero',
        'fecha_publicacion',
        'portada',
    ];

    protected function casts(): array
    {
        return [
            'fecha_publicacion' => 'date',
        ];
    }

    public function anio(): BelongsTo
    {
        return $this->belongsTo(Anio::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['anio_id', 'numero', 'fecha_publicacion', 'portada'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
