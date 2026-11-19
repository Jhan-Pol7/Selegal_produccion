# SELEGAL

Aplicación Laravel conectada al esquema MySQL de `database/selegal_db.sql`. MySQL Workbench permite administrar la base; la aplicación se conecta al servidor MySQL mediante las variables `DB_*` del archivo `.env`.

## Inicio local

1. Configura en `.env` el host, puerto, usuario y contraseña del servidor MySQL. La base utilizada es `selegal_db`.
2. Instala las tablas y datos iniciales ejecutando `php artisan selegal:instalar-db`. También puedes ejecutar el archivo SQL en MySQL Workbench. El comando utiliza `CREATE TABLE IF NOT EXISTS` y no elimina las tablas existentes.
3. Crea el primer administrador con `php artisan selegal:admin`; la contraseña se introduce de forma oculta.
4. Ejecuta `php artisan serve --host=127.0.0.1 --port=8017` y abre `http://127.0.0.1:8017`.
5. En el panel, configura los horarios de atención. La fecha elegida determina el día de la semana al que se aplicarán los horarios.

No ejecutes las migraciones antiguas de Laravel para crear las tablas del centro: pertenecen al prototipo previo con SQLite y no son el esquema MySQL proporcionado. Las sesiones, la caché y la cola están configuradas para funcionar sin tablas adicionales.

La dirección solo puede mostrarse si está acreditada en la tabla `centros`. Los avisos de solicitudes se registran en `notificaciones`; el envío de correos requiere una configuración adicional. El ticket está diseñado para impresoras térmicas de 80 mm y debe comprobarse con la impresora disponible. Incluye `public/uploads/members` y la base de datos en las copias de seguridad.

## Roles y permisos

El panel usa Spatie Laravel Permission con el guard `web` y la tabla `usuarios`. En una instalación nueva, después de importar el esquema base, ejecuta únicamente la migración específica de permisos y el inicializador:

```powershell
php artisan migrate --path=database/migrations/2026_11_11_135102_create_permission_tables.php
php artisan db:seed --class=AccessSeeder
```

Revisa que las cinco tablas de Spatie no existan antes de ejecutar la migración. Crea el primer administrador con `php artisan selegal:admin` después de inicializar roles. Para una base ya existente, respalda los datos y revisa la migración antes de ejecutarla. El inicializador no reemplaza la configuración de roles ya existentes.

El administrador gestiona usuarios, roles y permisos en **Gestión de accesos**. Recepción inicia con acceso a solicitudes y citas. El rol conciliador empieza sin acceso a expedientes o citas porque aún no existe un mecanismo para asignar registros a cada conciliador. Los permisos actuales de solicitudes y citas permiten ver todos los registros del módulo; no son permisos por expediente.
