<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SELEGAL · Centro de Conciliación Extrajudicial</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,600&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}?v=3">
</head>
<body>
@php
    $isAdmin = str_starts_with($page,'admin-') || $page==='dashboard';
    $phone=$settings['phone']??'921778133';
    $sectionHeadingTag = $page === 'home' ? 'h2' : 'h1';
@endphp
@if($isAdmin)
<div class="admin-shell">
    <aside class="sidebar">
        <a class="brand" href="{{ route('admin.dashboard') }}">
            <img src="/assets/escudo.jpg" alt="Escudo SELEGAL" width="44" height="44">
            <span>SELEGAL<small>CONCILIACIÓN EXTRAJUDICIAL</small></span>
        </a>
        <div class="side-label">ADMINISTRACIÓN</div>
        <nav aria-label="Administración">
            <a href="{{ route('admin.dashboard') }}" aria-current="
            {{ request()->routeIs('admin.dashboard') ? 'page' : 'false' }}">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="m3 10 9-7 9 7M5.5 9v11h13V9M10 20v-6h4v6"/></svg>
                Inicio
            </a>
            @can('solicitudes ver')
                <a href="{{ route('admin.requests') }}" aria-current="
                {{ request()->routeIs('admin.requests') ? 'page' : 'false' }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="3.5"
                        width="14" height="17" rx="2"/><path d="M8.5 8h7m-7 4h7m-7 4H13"/></svg>
                    Solicitudes
                </a>
            @endcan
            @can('citas gestionar')
                <a href="{{ route('admin.appointments') }}" aria-current="{{ request()->routeIs
                ('admin.appointments') ? 'page' : 'false' }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3.5" y="5.5" width="17"
                        height="15" rx="2"/><path d="M7.5 3.5v4m9-4v4m-13 3h17M8 15h2m4 0h2"/></svg>
                    Citas
                </a>
            @endcan
            @can('contenido gestionar')
                <a href="{{ route('admin.team') }}" aria-current="{{ request()->routeIs('admin.team')
                ? 'page' : 'false' }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10" cy="8" r="3"/><path
                         d="M4 19v-1a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v1m1-13a3 3 0 0 1 0 5.5m2 3.5a4
                          4 0 0 1 2 3.5v.5"/></svg>
                    Equipo
                </a>
                <a href="{{ route('admin.contact') }}" aria-current="{{ request()->routeIs('admin.contact')
                ? 'page' : 'false' }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19 10c0 4.8-7 10-7 10s-7-5.2-7-10a7 7
                         0 1 1 14 0Z"/><circle cx="12" cy="10" r="2.4"/></svg>
                    Contacto
                </a>
                <a href="{{ route('admin.testimonials') }}" aria-current="{{ request()->routeIs
                ('admin.testimonials') ? 'page' : 'false' }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 4.5h14a2 2 0 0 1 2 2v8a2 2
                         0 0 1-2 2h-7l-5 3v-3H5a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2Z"/><path d="M7 9h10m-10 4h6"/>
                        </svg>
                    Testimonios
                </a>
            @endcan
            @can('horarios gestionar')
                <a href="{{ route('admin.availability') }}" aria-current="{{ request()->routeIs('admin.availability') ? 'page' : 'false' }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="8.5"/><path d="M12 7.5v5l3.3 2"/></svg>
                    Horarios
                </a>
            @endcan
            @role('ADMINISTRADOR')
                <a href="{{ route('admin.access') }}" aria-current="{{ request()->routeIs('admin.access', 'admin.users') ? 'page' : 'false' }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="5.5" y="10" width="13" height="10.5" rx="2"/><path d="M8.5 10V7a3.5 3.5 0 0 1 7 0v3m-3.5 4v2.5"/></svg>
                    Gestión de accesos
                </a>
            @endrole
        </nav>
        <form method="post" action="{{ route('logout') }}">
            @csrf
            <button class="logout">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 4H5v16h4m0-8h11m-4-4 4 4-4 4"/></svg>
                Cerrar sesión
            </button>
        </form>
    </aside>
    <main class="admin-main">
        <div class="admin-top">
            <span>Centro de Conciliación Extrajudicial</span>
            <span class="admin-account">
                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3"/><path d="M5 20v-1a4 4 0 0 1 4-4h6a4 4 0 0 1 4 4v1"/></svg>
                {{ auth()->user()->name }}
            </span>
        </div>
        <div class="admin-content">
