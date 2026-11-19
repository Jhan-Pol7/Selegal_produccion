<?php
namespace Tests\Feature;
use Tests\TestCase;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
class AccessControlTest extends TestCase {
 protected function setUp(): void {parent::setUp();Schema::create('usuarios',function(Blueprint $t){$t->id();$t->string('nombre');$t->string('usuario');$t->string('email');$t->string('password');$t->string('rol');$t->boolean('activo');$t->timestamps();});Schema::create('centros',function(Blueprint $t){$t->id();$t->string('nombre_comercial');$t->string('whatsapp')->nullable();});require_once database_path('migrations/2026_11_11_135102_create_permission_tables.php');$migration=require database_path('migrations/2026_11_11_135102_create_permission_tables.php');$migration->up();app(PermissionRegistrar::class)->forgetCachedPermissions();}
 public function test_routes_require_role_and_permission(): void {$adminRole=Role::create(['name'=>'ADMINISTRADOR','guard_name'=>'web']);$reception=Role::create(['name'=>'RECEPCIONISTA','guard_name'=>'web']);$conciliator=Role::create(['name'=>'CONCILIADOR','guard_name'=>'web']);$permission=Permission::create(['name'=>'citas gestionar','guard_name'=>'web']);$reception->givePermissionTo($permission);$person=User::create(['nombre'=>'Prueba','usuario'=>'test','email'=>'test@example.invalid','password'=>'secret123456','rol'=>'PERSONAL','activo'=>true]);$person->assignRole($reception);$this->actingAs($person)->get('/admin/accesos')->assertForbidden();$this->actingAs($person)->get('/admin/usuarios')->assertForbidden();$this->actingAs($person)->post('/admin/equipo')->assertForbidden();$person->syncRoles([$conciliator]);$this->actingAs($person->fresh())->get('/admin/citas')->assertForbidden();$this->actingAs($person->fresh())->get('/admin/solicitudes')->assertForbidden();}
 public function test_admin_cannot_remove_own_admin_role(): void {$role=Role::create(['name'=>'ADMINISTRADOR','guard_name'=>'web']);$person=User::create(['nombre'=>'Prueba','usuario'=>'test','email'=>'test@example.invalid','password'=>'secret123456','rol'=>'ADMINISTRADOR','activo'=>true]);$person->assignRole($role);$this->actingAs($person)->post('/admin/accesos/usuarios/'.$person->id,[])->assertSessionHasErrors('roles');$this->assertTrue($person->fresh()->hasRole('ADMINISTRADOR'));}
 public function test_admin_can_open_access_management(): void {\Illuminate\Support\Facades\DB::table('centros')->insert(['nombre_comercial'=>'SELEGAL','whatsapp'=>'921778133']);$role=Role::create(['name'=>'ADMINISTRADOR','guard_name'=>'web']);$person=User::create(['nombre'=>'Prueba','usuario'=>'test','email'=>'test@example.invalid','password'=>'secret123456','rol'=>'ADMINISTRADOR','activo'=>true]);$person->assignRole($role);$this->actingAs($person)->get('/admin/accesos')->assertOk()->assertSee('Gestión de accesos')->assertSee('ADMINISTRADOR');}
}
