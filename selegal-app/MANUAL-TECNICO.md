# SELEGAL · Mini manual técnico

## Arquitectura

Aplicación Laravel con PHP, Blade, CSS y JavaScript. Utiliza MySQL y la base `selegal_db`; MySQL Workbench sirve para administrarla. La zona horaria de la aplicación es **America/Lima**.

- `routes/web.php`: rutas públicas y administrativas.
- `app/Http/Controllers/SiteController.php`: solicitudes, citas, horarios y gestión de contenidos.
- `app/Models/User.php`: autenticación contra la tabla `usuarios`.
- `resources/views/site.blade.php`: vistas públicas y panel.
- `resources/views/ticket.blade.php`: ticket para papel térmico de 80 mm con código Code 39 en SVG.
- `public/assets/`: estilos, interacciones, logotipo y balanza 3D.
- `database/selegal_db.sql`: esquema MySQL y datos institucionales iniciales.

## Puesta en marcha local

Requisitos: PHP con `pdo_mysql`, Composer y MySQL 8.0.16 o posterior. Desde la carpeta `selegal-app`:

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
```

Si ya existe `.env`, **consérvalo** y omite los dos comandos que lo reemplazarían o cambiarían su clave. Configura en ese archivo `DB_CONNECTION=mysql`, `DB_HOST`, `DB_PORT`, `DB_DATABASE=selegal_db`, `DB_USERNAME` y `DB_PASSWORD` con los datos del servidor local. No compartas el archivo ni sus credenciales.

```powershell
php artisan selegal:instalar-db
php artisan selegal:admin
php artisan serve --host=127.0.0.1 --port=8017
```

El instalador aplica el SQL proporcionado con tablas `IF NOT EXISTS`; no reemplaza la estructura de tablas que ya existen. Alternativamente, ejecuta el SQL desde Workbench. No uses las migraciones antiguas del prototipo SQLite para crear el esquema del centro.

## Cómo se relacionan los datos

Una `solicitud` pertenece a un `solicitante`, una `materia` y un `centro`. La tabla `citas` enlaza una solicitud con un `horario_atencion` semanal. Una solicitud admite una cita; el índice único del horario activo evita reservas simultáneas para el mismo centro, fecha y hora. Las citas canceladas liberan el horario. El panel permite crear una solicitud junto con su cita en una transacción.

Los casos con la etiqueta **Contenido de ejemplo** son ficticios. El comando `php artisan selegal:casos-ejemplo` crea 70 casos identificados y se detiene si ya existen ejemplos con ese prefijo; no lo ejecutes de nuevo para generar datos reales.

## Correo, archivos y copias de seguridad

Los avisos se registran en `notificaciones`. Para enviar correos realmente, configura un transporte SMTP o proveedor compatible en `MAIL_*` dentro de `.env`; con el transporte `log` quedan pendientes y no salen por correo. El botón de WhatsApp abre una conversación, pero el formulario no envía mensajes de WhatsApp por sí solo.

Respalda la base MySQL y `public/uploads/members`. Para restaurar, usa una copia verificada de ambos en el entorno correspondiente. Mantén `.env` fuera del repositorio y configura HTTPS y `APP_DEBUG=false` antes de publicar.

## Comprobaciones rápidas

```powershell
php artisan route:list --except-vendor
php artisan view:cache
php artisan config:clear
```

Si no aparecen horarios, revísalos en **Administración → Horarios**. Si el puerto 8017 está ocupado, inicia Laravel en otro puerto libre y usa esa dirección. Antes de operar con una impresora térmica, comprueba el tamaño de papel y la lectura física del código de barras; la lectura depende de la impresora y del escáner.