@elseif($page!=='login')
<header class="site-header">
    <div class="header-inner">
        <a class="brand" href="{{ route('home') }}">
            <img src="/assets/escudo.jpg" alt="Escudo SELEGAL" width="48" height="48">
            <span>SELEGAL<small>CONCILIACIÓN EXTRAJUDICIAL</small></span>
        </a>
        <button class="menu-toggle" type="button" aria-label="Abrir menú" aria-expanded="false" aria-controls="public-nav">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" aria-hidden="true" width="24" height="24">
                <path class="menu-bars" d="M4 6h16M4 12h16M4 18h16"/>
                <path class="menu-cross" d="m6 6 12 12m0-12L6 18"/>
            </svg>
        </button>
        <nav class="public-nav" id="public-nav" aria-label="Navegación principal">
            <a href="{{ route('home') }}">Inicio</a>
            <a href="{{ route('home') }}#seccion-services">Servicios</a>
            <a href="{{ route('home') }}#seccion-process">Proceso</a>
            <a href="{{ route('home') }}#seccion-team">Nuestro equipo</a>
            <a href="{{ route('home') }}#seccion-testimonials">Testimonios</a>
            <a href="{{ route('home') }}#seccion-contact">Contacto</a>
            <a href="{{ route('login') }}">Iniciar sesión</a>
            <a class="btn btn-gold nav-cta" href="{{ route('request.form') }}">Solicitar conciliación <span aria-hidden="true">↗</span></a>
        </nav>
    </div>
</header>
<main class="site-main">
@endif
@if(session('success'))<div class="notice">{{ session('success') }}</div>@endif
@if($page==='home')
<section class="hero">
    <div class="hero-inner">
        <div class="hero-copy">
            <div class="eyebrow">DIÁLOGO · ACUERDO · TRANQUILIDAD</div>
            <h1>Un camino hacia<br><em>el acuerdo.</em></h1>
            <h2>Centro de Conciliación Extrajudicial SELEGAL</h2>
            <p>Resolvemos conflictos civiles y familiares de forma pacífica, sin ir a juicio.</p>
            <div class="actions">
                <a class="btn btn-gold" href="{{ route('request.form') }}">Solicitar conciliación <span aria-hidden="true">↗</span></a>
                <a class="btn btn-outline" href="{{ route('process') }}">Conoce el proceso <span aria-hidden="true">→</span></a>
            </div>
        </div>
    </div>
</section>
<div class="trust">
    <div class="trust-inner">
        <div class="trust-item">
            <span class="trust-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 2.5 15 5l3.8.2 1.2 3.6 2.2 3.2-2.2 3.2-1.2 3.6-3.8.2-3 2.5-3-2.5-3.8-.2L4 15.2 1.8 12 4 8.8l1.2-3.6L9 5Z"/><path d="m8.5 12 2.3 2.3 4.7-4.8"/></svg></span>
            <p><b>Autorizado por el Ministerio de Justicia</b><br>R.D. N.° 364-2026-JUS/DGDPAJ/DCMA</p>
        </div>
        <div class="trust-item">
            <span class="trust-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 9h18L12 3 3 9Zm2 11h14M3 22h18M7 12v5m5-5v5m5-5v5"/></svg></span>
            <p>Corporación de Abogados e Ingenieros por la Defensa de los Derechos Fundamentales</p>
        </div>
    </div>
</div>
<section class="service-overview">
    <div class="section">
        <div class="section-heading">
            <div>
                <div class="eyebrow">NUESTRAS ÁREAS DE ATENCIÓN</div>
                <h2>Una solución empieza<br><em>con el diálogo.</em></h2>
            </div>
            <p>Te acompañamos a encontrar acuerdos en un espacio de respeto y escucha.</p>
        </div>
        <div class="grid two">
            <article class="service-card">
                <div class="card-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 9h14v11H5zM9 9V6a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v3M5 13c4.5 2.2 9.5 2.2 14 0M12 13v3"/></svg></div>
                <h3>Materia civil</h3>
                <p>Encuentra una alternativa pacífica para resolver conflictos patrimoniales y civiles.</p>
                <a class="text-link" href="{{ route('services') }}#civil">Conocer más →</a>
            </article>
            <article class="service-card">
                <div class="card-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 11.5 12 4l9 7.5M5 10v10h14V10"/><path d="M12 17.5 8.8 14a1.8 1.8 0 0 1 2.6-2.5l.6.6.6-.6a1.8 1.8 0 0 1 2.6 2.5Z"/></svg></div>
                <h3>Materia familiar</h3>
                <p>Construyamos acuerdos que prioricen el bienestar y la tranquilidad de tu familia.</p>
                <a class="text-link" href="{{ route('services') }}#familiar">Conocer más →</a>
            </article>
        </div>
    </div>
