# 📋 Activity Log - Auditoría del Sistema

## Descripción

El sistema utiliza **Spatie Laravel Activity Log** (v4.10) para registrar automáticamente todos los cambios realizados en los modelos principales. Esto proporciona una auditoría completa de quién hizo qué cambio y cuándo.

## 🎯 Modelos con Activity Log

Todos los modelos del sistema tienen registro de actividad configurado:

### 1. **Item** (Artículos)
**Campos auditados:**
- `titulo`, `subtitulo`, `descripcion`
- `fecha`, `pagina`, `estado`
- `carpeta_id`, `numero_id`, `seccion_id`

### 2. **Carpeta**
**Campos auditados:**
- `codigo`, `nombre`

### 3. **Fuente** (Periódicos/Revistas)
**Campos auditados:**
- `nombre`, `tipo`

### 4. **Seccion**
**Campos auditados:**
- `fuente_id`, `nombre`

### 5. **Autor**
**Campos auditados:**
- `nombre`

### 6. **Tema**
**Campos auditados:**
- `nombre`

### 7. **Anio** (Años de publicación)
**Campos auditados:**
- `fuente_id`, `anio`

### 8. **Numero** (Números de edición)
**Campos auditados:**
- `anio_id`, `numero`, `fecha_publicacion`

### 9. **User** (Usuarios)
**Campos auditados:**
- `name`, `email`

> **Nota**: La contraseña (`password`) NO se registra por seguridad.

---

## 📊 Estructura de la tabla `activity_log`

| Columna | Tipo | Descripción |
|---------|------|-------------|
| `id` | bigint | ID único de la actividad |
| `log_name` | string | Nombre del log (por defecto: "default") |
| `description` | text | Descripción del evento (created, updated, deleted) |
| `subject_type` | string | Clase del modelo (App\Models\Item) |
| `subject_id` | bigint | ID del registro modificado |
| `event` | string | Tipo de evento (created, updated, deleted) |
| `causer_type` | string | Clase del usuario (App\Models\User) |
| `causer_id` | bigint | ID del usuario que realizó el cambio |
| `properties` | json | Valores antes y después del cambio |
| `batch_uuid` | uuid | UUID para agrupar múltiples acciones |
| `created_at` | timestamp | Fecha y hora del cambio |
| `updated_at` | timestamp | Fecha y hora de actualización |

---

## 🔍 Consultar Activity Log

### Obtener actividad de un modelo específico

```php
use Spatie\Activitylog\Models\Activity;
use App\Models\Item;

// Todas las actividades de un item
$item = Item::find(1);
$activities = Activity::forSubject($item)->get();

// Última actividad
$lastActivity = Activity::forSubject($item)->latest()->first();
```

### Obtener actividad por usuario

```php
use App\Models\User;

$user = User::find(1);
$activities = Activity::causedBy($user)->get();
```

### Obtener todos los cambios de un tipo de modelo

```php
// Todos los items creados
$createdItems = Activity::forSubject(Item::class)
    ->where('event', 'created')
    ->get();

// Todos los items actualizados hoy
$updatedToday = Activity::forSubject(Item::class)
    ->where('event', 'updated')
    ->whereDate('created_at', today())
    ->get();
```

### Obtener actividad por evento

```php
// Solo eventos de creación
Activity::where('event', 'created')->get();

// Solo eventos de actualización
Activity::where('event', 'updated')->get();

// Solo eventos de eliminación
Activity::where('event', 'deleted')->get();
```

---

## 📝 Qué se registra

### Evento: `created`
Se registra cuando se crea un nuevo registro.

**Propiedades:**
```json
{
  "attributes": {
    "id": 1,
    "titulo": "Nuevo artículo",
    "fecha": "2025-10-26",
    "created_by": 1,
    "updated_by": 1
  }
}
```

### Evento: `updated`
Se registra cuando se actualiza un registro. Solo se guardan los campos modificados.

**Propiedades:**
```json
{
  "attributes": {
    "titulo": "Título actualizado",
    "updated_at": "2025-10-26 10:30:00"
  },
  "old": {
    "titulo": "Título anterior",
    "updated_at": "2025-10-26 10:00:00"
  }
}
```

### Evento: `deleted`
Se registra cuando se elimina un registro.

**Propiedades:**
```json
{
  "attributes": {
    "id": 1,
    "titulo": "Artículo eliminado",
    "fecha": "2025-10-26"
  }
}
```

---

## 🎨 Mostrar Activity Log en Filament

### Crear un recurso para Activity Log

```bash
php artisan make:filament-resource Activity --model="Spatie\Activitylog\Models\Activity" --simple
```

### Ejemplo de tabla de actividades

