# 📥 Guía de Importación de Datos CSV

## Descripción

Este documento explica cómo importar datos del archivo vertical histórico desde un archivo CSV al sistema.

## 🗂️ Estructura del CSV

El archivo CSV debe tener las siguientes columnas:

| Columna | Descripción | Requerido | Mapeo en BD |
|---------|-------------|-----------|-------------|
| `No.` | Número consecutivo | No | - |
| `Letra` | Código de carpeta (una letra) | **Sí** | `carpetas.codigo` |
| `Correlativo` | Referencia del documento | No | Para nombre de carpeta |
| `Origen` | Nombre de la fuente | No | `fuentes.nombre` |
| `Año` | Año (romano o numérico) | No | `anios.anio` |
| `Epoca` | Época | No | - |
| `Fecha` | Fecha de publicación (d/m/Y) | No | `items.fecha_publicacion` |
| `Número` | Número de edición | No | `numeros.numero` |
| `Página` | Página del artículo | No | `items.pagina` |
| `Sección` | Sección de la publicación | No | `secciones.nombre` |
| `Título` | Título del artículo | **Sí** | `items.titulo` |
| `Subtítulo` | Subtítulo del artículo | No | `items.subtitulo` |
| `Autores` | Autores (separados por comas) | No | `autores` (many-to-many) |
| `Tema 1-5` | Temas del artículo | No | `temas` (many-to-many) |

### Notas sobre el formato:

- **Fechas**: Deben estar en formato `dd/mm/yyyy` (ejemplo: `10/11/2024`)
- **Autores**: Separados por comas (ejemplo: `Juan Pérez, María López`)
- **Temas**: Hasta 5 temas por artículo
- **Valores vacíos**: Use `-` o deje el campo vacío

## 🚀 Uso del Comando

### Sintaxis básica

```bash
php artisan archivo:importar-csv [archivo]
```

### Ejemplos

#### 1. Importar usando la ruta por defecto

```bash
php artisan archivo:importar-csv
```

Por defecto, el comando busca el archivo en:
```
/ruta/del/proyecto/Archivo Vertical Histórico Hoja 1.csv
```

#### 2. Importar desde una ruta específica

```bash
php artisan archivo:importar-csv /ruta/completa/al/archivo.csv
```

#### 3. Importar desde un archivo en el directorio storage

```bash
php artisan archivo:importar-csv storage/app/archivo.csv
```

## 📊 Proceso de Importación

El comando realiza los siguientes pasos:

1. ✅ **Validación**: Verifica que el archivo existe
2. 🔍 **Confirmación**: Solicita confirmación antes de proceder
3. 📁 **Lectura**: Lee el archivo CSV línea por línea
4. 🔄 **Procesamiento**: Para cada fila:
   - Crea o encuentra la **Carpeta** correspondiente
   - Crea o encuentra la **Fuente**
   - Crea o encuentra el **Año** (si aplica)
   - Crea o encuentra el **Número** (si aplica)
   - Crea o encuentra la **Sección** (si aplica)
   - Crea el **Item** (artículo)
   - Asocia los **Autores** al item
   - Asocia los **Temas** al item
5. 📈 **Estadísticas**: Muestra un resumen de la importación

## 🎯 Características Especiales

### Transacciones
- Toda la importación se ejecuta dentro de una **transacción de base de datos**
- Si ocurre un error, **todos los cambios se revierten**

### Manejo de duplicados
- El comando utiliza `firstOrCreate()` para **evitar duplicados**:
  - Carpetas: Por `codigo`
  - Fuentes: Por `nombre`
  - Años: Por `anio` + `fuente_id`
  - Números: Por `numero` + `anio_id`
  - Secciones: Por `nombre` + `fuente_id`
  - Autores: Por `nombre`
  - Temas: Por `nombre`

### Detección automática de tipo de fuente
El comando detecta automáticamente el tipo de fuente basándose en el nombre:
- `Periodico`: Si contiene "prensa", "diario", "siglo", "gráfico", "hora"
- `Revista`: Si contiene "revista"
- `Periodico` (por defecto): En otros casos

### Manejo de errores
- Errores en filas individuales **no detienen** la importación
- Se muestra un **contador de errores** al final
- Los errores se registran con el **número de línea** del CSV