</section>
@include('section-services')
@include('section-process')
@include('section-team')
@include('section-testimonials')
@include('section-contact')
<section class="cta-band">
    <div class="cta-inner">
        <div>
            <div class="eyebrow light">ESTAMOS PARA ESCUCHARTE</div>
            <h2>El primer paso es conversar.</h2>
            <p>Permítenos acompañarte en la búsqueda de un acuerdo.</p>
        </div>
        <a class="btn btn-gold" href="{{ route('request.form') }}">Solicitar conciliación ↗</a>
    </div>
</section>
@elseif($page==='services')
@include('section-services')
@elseif($page==='process')
@include('section-process')
@elseif($page==='team')
@include('section-team')
@elseif($page==='testimonials')
@include('section-testimonials')
@elseif($page==='contact')
@include('section-contact')
{{-- INICIO DEL FORMULARIO --}}
{{-- Formulario: envía los datos mediante POST con @csrf y muestra los errores de validación de Laravel. --}}
@elseif($page==='request')
<div class="request-background"><section class="page-intro compact"><div class="eyebrow">DAMOS EL PRIMER PASO</div><h1>Solicita tu <em>conciliación</em></h1><p>Completa tus datos y cuéntanos cómo podemos ayudarte.</p></section><section class="form-wrap"><form class="form-card" action="{{ route('request.store') }}" method="post" novalidate>@csrf<div class="form-grid">@foreach(['name'=>'Nombre completo','phone'=>'Teléfono / WhatsApp','email'=>'Correo electrónico'] as $key=>$label)<label>{{ $label }} <span>*</span><input name="{{ $key }}" value="{{ old($key) }}" type="{{ $key==='email'?'email':'text' }}" required>@error($key)<small class="error">{{ $message }}</small>@enderror</label>@endforeach<label>Tipo de caso <span>*</span><select name="matter" required><option value="">Selecciona una materia</option>@foreach(['Civil','Familiar'] as $m)<option @selected(old('matter')===$m)>{{ $m }}</option>@endforeach</select>@error('matter')<small class="error">{{ $message }}</small>@enderror</label><label class="full">Breve descripción <span>*</span><textarea name="description" rows="5" required>{{ old('description') }}</textarea>@error('description')<small class="error">{{ $message }}</small>@enderror</label></div><p class="muted">* Campos obligatorios</p><button class="btn btn-gold" type="submit">Enviar solicitud ↗</button></form></section></div>
{{-- FIN DEL FORMULARIO --}}
{{-- INICIO DE SOLICITUD ENVIADA --}}
{{-- Solicitud enviada: confirma el registro y ofrece el enlace a /agendar. --}}
@elseif($page==='success')
<section class="result"><div class="result-icon">✓</div><div class="eyebrow">HEMOS RECIBIDO TU SOLICITUD</div><h1>¡Solicitud <em>enviada!</em></h1><p>Gracias por confiar en SELEGAL. Puedes elegir una fecha y hora para tu cita.</p><a class="btn btn-gold" href="{{ route('schedule') }}">Agendar cita →</a></section>
{{-- FIN DE SOLICITUD ENVIADA --}}
{{-- INICIO DE LA AGENDA --}}
{{-- Agenda: app.js carga las horas del día y el formulario envía la selección a book(). --}}
@elseif($page==='schedule')
<section class="page-intro compact"><div class="eyebrow">ELIGE UN MOMENTO</div><h1>Agenda tu <em>cita</em></h1><p>Selecciona un día disponible y luego el horario que prefieras.</p></section><section class="form-wrap"><form class="form-card" action="{{ route('book') }}" method="post">@csrf @error('day')<p class="error">{{ $message }}</p>@enderror @if($slots->isEmpty())<p>No hay horarios disponibles por el momento. Comunícate con nosotros por WhatsApp.</p>@else<label>Día disponible<select name="day" id="day" required><option value="">Selecciona un día</option>@foreach($slots as $day=>$times)<option value="{{ $day }}">{{ \Carbon\Carbon::parse($day)->translatedFormat('d \d\e F \d\e Y') }}</option>@endforeach</select></label><label>Horario<select name="time" id="time" required disabled><option value="">Selecciona primero un día</option></select></label><button class="btn btn-gold" id="book-button" disabled>Confirmar cita →</button><script>window.availableSlots = @json($slots->map(fn($v)=>$v->pluck('time')->values()));</script>@endif</form></section>
{{-- FIN DE LA AGENDA --}}
{{-- INICIO DE LA CONFIRMACIÓN DE CITA --}}
{{-- Confirmación: muestra los datos de la cita consultada por booked(). --}}
@elseif($page==='booked')
<section class="result"><div class="result-icon">✓</div><div class="eyebrow">TE ESPERAMOS</div><h1>Cita <em>confirmada</em></h1><div class="summary"><p><span>Fecha</span><b>{{ \Carbon\Carbon::parse($a->day)->format('d/m/Y') }}</b></p><p><span>Hora</span><b>{{ $a->time }}</b></p><p><span>Materia</span><b>{{ $a->matter }}</b></p></div><p>Para reprogramar o cancelar, escríbenos por WhatsApp.</p><a class="btn btn-green" href="https://wa.me/51{{ preg_replace('/\D/','',$phone) }}">Escribir por WhatsApp ↗</a></section>
{{-- FIN DE LA CONFIRMACIÓN DE CITA --}}
@elseif($page==='login')
<main class="login-page">
    <a href="{{ route('home') }}" class="back-home">← Volver al sitio</a>
    <form class="login-card" method="post">
        @csrf
        <img src="/assets/escudo.jpg" alt="Escudo SELEGAL" width="56" height="56">
        <div class="eyebrow">CENTRO DE CONCILIACIÓN</div>
        <h1>Bienvenido a <em>SELEGAL</em></h1>
        <p>Ingresa al panel de administración</p>
        <label>
            Correo electrónico
            <input type="email" name="email" value="{{ old('email') }}" required>
            @error('email')<small class="error">{{ $message }}</small>@enderror
        </label>
        <label>
            Contraseña
            <input type="password" name="password" required>
            @error('password')<small class="error">{{ $message }}</small>@enderror
        </label>
        <button class="btn btn-gold">Ingresar →</button>
    </form>
