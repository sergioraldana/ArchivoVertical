# Changelog

Todos los cambios notables en este proyecto serán documentados en este archivo.

El formato está basado en [Keep a Changelog](https://keepachangelog.com/es-ES/1.1.0/),
y este proyecto adhiere a [Semantic Versioning](https://semver.org/lang/es/).

## [Unreleased]

### Añadido
- **Sistema de Usuario Sistema** para operaciones automatizadas:
  - Usuario especial con ID fijo `1` para seeders, comandos y observers
  - Clase helper `SystemUser` con métodos estáticos
  - `SystemUserSeeder` que se ejecuta primero en todos los seeders
  - Documentación completa en `SISTEMA_USUARIO.md`
  - Integración automática en todos los seeders existentes
  - Soporte en el comando `ImportarArchivoCSV`
  - Resuelve el problema de `created_by` y `updated_by` en userstamps
- **Spatie Activity Log** (`spatie/laravel-activitylog` v4.10):
  - Registro automático de cambios en **todos los modelos**
  - Tabla `activity_log` con columnas: subject, causer, properties, event, batch_uuid
  - Integrado en 9 modelos: Item, Carpeta, Fuente, Seccion, Autor, Tema, Anio, Numero, User
  - Configuración publicada en `config/activitylog.php`
  - Documentación completa en `ACTIVITY_LOG.md`
  - Solo registra campos modificados (logOnlyDirty)
  - No registra logs vacíos automáticamente
  - Helper `ActivityLogInfolist` para visualización de activity logs en Filament
  - Los logs se pueden consultar mediante tinker o crear un recurso dedicado

### Cambiado
- Todos los seeders ahora usan `SystemUser::actAs()` para operaciones
- `DatabaseSeeder` incluye `SystemUserSeeder` como primer seeder
- Comando de importación CSV autentica automáticamente como usuario sistema
- Todos los modelos ahora registran automáticamente sus modificaciones con Activity Log
- Integración perfecta entre Activity Log, Userstamps y SystemUser

---

## [0.3.0] - 2025-10-26

### Añadido

#### Esquema de Base de Datos
- **13 migraciones** completas para el sistema de archivo vertical:
  - `carpetas` - Organización por letra temática
  - `fuentes` - Periódicos, revistas y otras publicaciones
  - `secciones` - Secciones de publicaciones
  - `autores` - Autores de artículos
  - `temas` - Temas y categorías
  - `anios` - Años de publicación por fuente
  - `numeros` - Números de edición (soporta romanos)
  - `items` - Artículos y documentos principales
  - `item_autor` - Relación many-to-many items-autores
  - `item_tema` - Relación many-to-many items-temas
- Soporte para **userstamps** (created_by, updated_by) en todas las tablas
- Índices optimizados para búsquedas frecuentes
- Soporte para archivos múltiples en formato JSON
- Campos para OCR y digitalización

#### Modelos Eloquent
- **10 modelos** con relaciones completas:
  - `Carpeta`, `Fuente`, `Seccion`, `Autor`, `Tema`
  - `Anio`, `Numero`, `Item`, `User`
- Enum `TipoFuente` implementando `HasLabel` de Filament:
  - Periódico, Revista, Sitio Web, Libro, Documento, Otro
- Trait `Userstamps` integrado en todos los modelos
- Relaciones `belongsTo`, `hasMany` y `belongsToMany` configuradas
- Accessors personalizados para datos derivados

#### Recursos Filament
- **9 recursos CRUD** completamente funcionales:
  - `CarpetaResource` (simple)
  - `FuenteResource` (simple)
  - `SeccionResource` (simple)
  - `AutorResource` (simple)
  - `TemaResource` (simple)
  - `AnioResource` (simple)
  - `NumeroResource` (simple)
  - `ItemResource` (completo con form y table)
  - `UserResource` (gestión de usuarios)
- **Recursos simples** con modales para create/edit/delete
- Forms con validación completa y creación inline de relaciones
- Tables con filtros, ordenamiento y búsqueda
- Iconos personalizados para cada recurso
- Labels en español
- Soporte para archivos múltiples con FileUpload

#### Sistema de Importación CSV
- **Comando Artisan** `archivo:importar-csv`:
  - Importación masiva desde archivo CSV
  - **Análisis inteligente de carpetas**: Detecta automáticamente nombres de carpetas basándose en el tema más frecuente por letra
  - Manejo de duplicados con `firstOrCreate()`
  - Transacciones de base de datos (rollback en caso de error)
  - Barra de progreso en tiempo real
  - Estadísticas detalladas al finalizar
  - Manejo robusto de errores con reportes por línea
  - Parseo de fechas (formato d/m/Y)
  - Detección automática de tipo de fuente
  - Asociación many-to-many de autores y temas
  - **Dos pasadas**: Primera para análisis, segunda para importación
- Importados **396 items** con 233 autores y 459 temas del CSV histórico
- **Test suite** para el comando de importación

#### Gestión de Usuarios
- Resource Filament para administrar usuarios:
  - Creación, edición y eliminación
  - Validación de email único
  - Hashing automático de contraseñas
  - Confirmación de contraseña en creación
- **Página de perfil** para usuarios autenticados:
  - Edición de email
  - Cambio de contraseña con validación de contraseña actual
  - Validación de contraseña mínimo 5 caracteres
  - Confirmación de nueva contraseña
  - Validación con `currentPassword()` de Filament

#### Seeders
- `UserSeeder` - Usuario inicial: aldana.sergio@correoe.usac.edu.gt
- `FuenteSeeder` - 7 fuentes periodísticas iniciales
- `NumeroSeeder` - 540 números (XL-LXXV) para Prensa Libre 2010-2024
- Función helper `convertirARomano()` para números romanos
- `DatabaseSeeder` orquestando todos los seeders

#### Documentación
- `IMPORTACION.md` - Guía completa de importación CSV:
  - Estructura del CSV y mapeo a base de datos
  - Ejemplos de uso del comando
  - Características especiales del importador
  - Solución de problemas
  - Estadísticas y salidas esperadas
  - Mejores prácticas

### Cambiado
- Campo `codigo_ref` **eliminado** de la tabla items (simplificación)
- Migración items actualizada para remover campo obsoleto
- Model `Item` actualizado sin `codigo_ref` en fillable
- Forms y Tables de Filament actualizados
- Validación de contraseña flexible (mínimo 5 caracteres)

### Corregido
- Imports de Filament 4 corregidos:
  - `Group` y `Section` ahora desde `Filament\Schemas\Components`
- Validación de email único en perfil ignorando usuario actual
- Blade component para perfil usando form estándar
- Property `$view` en `EditProfile` como no-estática

### Características Técnicas
- **Sistema inteligente de carpetas**: Analiza 396 registros y determina nombres automáticamente:
  - Z → "Zoologico" (tema dominante)
  - V → "Vacunas" (150+ menciones)
  - W → "Wyld, Guatemala Adolfo"
  - X → "Xincas"
  - Y → "Yemen- Revoluciones, 1994"
- **Normalización de nombres**: Limpieza de sufijos y capitalización
- **Cache de análisis**: Evita procesamiento redundante
- **Manejo de errores por línea**: 410 errores identificados (filas vacías)
- **Tiempo de importación**: ~3.5 segundos para 800+ líneas

### Archivos nuevos
- `app/Console/Commands/ImportarArchivoCSV.php` - Comando de importación
- `app/Models/` - 9 modelos Eloquent nuevos
- `app/TipoFuente.php` - Enum para tipos de fuente
- `app/Filament/Resources/` - 9 recursos completos
- `app/Filament/Pages/EditProfile.php` - Página de perfil
- `database/migrations/` - 13 nuevas migraciones
- `database/seeders/` - 4 seeders
- `tests/Feature/ImportarArchivoCSVTest.php` - Tests
- `IMPORTACION.md` - Documentación de importación
- `resources/views/filament/pages/edit-profile.blade.php` - Vista de perfil

---

## [0.2.0] - 2025-10-25

### Añadido
- Filament PHP v4.1.10 instalado y configurado
  - Panel de administración configurado en `/admin`
  - Proveedor de servicios `AdminPanelProvider`
  - Assets publicados (CSS, JS, fuentes Inter)
  - Componentes: Forms, Tables, Schemas, Actions, Notifications, Widgets
- Laravel Boost v1.5.1 para integración con IA
  - Servidor MCP (Model Context Protocol) configurado
  - 14 guías de desarrollo específicas para el stack del proyecto
  - Integración con Cursor, PhpStorm, VS Code y Junie
  - Herramientas MCP: search-docs, tinker, database-query, browser-logs, etc.
- Configuración de guías para:
  - Laravel 12
  - Filament v4
  - Livewire v3
  - Pest v4
  - Tailwind v4
  - PHP 8.3
  - Laravel Pint

### Archivos nuevos
- `.cursor/mcp.json` - Configuración del servidor MCP
- `.cursor/rules/laravel-boost.mdc` - Guías de desarrollo
- `.junie/` - Configuración para Junie AI
- `app/Providers/Filament/AdminPanelProvider.php` - Proveedor del panel
- `boost.json` - Configuración de Laravel Boost
- `public/css/filament/` - Assets CSS de Filament
- `public/js/filament/` - Assets JavaScript de Filament
- `public/fonts/filament/` - Fuentes Inter de Filament

---

## [0.1.0] - 2025-10-25

### Añadido
- Configuración inicial del proyecto Laravel 12
- Estructura base del proyecto
- Configuración de Git
- Repositorio remoto en GitHub: `https://github.com/sergioraldana/ArchivoVertical`
- Dependencias iniciales:
  - Laravel Framework v12.0
  - Laravel Tinker v2.10.1
  - Laravel Pint v1.24
  - Laravel Sail v1.41
  - Pest v4.1
  - Pest Plugin Laravel v4.0

### Configurado
- Conexión SSH con GitHub
- Estructura de directorios estándar de Laravel 12
- Composer con paquetes base

---

## Tipos de cambios

- **Añadido** para funcionalidades nuevas.
- **Cambiado** para cambios en funcionalidades existentes.
- **Obsoleto** para funcionalidades que serán eliminadas.
- **Eliminado** para funcionalidades eliminadas.
- **Corregido** para corrección de errores.
- **Seguridad** para vulnerabilidades.

[Unreleased]: https://github.com/sergioraldana/ArchivoVertical/compare/v0.3.0...HEAD
[0.3.0]: https://github.com/sergioraldana/ArchivoVertical/compare/v0.2.0...v0.3.0
[0.2.0]: https://github.com/sergioraldana/ArchivoVertical/compare/v0.1.0...v0.2.0
[0.1.0]: https://github.com/sergioraldana/ArchivoVertical/releases/tag/v0.1.0

