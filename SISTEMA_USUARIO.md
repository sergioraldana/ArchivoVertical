# 🤖 Sistema de Usuario Sistema

## Descripción

El sistema de usuario sistema (`SystemUser`) es una funcionalidad que permite ejecutar operaciones automatizadas (seeders, comandos, observers, jobs, etc.) con un usuario autenticado, resolviendo el problema de los campos `created_by` y `updated_by` del paquete `Userstamps`.

## ¿Por qué es necesario?

Cuando usamos **userstamps** (`created_by`, `updated_by`), Laravel necesita un usuario autenticado para llenar estos campos. Sin embargo, en operaciones automatizadas como:

- 🌱 **Seeders** - Al poblar la base de datos
- ⚙️ **Comandos Artisan** - Al ejecutar tareas programadas
- 👁️ **Observers** - Al ejecutar eventos del modelo
- 📋 **Jobs** - Al procesar trabajos en cola
- 🔄 **Migrations con datos** - Al crear registros iniciales

No hay un usuario autenticado, lo que puede causar errores o registros con valores `NULL` en `created_by` y `updated_by`.

## Solución: Usuario Sistema

Se creó un **usuario especial** con ID fijo `1` y email `system@archivovertical.local` que actúa como usuario para todas estas operaciones automatizadas.

## 📁 Archivos del sistema

### 1. Seeder del Usuario Sistema

```php
// database/seeders/SystemUserSeeder.php
```

**Características:**
- ID fijo: `1`
- Email: `system@archivovertical.local`
- Contraseña aleatoria y segura
- Se ejecuta **siempre primero** en `DatabaseSeeder`

### 2. Clase Helper

```php
// app/Support/SystemUser.php
```

**Métodos disponibles:**
- `SystemUser::id()` - Obtiene el ID del usuario sistema o del usuario autenticado
- `SystemUser::get()` - Obtiene la instancia del usuario sistema
- `SystemUser::actAs(callable)` - Ejecuta un callback autenticado como usuario sistema
- `SystemUser::isCurrent()` - Verifica si el usuario actual es el usuario sistema
- `SystemUser::exists()` - Verifica si existe el usuario sistema

## 🚀 Uso

### En Seeders

```php
use App\Support\SystemUser;

class MiSeeder extends Seeder
{
    public function run(): void
    {
        SystemUser::actAs(function () {
            // Todo lo que esté aquí se ejecuta como usuario sistema
            Carpeta::create(['codigo' => 'A', 'nombre' => 'Archivo A']);
            Fuente::create(['nombre' => 'Mi Fuente', 'tipo' => 'periodico']);
            // Los campos created_by y updated_by se llenarán con ID 1
        });
    }
}
```

### En Comandos Artisan

```php
use App\Support\SystemUser;
use Illuminate\Support\Facades\Auth;

class MiComando extends Command
{
    public function handle()
    {
        // Verificar que existe el usuario sistema
        if (!SystemUser::exists()) {
            $this->error('Usuario sistema no encontrado');
            $this->warn('Ejecute: php artisan db:seed --class=SystemUserSeeder');
            return self::FAILURE;
        }

        // Autenticar como usuario sistema
        $systemUser = SystemUser::get();
        Auth::login($systemUser);

        // Ahora todas las operaciones usan el usuario sistema
        Item::create([...]);
        
        return self::SUCCESS;
    }
}
```

### En Observers

```php
use App\Support\SystemUser;

class MiObserver
{
    public function creating(MiModelo $modelo)
    {
        // Si no hay usuario autenticado, usar el sistema
        if (!auth()->check()) {
            $modelo->created_by = SystemUser::ID;
        }
    }
}
```

### Obtener ID del usuario (sistema o autenticado)

```php
use App\Support\SystemUser;

// En cualquier lugar de tu código
$userId = SystemUser::id(); // Retorna Auth::id() o SystemUser::ID
```

## ⚙️ Configuración

### 1. Ejecutar el seeder del usuario sistema

```bash
php artisan db:seed --class=SystemUserSeeder
```

### 2. Incluir en DatabaseSeeder (siempre primero)