</main>
@endif
@if($page==='dashboard' && !auth()->user()->can('solicitudes ver') && !auth()->user()->can('citas gestionar'))
<div class="admin-heading"><div><div class="eyebrow">SELEGAL</div><h1>Bienvenido al panel</h1><p>Tu cuenta está activa. Solicita al administrador los accesos que correspondan a tu función.</p></div></div>
@elseif($page==='dashboard')
<div class="admin-heading"><div><div class="eyebrow">RESUMEN GENERAL</div><h1>Bienvenido al panel</h1><p>Todo lo que necesitas para acompañar cada solicitud.</p></div><a class="btn btn-green" href="{{ route('admin.requests') }}">Ver solicitudes →</a></div><div class="grid two stats"><div class="stat"><span>Solicitudes nuevas</span><strong>{{ $newCount }}</strong><a href="{{ route('admin.requests') }}">Revisar solicitudes →</a></div><div class="stat"><span>Próximas citas</span><strong>{{ $appointmentCount }}</strong><a href="{{ route('admin.appointments') }}">Ver citas →</a></div></div><div class="panel"><div class="panel-title"><div><div class="eyebrow">ACTIVIDAD RECIENTE</div><h2>Últimas solicitudes</h2></div><a href="{{ route('admin.requests') }}">Ver todas →</a></div>@forelse($recent as $item)<div class="list-row"><div><b>{{ $item->name }}</b><small>{{ $item->matter }} · {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</small></div><span class="badge">{{ $item->status }}</span></div>@empty<p class="muted">Aún no hay solicitudes.</p>@endforelse</div><h2>Accesos rápidos</h2><div class="quick-links">@can('contenido gestionar')<a href="{{ route('admin.team') }}">♙ <b>Equipo</b><span>Administrar integrantes →</span></a><a href="{{ route('admin.contact') }}">◇ <b>Contacto</b><span>Editar información →</span></a>@endcan @can('horarios gestionar')<a href="{{ route('admin.availability') }}">◷ <b>Horarios</b><span>Definir disponibilidad →</span></a>@endcan</div>
@elseif($page==='admin-requests')
<div class="admin-heading">
    <div>
        <div class="eyebrow">GESTIÓN DE ATENCIÓN</div>
        <h1>Solicitudes recibidas</h1>
        <p>Revisa y da seguimiento a cada solicitud.</p>
    </div>
</div>
<div class="panel table-wrap" data-list>
    <label class="list-search">Buscar solicitudes<input type="search" data-list-search placeholder="Buscar por nombre, materia o estado"></label>
    <div class="admin-table-scroll" role="region" aria-label="Solicitudes recibidas" tabindex="0">
        <table>
            <thead><tr><th scope="col">Nombre</th><th scope="col">Tipo de caso</th><th scope="col">Fecha</th><th scope="col">Estado</th><th scope="col"></th></tr></thead>
            <tbody>
                @forelse($items as $i)
                    <tr data-list-row>
                        <td>{{ $i->name }}</td>
                        <td>{{ $i->matter }}</td>
                        <td>{{ \Carbon\Carbon::parse($i->created_at)->format('d/m/Y') }}</td>
                        <td><span class="badge">{{ $i->status }}</span></td>
                        <td><a href="?ver={{ $i->id }}">Ver detalle →</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5">Aún no hay solicitudes.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="list-pagination" data-pagination hidden>
        <button type="button" data-prev>← Anterior</button>
        <span data-page-info></span>
        <button type="button" data-next>Siguiente →</button>
    </div>
</div>
@if($detail)
    <div class="overlay">
        <div class="modal">
            <a class="close" href="{{ route('admin.requests') }}" aria-label="Cerrar detalle">×</a>
            <div class="eyebrow">SOLICITUD #{{ $detail->id }}</div>
            <h2>Detalle de solicitud</h2>
            <p><b>Nombre:</b> {{ $detail->name }}</p>
            <p><b>Teléfono:</b> {{ $detail->phone }}</p>
            <p><b>Correo:</b> {{ $detail->email }}</p>
            <p><b>Materia:</b> {{ $detail->matter }}</p>
            <p><b>Descripción:</b> {{ $detail->description }}</p>
            <form method="post" action="{{ route('admin.contacted',$detail->id) }}">
                @csrf
                <button class="btn btn-green">Marcar como contactado</button>
            </form>
        </div>
    </div>
@endif
@elseif($page==='admin-appointments')
<div class="admin-heading">
    <div>
        <div class="eyebrow">GESTIÓN DE ATENCIÓN</div>
        <h1>Citas</h1>
        <p>Consulta las citas y prepara su ticket.</p>
    </div>
</div>
<div class="panel table-wrap" data-list>
    <label class="list-search">Buscar citas<input type="search" data-list-search placeholder="Buscar por nombre, materia o fecha"></label>
    <div class="admin-table-scroll" role="region" aria-label="Citas" tabindex="0">
        <table>
            <thead><tr><th scope="col">Nombre</th><th scope="col">Materia</th><th scope="col">Fecha y hora</th><th scope="col">Estado</th><th scope="col">Acciones</th></tr></thead>
            <tbody>
                @forelse($items as $i)
                    <tr data-list-row>
                        <td>{{ $i->name }}</td>
                        <td>{{ $i->matter }}</td>
                        <td>{{ \Carbon\Carbon::parse($i->day)->format('d/m/Y') }} · {{ $i->time }}</td>
                        <td><span class="badge">{{ $i->status }}</span></td>
                        <td>
                            <a target="_blank" href="{{ route('admin.ticket',$i->id) }}">Imprimir ticket ↗</a>
                            <details class="appointment-actions">
                                <summary>Gestionar</summary>
                                <form method="post" action="{{ route('admin.appointment.status',$i->id) }}">
                                    @csrf
                                    <select name="status">
                                        @foreach(['Pendiente','Confirmada','Reprogramada','Cancelada','Finalizada'] as $s)
                                            <option @selected($i->status===$s)>{{ $s }}</option>
                                        @endforeach
                                    </select>
                                    <select name="new_slot">
                                        <option value="">Nuevo horario (para reprogramar)</option>
                                        @foreach($slots as $slot)
                                            <option value="{{ $slot->day }}|{{ $slot->time }}">{{ $slot->day }} · {{ $slot->time }}</option>
                                        @endforeach
                                    </select>
                                    <button class="link-button">Guardar</button>
                                </form>
                            </details>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">Aún no hay citas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="list-pagination" data-pagination hidden>
        <button type="button" data-prev>← Anterior</button>
        <span data-page-info></span>
        <button type="button" data-next>Siguiente →</button>
    </div>
</div>
<section class="booking-panel" id="registrar-cita"><div class="booking-heading"><div><div class="eyebrow">
    NUEVA RESERVA</div><h2>Registrar cita</h2><p>Elige una solicitud pendiente o registra los datos del solicitante.</p></div><span class="booking-icon">▦</span></div>
@if($errors->any())<div class="booking-alert" role="alert"><b>Revisa los datos de la cita</b><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
@if($slots->isEmpty())<div class="booking-empty"><b>Aún no hay horarios disponibles</b><p>Configura los días y las horas de atención para habilitar las reservas.</p><a href="{{ route('admin.availability') }}" class="text-link">Configurar horarios →</a></div>@endif
<form method="post" action="{{ route('admin.appointments.store') }}" id="admin-booking">@csrf
<label>Origen de la solicitud<select name="mode" id="booking-mode"><option value="new" @selected(old('mode','new')==='new')>Crear una solicitud nueva</option><option value="existing" @selected(old('mode')==='existing')>Elegir una solicitud existente</option></select></label>
<div id="new-request" @if(old('mode','new')!=='new') hidden @endif><div class="booking-grid"><label>Nombre completo<input name="name" value="{{ old('name') }}" maxlength="150" required></label><label>Teléfono / WhatsApp<input name="phone" value="{{ old('phone') }}" maxlength="20" required></label><label>Correo electrónico<input type="email" name="email" value="{{ old('email') }}" required></label><label>Materia<select name="matter" required><option value="">Selecciona una materia</option>@foreach(['Civil','Familiar'] as $matter)<option value="{{ $matter }}" @selected(old('matter')===$matter)>{{ $matter }}</option>@endforeach</select></label><label class="full">Breve descripción<textarea name="description" rows="3" required>{{ old('description') }}</textarea></label></div></div>
<div id="existing-request" @if(old('mode','new')!=='existing') hidden @endif><label>Solicitud pendiente<select name="request_id" required><option value="">Selecciona una solicitud</option>@foreach($requests as $requestItem)<option value="{{ $requestItem->id }}" @selected(old('request_id')==$requestItem->id)>#{{ $requestItem->id }} · {{ $requestItem->name }} · {{ $requestItem->matter }}</option>@endforeach</select></label>@if($requests->isEmpty())<p class="booking-note">No hay solicitudes pendientes de programación. Puedes crear una solicitud nueva.</p>@endif</div>
<div class="booking-divider"><span>FECHA Y HORA</span></div><div class="booking-grid"><label>Fecha disponible<select name="day" id="day" required><option value="">Selecciona un día</option>@foreach($slots->groupBy('day') as $day=>$times)<option value="{{ $day }}" @selected(old('day')===$day)>{{ \Carbon\Carbon::parse($day)->format('d/m/Y') }}</option>@endforeach</select></label><label>Horario<select name="time" id="time" required data-selected="{{ old('time') }}" disabled><option value="">Selecciona primero un día</option></select></label></div><button class="btn btn-gold" id="book-button" @disabled($slots->isEmpty())>Registrar cita →</button><script>window.availableSlots = @json($slots->groupBy('day')->map(fn($v)=>$v->pluck('time')->values()));</script></form></section>
@elseif($page==='admin-team' || $page==='admin-testimonials')
@php $team=$page==='admin-team'; @endphp<div class="admin-heading"><div><div class="eyebrow">CONTENIDO DEL SITIO</div><h1>{{ $team?'Nuestro equipo':'Testimonios' }}</h1><p>Administra el contenido que aparece en la página pública.</p></div><a class="btn btn-gold" href="#editor">+ Agregar {{ $team?'integrante':'testimonio' }}</a></div><div class="panel">@forelse($items as $i)<div class="list-row"><div>@if($i->example)<small class="example">Contenido de ejemplo</small>@endif<b>{{ $i->name }}</b><small>{{ $team?$i->role:$i->body }}</small>@if(!$team && !$i->visible)<span class="badge">Oculto</span>@endif</div><div class="row-actions"><a href="?editar={{ $i->id }}#editor">Editar</a><form method="post" action="{{ $team?route('admin.member.delete',$i->id):route('admin.testimonial.toggle',$i->id) }}" @if($team) onsubmit="return confirm('¿Eliminar a este integrante?')" @endif>@csrf<button class="link-button">{{ $team?'Eliminar':($i->visible?'Ocultar':'') }}</button></form></div></div>@empty<p class="muted">No hay elementos registrados.</p>@endforelse</div><div class="panel editor" id="editor"><div class="eyebrow">{{ $edit?'EDITAR':'AGREGAR' }}</div><h2>{{ $team?'Integrante':'Testimonio' }}</h2><form method="post" enctype="multipart/form-data" action="{{ $team?route('admin.member.save'):route('admin.testimonial.save') }}">@csrf<input type="hidden" name="id" value="{{ $edit->id??'' }}"><label>Nombre<input name="name" value="{{ old('name',$edit->name??'') }}" required></label>@if($team)<label>Cargo<select name="role" required>@foreach(['ABOGADO','CONCILIADOR','DIRECTOR'] as $role)<option value="{{ $role }}" @selected(old('role',$edit->role??'')===$role)>{{ ucfirst(strtolower($role)) }}</option>@endforeach</select></label><label>Descripción<textarea name="description" rows="4" maxlength="2000">{{ old('description',$edit->description??'') }}</textarea></label><label>Foto<input type="file" name="photo" accept="image/*"></label>@else<label>Testimonio<textarea name="body" required>{{ old('body',$edit->body??'') }}</textarea></label>@endif @if($errors->any())<p class="error">Revisa los campos ingresados.</p>@endif<button class="btn btn-gold">Guardar</button> <a class="text-link" href="{{ $team?route('admin.team'):route('admin.testimonials') }}">Cancelar</a></form></div>
@elseif($page==='admin-contact')
<div class="admin-heading"><div><div class="eyebrow">INFORMACIÓN PÚBLICA</div><h1>Contacto </h1><p>Actualiza los datos de contacto del centro.</p></div></div><form class="panel editor" method="post">@csrf<label>Teléfono<input name="phone" value="{{ old('phone',$settings['phone']??'921778133') }}" required></label><label>Correo electrónico<input type="email" name="email" value="{{ old('email',$settings['email']??'jhanpool799@gmail.com') }}" required></label><label>Dirección<input name="address" value="{{ old('address',$settings['address']??'') }}"></label><label class="switch-line"><input type="checkbox" name="show_address" value="1" @checked(($settings['show_address']??'0')==='1')>  dirección en la web: Sí / No</label><button class="btn btn-gold">Guardar cambios</button></form>
@elseif($page==='admin-availability')Mostrar
@php $weekday=(int)request('dia',now()->dayOfWeekIso); if($weekday<1 || $weekday>7)
    $weekday=1; $weekdays=[1=>'Lunes',2=>'Martes',3=>'Miércoles',4=>'Jueves',5=>'Viernes',
    6=>'Sábado',7=>'Domingo']; $selectedTimes=$weekly->where('dia_semana',$weekday)->map(fn($h)=>substr
    ($h->hora_inicio,0,5))->all(); @endphp
<div class="admin-heading"><div><div class="eyebrow">AGENDA · CONFIGURACIÓN</div><h1>Horarios
    disponibles</h1><p>Establece la atención que se repite cada semana.</p></div><a class="btn
     btn-green" href="{{ route('admin.appointments') }}#registrar-cita">Volver a citas →</a></div>
<section class="booking-panel"><div class="booking-heading"><div><h2>Horario semanal</h2><p>
    Elige un día y marca las horas disponibles para ese día cada semana.</p></div><span
    class="booking-icon">◷</span></div><nav class="weekday-nav" aria-label="Días de atención">
        @foreach($weekdays as $num=>$label)<a href="{{ route('admin.availability',['dia'=>$num]) }}"
            @if($weekday===$num) aria-current="page" @endif>{{ $label }}</a>@endforeach</nav>
<form method="post">@csrf<input type="hidden" name="weekday" value="{{ $weekday }}">

<div class="booking-divider"><span>{{ mb_strtoupper($weekdays[$weekday]) }} · RANGO DE ATENCIÓN</span></div>@php $dayHours=$weekly->where('dia_semana',$weekday); $start=old('start',$dayHours->isEmpty()?'00:00':substr($dayHours->min('hora_inicio'),0,5)); $end=old('end',$dayHours->isEmpty()?'00:00':substr($dayHours->max('hora_fin'),0,5)); @endphp @if($errors->any())<p class="error">{{ $errors->first() }}</p>@endif<div class="booking-grid"><label>Desde<input type="time" name="start" value="{{ $start }}" required></label><label>Hasta<input type="time" name="end" value="{{ $end }}" required></label></div><p class="booking-note">La atención se repite cada {{ mb_strtolower($weekdays[$weekday]) }} y las citas duran 60 minutos. Para cerrar ese día, indica 00:00 en ambos campos. Las citas existentes se conservan.</p><button class="btn btn-gold">Guardar horario →</button></form></section><div class="panel upcoming-slots"><h2>Próximos horarios disponibles</h2><p class="muted">Horarios sin reservar de los próximos 60 días.</p>@forelse($slots->groupBy('day')->take(10) as $day=>$times)<div class="list-row"><b>{{ \Carbon\Carbon::parse($day)->format('d/m/Y') }}</b><span>{{ $times->pluck('time')->join(' · ') }}</span></div>@empty<div class="booking-empty"><b>Tu agenda aún no tiene horarios disponibles</b><p>Selecciona un día y guarda al menos una hora para comenzar.</p></div>@endforelse</div>
@elseif($page==='admin-access')

@include('access')
@elseif($page==='admin-users')
<div class="admin-heading"><div><div class="eyebrow">ACCESO AL PANEL</div>
<h1>Usuarios</h1><p>Agrega personas y asigna su función en el centro.</p></div></div><div class="panel">@foreach($items as $i)<div class="list-row"><b>{{ $i->name }}</b><span>{{ $i->email }}</span></div>@endforeach</div><form class="panel editor" method="post">@csrf<h2>Agregar usuario</h2><label>Nombre<input name="name" required></label><label>Correo<input type="email" name="email" required></label><label>Rol<select name="access_role" required><option value="">Selecciona un rol</option><option value="ADMINISTRADOR">Administrador</option><option value="CONCILIADOR">Conciliador</option><option value="RECEPCIONISTA">Recepcionista</option></select></label><label>Contraseña (mínimo 12 caracteres)<input type="password" name="password" minlength="12" required></label><label>Confirmar contraseña<input type="password" name="password_confirmation" minlength="12" required></label>@if($errors->any())<p class="error">{{ $errors->first() }}</p>@endif<button class="btn btn-gold">Agregar usuario</button></form>
@endif
@if($isAdmin)</div></main></div>@elseif($page!=='login')
</main>
<footer class="site-footer">
    <div class="footer-inner">
        <div>
            <a class="brand footer-brand" href="{{ route('home') }}">
                <img src="/assets/escudo.jpg" alt="Escudo SELEGAL" width="48" height="48">
                <span>SELEGAL<small>CONCILIACIÓN EXTRAJUDICIAL</small></span>
            </a>
            <p>Un espacio para el diálogo,<br>un camino hacia el acuerdo.</p>
            <a class="btn btn-gold mobile-request-cta" href="{{ route('request.form') }}">
                Solicitar conciliación <span aria-hidden="true">↗</span></a>
        </div>
        <div class="footer-links">
            <h2>EXPLORA</h2>
            <a href="{{ route('services') }}">Servicios</a>
            <a href="{{ route('process') }}">Proceso</a>
            <a href="{{ route('team') }}">Nuestro equipo</a>
        </div>
        <div class="footer-links">
            <h2>CONTÁCTANOS</h2>
            <a href="https://wa.me/51{{ preg_replace('/\D/','',$phone) }}">{{ $phone }}</a>
            <a href="mailto:{{ $settings['email']??'jhanpool799@gmail.com' }}">{{ $settings
            ['email']??'jhanpool799@gmail.com' }}</a>
            <p class="footer-social">Facebook · Instagram · TikTok</p>
            <a href="{{ route('login') }}">Acceso administrativo</a>
        </div>
    </div>
    <div class="footer-bottom">R.D. N.° 364-2026-JUS/DGDPAJ/DCMA <span>© {{ date('Y') }} SELEGAL</span></div>
</footer>
<a class="whatsapp" href="https://wa.me/51{{ preg_replace('/\D/','',$phone) }}" aria-label="Contactar por WhatsApp" title="Contactar por WhatsApp">
    <svg viewBox="0 0 32 32" aria-hidden="true" focusable="false"><path fill="currentColor" d="M16 3a13 13 0 0 0-11.1 19.8L3 29l6.4-1.7A13 13 0 1 0 16 3Zm0 23.6a10.6 10.6 0 0 1-5.4-1.5l-.4-.2-3.8 1 1-3.7-.3-.4a10.6 10.6 0 1 1 8.9 4.8Zm5.8-7.9c-.3-.2-1.9-.9-2.2-1-.3-.1-.5-.2-.7.1-.2.3-.8 1-1 1.2-.2.2-.4.2-.7.1-.3-.2-1.3-.5-2.4-1.5-.9-.8-1.5-1.7-1.7-2-.2-.3 0-.5.1-.6l.5-.6.3-.5c.1-.2.1-.4 0-.6l-.9-2.2c-.2-.6-.5-.5-.7-.5h-.6c-.2 0-.6.1-.9.4s-1.2 1.1-1.2 2.7c0 1.6 1.2 3.1 1.3 3.3.2.2 2.3 3.5 5.6 4.9.8.3 1.4.5 1.9.6.8.3 1.6.2 2.1.1.7-.1 1.9-.8 2.2-1.5.3-.7.3-1.3.2-1.5-.1-.2-.3-.3-.6-.4Z"/></svg>
</a>
@endif
<script src="/assets/app.js?v=2" defer></script>
</body>
</html>
