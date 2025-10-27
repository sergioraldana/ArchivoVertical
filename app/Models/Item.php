<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Mattiverse\Userstamps\Traits\Userstamps;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Item extends Model
{
    use LogsActivity, Userstamps;

    protected $fillable = [
        'carpeta_id',
        'numero_id',
        'seccion_id',
        'titulo',
        'subtitulo',
        'descripcion',
        'fecha',
        'pagina',
        'estado',
        'tiene_ocr',
        'texto_ocr',
        'archivos',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'tiene_ocr' => 'boolean',
            'archivos' => 'array',
        ];
    }

    public function carpeta(): BelongsTo
    {
        return $this->belongsTo(Carpeta::class);
    }

    public function numero(): BelongsTo
    {
        return $this->belongsTo(Numero::class);
    }

    public function seccion(): BelongsTo
    {
        return $this->belongsTo(Seccion::class);
    }

    public function autores(): BelongsToMany
    {
        return $this->belongsToMany(Autor::class, 'item_autor')
            ->withTimestamps();
    }

    public function temas(): BelongsToMany
    {
        return $this->belongsToMany(Tema::class, 'item_tema')
            ->withTimestamps();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['titulo', 'subtitulo', 'descripcion', 'fecha', 'pagina', 'estado', 'carpeta_id', 'numero_id', 'seccion_id'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