```php
public function run(): void
{
    $this->call([
        SystemUserSeeder::class, // ⚠️ SIEMPRE PRIMERO
        UserSeeder::class,
        FuenteSeeder::class,
        // ... otros seeders
    ]);
}
```

### 3. Actualizar seeders existentes

Envolver el código que crea registros con `SystemUser::actAs()`:

```php
// Antes
public function run(): void
{
    Fuente::create(['nombre' => 'Prensa Libre', 'tipo' => 'periodico']);
}

// Después
public function run(): void
{
    SystemUser::actAs(function () {
        Fuente::create(['nombre' => 'Prensa Libre', 'tipo' => 'periodico']);
    });
}
```

## 🔍 Verificación

### Ver el usuario sistema

```bash
php artisan tinker
>>> App\Models\User::find(1)
```

### Ver registros creados por el sistema

```sql
SELECT * FROM carpetas WHERE created_by = 1;
SELECT * FROM fuentes WHERE created_by = 1;
SELECT * FROM items WHERE created_by = 1;
```

### Verificar en el panel de Filament

1. Ir a `/admin/users`
2. Buscar el usuario "Sistema"
3. Ver que tiene ID = 1

## 📊 Beneficios

✅ **Trazabilidad completa** - Todos los registros tienen un `created_by` y `updated_by`  
✅ **No más NULL** - Evita valores `NULL` en campos de userstamps  
✅ **Auditoría clara** - Fácil identificar registros creados por el sistema  
✅ **Sin errores** - No más excepciones por falta de usuario autenticado  
✅ **Reutilizable** - Una sola implementación para todo el proyecto  
✅ **Seguro** - Contraseña aleatoria imposible de adivinar  

## ⚠️ Consideraciones

### El usuario sistema NO debe:

- ❌ Usarse para login manual en el panel
- ❌ Tener permisos de administrador visible
- ❌ Ser modificado o eliminado manualmente
- ❌ Compartir su contraseña (se genera aleatoria)

### El usuario sistema DEBE:

- ✅ Crearse SIEMPRE PRIMERO en los seeders
- ✅ Tener ID fijo = 1
- ✅ Mantenerse en todas las bases de datos (dev, staging, prod)
- ✅ Excluirse de listados de usuarios "reales" en reportes

## 🔒 Seguridad

- La contraseña se genera aleatoria con `bin2hex(random_bytes(16))`
- El email `system@archivovertical.local` usa dominio `.local` (no ruteable)
- No se puede autenticar vía panel (solo programáticamente)
- Se puede agregar a un middleware que bloquee login del usuario sistema

## 📝 Ejemplo completo

### Comando de importación con usuario sistema

```php
use App\Support\SystemUser;
use Illuminate\Support\Facades\Auth;

class ImportarArchivoCSV extends Command
{
    public function handle()
    {
        // 1. Verificar existencia
        if (!SystemUser::exists()) {
            $this->error('Usuario sistema no encontrado');
            return self::FAILURE;
        }

        // 2. Autenticar
        if (!Auth::check()) {
            Auth::login(SystemUser::get());
        }

        // 3. Realizar operaciones (con userstamps automáticos)
        DB::transaction(function () {
            Item::create(['titulo' => 'Artículo 1', ...]); // created_by = 1
            Carpeta::create(['codigo' => 'Z', ...]); // created_by = 1
        });

        return self::SUCCESS;
    }
}
```

## 🎯 Casos de uso en el proyecto

1. **Seeders** (`UserSeeder`, `FuenteSeeder`, `AnioSeeder`)
   - Crean datos iniciales con `created_by = 1`

2. **Comando de importación CSV** (`ImportarArchivoCSV`)
   - Importa 396 items con `created_by = 1`

3. **Futuras migraciones con datos**
   - Cualquier migración que cree registros

4. **Jobs de procesamiento**
   - OCR automático de documentos
   - Generación de estadísticas

5. **Observers**
   - Generación automática de thumbnails
   - Indexación de búsqueda

## 🔗 Referencias

- Paquete Userstamps: https://github.com/wildside/userstamps
- Laravel Authentication: https://laravel.com/docs/authentication
- Laravel Seeders: https://laravel.com/docs/seeding

---

**Última actualización**: Octubre 2025  
**Versión del sistema**: 0.3.0

