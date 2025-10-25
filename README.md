# ArchivoVertical

![Version](https://img.shields.io/badge/version-0.2.0-blue.svg)
![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)
![Filament](https://img.shields.io/badge/Filament-4.x-orange.svg)
![PHP](https://img.shields.io/badge/PHP-8.3-purple.svg)
![License](https://img.shields.io/badge/license-MIT-green.svg)

Sistema de gestión de archivo vertical construido con Laravel 12 y Filament PHP v4.

## 📋 Tabla de Contenidos

- [Características](#-características)
- [Requisitos](#-requisitos)
- [Instalación](#-instalación)
- [Stack Tecnológico](#-stack-tecnológico)
- [Estructura del Proyecto](#-estructura-del-proyecto)
- [Desarrollo](#-desarrollo)
- [Testing](#-testing)
- [Versionado](#-versionado)
- [Changelog](#-changelog)
- [Licencia](#-licencia)

## ✨ Características

- 🎨 **Panel de Administración** con Filament PHP v4
- 🔐 **Autenticación y Autorización** con Laravel
- 📊 **Tablas Interactivas** con filtros y búsqueda
- 📝 **Formularios Dinámicos** para gestión de datos
- 🤖 **Integración con IA** mediante Laravel Boost y MCP
- 🎯 **Testing Automatizado** con Pest v4
- 🎨 **Diseño Moderno** con Tailwind CSS v4
- ⚡ **Componentes Reactivos** con Livewire v3

## 🔧 Requisitos

- PHP 8.3 o superior
- Composer 2.x
- Node.js 18.x o superior
- MySQL 8.0 o PostgreSQL 13+
- Git

## 📦 Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/sergioraldana/ArchivoVertical.git
cd ArchivoVertical
```

### 2. Instalar dependencias

```bash
composer install
npm install
```

### 3. Configurar el entorno

```bash
cp .env.example .env
php artisan key:generate
```

Edita `.env` con tus credenciales de base de datos.

### 4. Ejecutar migraciones

```bash
php artisan migrate
```

### 5. Crear usuario administrador de Filament

```bash
php artisan make:filament-user
```

### 6. Compilar assets

```bash
npm run build
# o para desarrollo
npm run dev
```

### 7. Iniciar el servidor

```bash
php artisan serve
```

Accede a:
- **Aplicación**: http://localhost:8000
- **Panel Admin**: http://localhost:8000/admin

## 🛠️ Stack Tecnológico

### Backend
- **[Laravel 12](https://laravel.com)** - Framework PHP
- **[Filament PHP v4](https://filamentphp.com)** - Panel de administración
- **[Livewire v3](https://livewire.laravel.com)** - Componentes dinámicos
- **[Laravel Boost](https://boost.laravel.com)** - Integración con IA

### Frontend
- **[Tailwind CSS v4](https://tailwindcss.com)** - Framework CSS
- **[Alpine.js](https://alpinejs.dev)** - Framework JavaScript reactivo

### Testing
- **[Pest v4](https://pestphp.com)** - Framework de testing
- **[PHPUnit v12](https://phpunit.de)** - Unit testing

### Herramientas de Desarrollo
- **[Laravel Pint](https://laravel.com/docs/pint)** - Formateador de código PHP
- **[Laravel Sail](https://laravel.com/docs/sail)** - Entorno de desarrollo Docker

## 📁 Estructura del Proyecto

```
ArchivoVertical/
├── app/
│   ├── Filament/            # Recursos y configuración de Filament
│   │   └── Resources/       # Recursos CRUD
│   ├── Http/
│   ├── Models/
│   └── Providers/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── views/
│   └── css/
├── tests/
│   ├── Feature/
│   └── Unit/
├── .cursor/                 # Configuración de IA y MCP
│   ├── mcp.json
│   └── rules/
├── CHANGELOG.md             # Historial de cambios
├── VERSIONING.md            # Guía de versionado
└── VERSION                  # Versión actual
```

## 💻 Desarrollo

### Comandos útiles

```bash
# Iniciar servidor de desarrollo
composer run dev

# Ejecutar tests
php artisan test

# Formatear código
vendor/bin/pint

# Limpiar caché
php artisan optimize:clear

# Ver rutas disponibles
php artisan route:list

# Ver comandos de Filament
php artisan list filament
```

### Crear recursos de Filament

```bash
# Crear un recurso completo
php artisan make:filament-resource NombreModelo --generate

# Crear un recurso personalizado
php artisan make:filament-resource NombreModelo
```

### Testing

```bash
# Ejecutar todos los tests
php artisan test

# Ejecutar tests específicos
php artisan test --filter=NombreTest

# Con cobertura
php artisan test --coverage
```

## 🧪 Testing

Este proyecto usa **Pest v4** para testing. Los tests se encuentran en:

- `tests/Feature/` - Tests de funcionalidades completas
- `tests/Unit/` - Tests unitarios de componentes individuales
- `tests/Browser/` - Tests de navegador (Pest v4)

```bash
# Ejecutar todos los tests
php artisan test

# Ejecutar solo tests de unidad
php artisan test --testsuite=Unit

# Ejecutar con reporte detallado
php artisan test --parallel
```

## 📝 Versionado

Este proyecto sigue [Semantic Versioning 2.0.0](https://semver.org/lang/es/).

**Versión actual**: `0.2.0`

Para más detalles sobre el proceso de versionado, consulta [VERSIONING.md](VERSIONING.md).

### Formato de versión

- **MAJOR.MINOR.PATCH** (ej: 1.2.3)
- **MAJOR**: Cambios incompatibles con versiones anteriores
- **MINOR**: Nueva funcionalidad compatible
- **PATCH**: Correcciones de errores

## 📋 Changelog

Todos los cambios notables están documentados en [CHANGELOG.md](CHANGELOG.md).

### Versiones recientes

- **[0.2.0]** (2025-10-25) - Filament v4 y Laravel Boost
- **[0.1.0]** (2025-10-25) - Configuración inicial del proyecto

## 🤝 Contribuir

Las contribuciones son bienvenidas. Por favor:

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'feat: add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

### Convenciones de commits

Este proyecto usa [Conventional Commits](https://www.conventionalcommits.org/es/):

- `feat:` Nueva funcionalidad
- `fix:` Corrección de errores
- `docs:` Cambios en documentación
- `style:` Formato de código
- `refactor:` Refactorización
- `test:` Tests
- `chore:` Mantenimiento

## 📄 Licencia

Este proyecto está licenciado bajo la Licencia MIT. Ver el archivo [LICENSE](LICENSE) para más detalles.

## 🔗 Enlaces

- **Repositorio**: [https://github.com/sergioraldana/ArchivoVertical](https://github.com/sergioraldana/ArchivoVertical)
- **Issues**: [https://github.com/sergioraldana/ArchivoVertical/issues](https://github.com/sergioraldana/ArchivoVertical/issues)
- **Laravel Docs**: [https://laravel.com/docs](https://laravel.com/docs)
- **Filament Docs**: [https://filamentphp.com/docs](https://filamentphp.com/docs)

---

<p align="center">Hecho con ❤️ usando Laravel y Filament PHP</p>