```php
use Filament\Tables\Columns\TextColumn;
use Spatie\Activitylog\Models\Activity;

public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('description')
                ->label('Evento')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'created' => 'success',
                    'updated' => 'warning',
                    'deleted' => 'danger',
                    default => 'gray',
                }),
            TextColumn::make('subject_type')
                ->label('Modelo')
                ->formatStateUsing(fn ($state) => class_basename($state)),
            TextColumn::make('causer.name')
                ->label('Usuario'),
            TextColumn::make('created_at')
                ->label('Fecha')
                ->dateTime()
                ->sortable(),
        ])
        ->defaultSort('created_at', 'desc');
}
```

---

## ⚙️ Configuración

La configuración se encuentra en `config/activitylog.php`.

### Opciones principales:

```php
return [
    // Activar/desactivar el registro
    'enabled' => env('ACTIVITY_LOGGER_ENABLED', true),

    // Nombre de la tabla
    'table_name' => 'activity_log',

    // Conexión de base de datos (null = default)
    'database_connection' => null,

    // Modelo de Activity personalizado
    'activity_model' => \Spatie\Activitylog\Models\Activity::class,

    // Nombre del log por defecto
    'default_log_name' => 'default',

    // Días para limpiar logs antiguos (null = nunca)
    'delete_records_older_than_days' => 365,
];
```

---

## 🧹 Limpieza de logs antiguos

### Comando para limpiar logs

```bash
# Limpiar logs mayores a 365 días (configurado en config)
php artisan activitylog:clean

# Limpiar logs mayores a 30 días
php artisan activitylog:clean --days=30
```

### Programar limpieza automática

En `app/Console/Kernel.php` o `routes/console.php`:

```php
use Illuminate\Support\Facades\Schedule;

Schedule::command('activitylog:clean')->daily();
```

---

## 🔐 Integración con Userstamps y SystemUser

El Activity Log está perfectamente integrado con:

1. **Userstamps**: Los campos `created_by` y `updated_by` se llenan automáticamente
2. **SystemUser**: Las operaciones automatizadas (seeders, comandos) registran al usuario sistema (ID: 1) como `causer`

### Ejemplo de actividad del sistema

```php
// Cuando se ejecuta un seeder con SystemUser::actAs()
Activity::latest()->first();

// Resultado:
// causer_id: 1 (Usuario Sistema)
// description: "created"
// subject: Item #396
```

---

## 📈 Casos de uso

### 1. Auditoría de cambios
Ver quién modificó un artículo y qué cambió:

```php
$item = Item::find(5);
$changes = Activity::forSubject($item)
    ->where('event', 'updated')
    ->get();

foreach ($changes as $change) {
    echo "Usuario: {$change->causer->name}\n";
    echo "Fecha: {$change->created_at}\n";
    echo "Cambios: " . json_encode($change->properties['old']) . "\n";
}
```

### 2. Historial de un usuario
Ver todas las acciones de un usuario:

```php
$user = User::find(2);
$userActivity = Activity::causedBy($user)
    ->latest()
    ->paginate(50);
```

### 3. Reportes de actividad
Generar reportes de actividad del sistema:

```php
// Items creados por mes
$itemsThisMonth = Activity::forSubject(Item::class)
    ->where('event', 'created')
    ->whereMonth('created_at', now()->month)
    ->count();

// Usuarios más activos
$topUsers = Activity::query()
    ->selectRaw('causer_id, causer_type, COUNT(*) as total')
    ->groupBy('causer_id', 'causer_type')
    ->orderByDesc('total')
    ->limit(10)
    ->get();
```

### 4. Restaurar valores anteriores
Deshacer un cambio usando los datos del log:

```php
$activity = Activity::find(123);
$oldValues = $activity->properties['old'];

// Restaurar valores
$item = Item::find($activity->subject_id);
$item->update($oldValues);
```

---

## 🛡️ Mejores prácticas

1. **No registrar datos sensibles**: El activity log NO registra contraseñas ni tokens
2. **Limpieza regular**: Configurar limpieza automática de logs antiguos
3. **Índices**: La tabla ya tiene índices en `subject` y `causer` para consultas rápidas
4. **Batch UUID**: Usar `batch_uuid` para agrupar múltiples operaciones relacionadas
5. **Log names**: Usar nombres de log específicos para categorizar actividades

---

## 🔗 Referencias

- **Documentación oficial**: https://spatie.be/docs/laravel-activitylog
- **Repositorio**: https://github.com/spatie/laravel-activitylog
- **Versión instalada**: v4.10.2

---

**Última actualización**: Octubre 2025  
**Versión del sistema**: 0.3.0

