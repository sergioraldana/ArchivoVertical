# Changelog

Todos los cambios notables en este proyecto serán documentados en este archivo.

El formato está basado en [Keep a Changelog](https://keepachangelog.com/es-ES/1.1.0/),
y este proyecto adhiere a [Semantic Versioning](https://semver.org/lang/es/).

## [Unreleased]

### Por hacer
- Crear usuario administrador para Filament
- Configurar base de datos
- Crear primer recurso de Filament

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

[Unreleased]: https://github.com/sergioraldana/ArchivoVertical/compare/v0.2.0...HEAD
[0.2.0]: https://github.com/sergioraldana/ArchivoVertical/compare/v0.1.0...v0.2.0
[0.1.0]: https://github.com/sergioraldana/ArchivoVertical/releases/tag/v0.1.0

