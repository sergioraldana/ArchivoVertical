<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Mattiverse\Userstamps\Traits\Userstamps;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Tema extends Model
{
    use LogsActivity, Userstamps;

    protected $fillable = [
        'nombre',
    ];

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'item_tema')
            ->withTimestamps();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nombre'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
