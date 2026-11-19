<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
class AccessSeeder extends Seeder {
 public function run(): void {
  $names=['accesos gestionar','solicitudes ver','citas gestionar','contenido gestionar','horarios gestionar'];
  foreach($names as $name) Permission::findOrCreate($name,'web');
  foreach(['ADMINISTRADOR','CONCILIADOR','RECEPCIONISTA'] as $name){$existing=Role::where('name',$name)->where('guard_name','web')->exists();$role=Role::findOrCreate($name,'web');if(!$existing && $name==='ADMINISTRADOR')$role->givePermissionTo($names);if(!$existing && $name==='RECEPCIONISTA')$role->givePermissionTo(['solicitudes ver','citas gestionar']);}
  app(PermissionRegistrar::class)->forgetCachedPermissions();
 }
}