## 📈 Salida del Comando

### Durante la importación

```
📂 Importando desde: /ruta/al/archivo.csv

¿Deseas continuar con la importación? (yes/no) [yes]:
 > yes

🚀 Iniciando importación...

 156 registros procesados | 2.5s | 12MB
```

### Al finalizar

```
✅ Importación completada exitosamente

┌─────────────┬──────────────────┐
│ Entidad     │ Cantidad Creada  │
├─────────────┼──────────────────┤
│ Carpetas    │ 5                │
│ Fuentes     │ 7                │
│ Años        │ 15               │
│ Números     │ 45               │
│ Secciones   │ 23               │
│ Autores     │ 78               │
│ Temas       │ 145              │
│ Items       │ 396              │
│ Errores     │ 0                │
└─────────────┴──────────────────┘

⏱️  Tiempo total: 12.34 segundos
```

## ⚠️ Consideraciones Importantes

### Antes de importar

1. **Respalda tu base de datos**:
   ```bash
   php artisan db:backup  # Si tienes configurado un paquete de backup
   ```

2. **Verifica el formato del CSV**:
   - Codificación: UTF-8
   - Separador: Coma (`,`)
   - Primera línea: Encabezados

3. **Limpia datos duplicados** si es necesario:
   ```bash
   # Opcional: limpiar datos previos
   php artisan migrate:fresh --seed
   ```

### Durante la importación

- ⏱️ **Tiempo estimado**: Depende del tamaño del archivo (aproximadamente 100-200 registros/segundo)
- 💾 **Memoria**: Para archivos grandes (>10,000 registros), asegúrate de tener suficiente memoria PHP
- 🔒 **No interrumpas**: Espera a que termine para evitar datos inconsistentes

### Después de importar

1. **Verifica los datos** en el panel de Filament
2. **Revisa errores** en la tabla de estadísticas
3. **Ajusta manualmente** los registros con errores si es necesario

## 🧪 Testing

Ejecuta los tests del comando:

```bash
php artisan test --filter=ImportarArchivoCSVTest
```

## 🐛 Solución de Problemas

### Error: "El archivo no existe"

**Problema**: La ruta del archivo es incorrecta

**Solución**: Verifica la ruta completa:
```bash
ls -la "/ruta/al/archivo.csv"
php artisan archivo:importar-csv "/ruta/completa/al/archivo.csv"
```

### Error: "Faltan campos obligatorios"

**Problema**: Una fila del CSV no tiene `letra` o `título`

**Solución**: 
- Revisa el CSV y completa los campos obligatorios
- O edita el comando para manejar este caso específico

### Error: "Memory limit exceeded"

**Problema**: El archivo es demasiado grande

**Solución**:
1. Aumenta el límite de memoria en `php.ini`:
   ```ini
   memory_limit = 512M
   ```
2. O divide el CSV en archivos más pequeños

### Error: "Transaction rolled back"

**Problema**: Ocurrió un error crítico durante la importación

**Solución**:
- Revisa los logs de Laravel: `storage/logs/laravel.log`
- Ejecuta el comando con más detalle de errores
- Reporta el error con el stack trace completo

## 📝 Ejemplo de CSV Válido

```csv
No.,Letra,Correlativo,Origen,Año,Epoca,Fecha,Número,Página,Sección,Título,Subtítulo,Autores,Tema 1,Tema 2,Tema 3,Tema 4,Tema 5
1,Z,1.37,Prensa Libre,LXXIV,,10/11/2024,24754,9,Plt,100 años del Parque Zoológico Nacional la Aurora,,-,Zoológico - Guatemala,Zoologico - La Aurora,100 años,,,
2,V,1.8,Siglo XXI,LXVI,,29/06/2017,22091,22,Actualidad,Ciberataque mundial es difícil de detener,,-,Virus informaticos,Delitos por computador,Seguridad  en computadores,,,
```

## 🔗 Referencias

- [Documentación de Laravel Console](https://laravel.com/docs/artisan)
- [Eloquent Relationships](https://laravel.com/docs/eloquent-relationships)
- [Carbon (Fechas)](https://carbon.nesbot.com/docs/)

---

**Última actualización**: Octubre 2025  
**Versión del comando**: 1.0.0

