<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Mattiverse\Userstamps\Traits\Userstamps;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Anio extends Model
{
    use LogsActivity, Userstamps;

    protected $table = 'anios';

    protected $fillable = [
        'fuente_id',
        'anio',
    ];

    protected function casts(): array
    {
        return [
            'anio' => 'string',
        ];
    }

    public function fuente(): BelongsTo
    {
        return $this->belongsTo(Fuente::class);
    }

    public function numeros(): HasMany
    {
        return $this->hasMany(Numero::class);
    }

    public function items(): HasManyThrough
    {
        return $this->hasManyThrough(Item::class, Numero::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['fuente_id', 'anio'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
