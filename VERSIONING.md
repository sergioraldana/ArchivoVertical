# Guía de Versionado

Este proyecto sigue [Semantic Versioning 2.0.0](https://semver.org/lang/es/).

## Formato de Versión

Dado un número de versión `MAJOR.MINOR.PATCH`, incrementa:

- **MAJOR** (X.0.0): Cambios incompatibles con versiones anteriores
- **MINOR** (0.X.0): Nueva funcionalidad compatible con versiones anteriores
- **PATCH** (0.0.X): Correcciones de errores compatibles con versiones anteriores

## Proceso de Release

### 1. Actualizar la versión

Edita los siguientes archivos con la nueva versión:

- `VERSION`
- `composer.json` (campo `version`)
- `CHANGELOG.md` (mueve los cambios de [Unreleased] a la nueva versión)

### 2. Actualizar CHANGELOG.md

```markdown
## [X.Y.Z] - YYYY-MM-DD

### Añadido
- Nuevas funcionalidades

### Cambiado
- Cambios en funcionalidades existentes

### Corregido
- Correcciones de errores
```

### 3. Hacer commit y tag

```bash
# Commit de la versión
git add VERSION composer.json CHANGELOG.md
git commit -m "Release version X.Y.Z"

# Crear tag anotado
git tag -a vX.Y.Z -m "Version X.Y.Z"

# Subir cambios y tags
git push origin main
git push origin vX.Y.Z
```

### 4. Crear Release en GitHub

1. Ve a: https://github.com/sergioraldana/ArchivoVertical/releases/new
2. Selecciona el tag `vX.Y.Z`
3. Título: `Version X.Y.Z`
4. Descripción: Copia el contenido relevante del CHANGELOG.md
5. Publica el release

## Ejemplos de Versionado

### Version 0.1.0 → 0.2.0
Agregar Filament PHP (nueva funcionalidad) → MINOR

### Version 0.2.0 → 0.2.1
Corregir bug en formulario de Filament → PATCH

### Version 0.2.1 → 1.0.0
Primera versión estable para producción → MAJOR

### Version 1.0.0 → 2.0.0
Cambio de estructura de base de datos incompatible → MAJOR

## Comandos Útiles

### Ver versión actual
```bash
cat VERSION
```

### Ver últimos tags
```bash
git tag --sort=-v:refname | head -5
```

### Ver cambios desde el último tag
```bash
git log $(git describe --tags --abbrev=0)..HEAD --oneline
```

### Crear release automático (futuro)
```bash
# Opción: usar herramientas como:
# - conventional-changelog
# - standard-version
# - semantic-release
```

## Nomenclatura de Commits

Se recomienda usar [Conventional Commits](https://www.conventionalcommits.org/es/):

- `feat:` Nueva funcionalidad (MINOR)
- `fix:` Corrección de errores (PATCH)
- `docs:` Cambios en documentación
- `style:` Formato de código
- `refactor:` Refactorización de código
- `test:` Agregar o modificar tests
- `chore:` Tareas de mantenimiento

**Ejemplo:**
```bash
git commit -m "feat: agregar recurso de usuarios en Filament"
git commit -m "fix: corregir validación de email en formulario"
git commit -m "docs: actualizar README con instrucciones de instalación"
```

## Pre-releases

Para versiones de desarrollo o beta:

- `1.0.0-alpha.1` - Versión alpha
- `1.0.0-beta.1` - Versión beta
- `1.0.0-rc.1` - Release candidate

```bash
git tag -a v1.0.0-beta.1 -m "Version 1.0.0 Beta 1"
```

