<div class="admin-heading">
    <div>
        <div class="eyebrow">ADMINISTRACIÓN</div>
        <h1>Gestión de accesos</h1>
        <p>Administra usuarios, roles y permisos del panel.</p>
    </div>
    <a class="btn btn-gold" href="{{ route('admin.users') }}">Agregar usuario</a>
</div>

@if($errors->any())
    <div class="notice">{{ $errors->first() }}</div>
@endif

<section class="panel access-panel" data-user-search-panel>
    <h2>Usuarios y roles</h2>
    <p class="muted">Cada usuario puede tener uno o más roles. Los permisos se suman.</p>
    <label class="list-search">
        Buscar usuarios
        <input type="search" data-user-search placeholder="Buscar por nombre, correo o rol" autocomplete="off">
    </label>
    <p class="muted" data-user-no-results hidden>No se encontraron usuarios.</p>
    @foreach($users as $user)
        <div class="list-row access-user" data-user-row data-search="{{ $user->nombre }} {{ $user->email }} {{ $user->roles->pluck('name')->join(' ') }}">
            <div>
                <b>{{ $user->nombre }}</b>
                <small>{{ $user->email }}</small>
                <small>Permisos efectivos: {{ $user->getAllPermissions()->pluck('name')->join(', ') ?: 'Ninguno' }}</small>
            </div>
            <form method="post" action="{{ route('admin.access.assign',$user) }}">
                @csrf
                <div class="access-checks access-role-checks">
                    @foreach($roles as $role)
                        <label><input type="checkbox" name="roles[]" value="{{ $role->id }}" @checked($user->hasRole($role))> {{ $role->name }}</label>
                    @endforeach
                </div>
                <button class="btn btn-green">Guardar roles</button>
            </form>
        </div>
    @endforeach
</section>

<section class="panel access-panel">
    <h2>Roles y permisos</h2>
    <p class="muted">Los roles del sistema conservan su nombre; asigna solo los permisos necesarios.</p>
    @foreach($roles as $role)
        <details class="access-item">
            <summary><b>{{ $role->name }}</b><span> · {{ $role->permissions->count() }} permisos</span></summary>
            <form method="post" action="{{ route('admin.access.roles') }}">
                @csrf
                <input type="hidden" name="id" value="{{ $role->id }}">
                <label>Nombre del rol<input name="name" value="{{ $role->name }}" required></label>
                <div class="access-checks">
                    @foreach($permissions as $permission)
                        <label><input type="checkbox" name="permissions[]" value="{{ $permission->id }}" @checked($role->hasPermissionTo($permission))> {{ $permission->name }}</label>
                    @endforeach
                </div>
                <button class="btn btn-gold">Guardar cambios</button>
            </form>
            @if(!in_array($role->name,['ADMINISTRADOR','CONCILIADOR','RECEPCIONISTA']))
                <form method="post" action="{{ route('admin.access.roles.delete',$role) }}" onsubmit="return confirm('¿Eliminar este rol?')">
                    @csrf
                    <button class="link-button access-delete">Eliminar rol</button>
                </form>
            @endif
        </details>
    @endforeach
    <details class="access-item access-add">
        <summary>+ Crear rol</summary>
        <form method="post" action="{{ route('admin.access.roles') }}">
            @csrf
            <label>Nombre<input name="name" required maxlength="100"></label>
            <div class="access-checks">
                @foreach($permissions as $permission)
                    <label><input type="checkbox" name="permissions[]" value="{{ $permission->id }}"> {{ $permission->name }}</label>
                @endforeach
            </div>
            <button class="btn btn-gold">Crear rol</button>
        </form>
    </details>
</section>

<section class="panel access-panel">
    <h2>Permisos</h2>
    <p class="muted">Los permisos del sistema controlan las secciones existentes.</p>
    @foreach($permissions as $permission)
        <details class="access-item">
            <summary>{{ $permission->name }}</summary>
            <form method="post" action="{{ route('admin.access.permissions') }}">
                @csrf
                <input type="hidden" name="id" value="{{ $permission->id }}">
                <label>Nombre<input name="name" value="{{ $permission->name }}" required></label>
                <button class="btn btn-green">Guardar permiso</button>
            </form>
        </details>
    @endforeach
    <details class="access-item access-add">
        <summary>+ Crear permiso</summary>
        <form method="post" action="{{ route('admin.access.permissions') }}">
            @csrf
            <label>Nombre<input name="name" required maxlength="100"></label>
            <button class="btn btn-gold">Crear permiso</button>
        </form>
    </details>
</section>
