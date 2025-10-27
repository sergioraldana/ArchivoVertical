<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Mattiverse\Userstamps\Traits\Userstamps;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Seccion extends Model
{
    use LogsActivity, Userstamps;

    protected $table = 'secciones';

    protected $fillable = [
        'fuente_id',
        'nombre',
    ];

    public function fuente(): BelongsTo
    {
        return $this->belongsTo(Fuente::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['fuente_id', 'nombre'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
