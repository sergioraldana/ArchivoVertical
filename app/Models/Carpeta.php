<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Mattiverse\Userstamps\Traits\Userstamps;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Carpeta extends Model
{
    use LogsActivity, Userstamps;

    protected $fillable = [
        'codigo',
        'nombre',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['codigo', 'nombre'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
