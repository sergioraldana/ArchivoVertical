<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Mattiverse\Userstamps\Traits\Userstamps;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Autor extends Model
{
    use LogsActivity, Userstamps;

    protected $table = 'autores';

    protected $fillable = [
        'nombre',
    ];

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'item_autor')
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
