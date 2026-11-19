<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AccessController extends Controller {
 private const PROTECTED_ROLES=['ADMINISTRADOR','CONCILIADOR','RECEPCIONISTA'];
 private const SYSTEM_PERMISSIONS=['accesos gestionar','solicitudes ver','citas gestionar','contenido gestionar','horarios gestionar'];
 public function index(){return view('site',['page'=>'admin-access','roles'=>Role::where('guard_name','web')->with('permissions')->orderBy('name')->get(),'permissions'=>Permission::where('guard_name','web')->orderBy('name')->get(),'users'=>User::with('roles','permissions')->orderBy('nombre')->get(),'settings'=>['phone'=>DB::table('centros')->where('nombre_comercial','SELEGAL')->value('whatsapp')]]);}
 public function saveRole(Request $r){$v=$r->validate(['id'=>'nullable|integer|exists:roles,id','name'=>['required','string','max:100',Rule::unique('roles','name')->where('guard_name','web')->ignore($r->input('id'))],'permissions'=>'nullable|array','permissions.*'=>Rule::exists('permissions','id')->where('guard_name','web')]);DB::transaction(function()use($v){$role=isset($v['id'])?Role::where('guard_name','web')->findOrFail($v['id']):new Role(['guard_name'=>'web']);if($role->exists && in_array($role->name,self::PROTECTED_ROLES,true) && $role->name!==$v['name'])abort(422,'No se puede renombrar un rol del sistema.');$role->name=$v['name'];$role->save();$perms=Permission::where('guard_name','web')->whereIn('id',$v['permissions']??[])->get();if($role->name==='CONCILIADOR' && $perms->whereIn('name',['solicitudes ver','citas gestionar'])->isNotEmpty())abort(422,'El acceso a casos asignados aún no está disponible para conciliadores.');if($role->name==='ADMINISTRADOR' && !$perms->contains('name','accesos gestionar'))abort(422,'El administrador debe conservar el permiso de accesos.');$role->syncPermissions($perms);});return redirect()->route('admin.access')->with('success','Rol guardado');}
 public function deleteRole(Role $role){abort_if($role->guard_name!=='web'||in_array($role->name,self::PROTECTED_ROLES,true)||$role->users()->exists(),422,'No se puede eliminar un rol del sistema o asignado.');$role->delete();return back()->with('success','Rol eliminado');}
 public function savePermission(Request $r){$v=$r->validate(['id'=>'nullable|integer|exists:permissions,id','name'=>['required','string','max:100',Rule::unique('permissions','name')->where('guard_name','web')->ignore($r->input('id'))]]);$p=isset($v['id'])?Permission::where('guard_name','web')->findOrFail($v['id']):new Permission(['guard_name'=>'web']);if($p->exists && in_array($p->name,self::SYSTEM_PERMISSIONS,true) && $p->name!==$v['name'])abort(422,'No se puede renombrar un permiso del sistema.');$p->name=$v['name'];$p->save();app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();return back()->with('success','Permiso guardado');}
 public function assign(Request $r,User $user){$v=$r->validate(['roles'=>'nullable|array','roles.*'=>Rule::exists('roles','id')->where('guard_name','web')]);$roles=Role::where('guard_name','web')->whereIn('id',$v['roles']??[])->get();if($user->id===$r->user()->id && !$roles->contains('name','ADMINISTRADOR'))return back()->withErrors(['roles'=>'No puedes retirar tu propio rol de administrador.']);$user->syncRoles($roles);return back()->with('success','Roles actualizados');}
}
