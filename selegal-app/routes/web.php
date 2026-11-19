<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController as S;
use App\Http\Controllers\AccessController as Access;

Route::get('/', [S::class,'home'])->name('home');
Route::get('/servicios', [S::class,'services'])->name('services');
Route::get('/proceso', [S::class,'process'])->name('process');
Route::get('/equipo', [S::class,'team'])->name('team');
Route::get('/testimonios', [S::class,'testimonials'])->name('testimonials');
Route::get('/contacto', [S::class,'contact'])->name('contact');
Route::get('/solicitar', [S::class,'requestForm'])->name('request.form');
Route::post('/solicitar', [S::class,'requestStore'])->middleware('throttle:10,1')->name('request.store');
Route::get('/solicitud-enviada', [S::class,'requestSuccess'])->name('request.success');
Route::get('/agendar', [S::class,'schedule'])->name('schedule');
Route::post('/agendar', [S::class,'book'])->name('book');
Route::get('/cita-confirmada', [S::class,'booked'])->name('booked');
Route::get('/admin/login', [S::class,'login'])->name('login');
Route::post('/admin/login', [S::class,'authenticate'])->middleware('throttle:5,1');
Route::middleware('auth')->prefix('admin')->group(function () {
 Route::get('/accesos', [Access::class,'index'])->middleware('role:ADMINISTRADOR')->name('admin.access');
 Route::post('/accesos/roles', [Access::class,'saveRole'])->middleware('role:ADMINISTRADOR')->name('admin.access.roles');
 Route::post('/accesos/roles/{role}/eliminar', [Access::class,'deleteRole'])->middleware('role:ADMINISTRADOR')->name('admin.access.roles.delete');
 Route::post('/accesos/permisos', [Access::class,'savePermission'])->middleware('role:ADMINISTRADOR')->name('admin.access.permissions');
 Route::post('/accesos/usuarios/{user}', [Access::class,'assign'])->middleware('role:ADMINISTRADOR')->name('admin.access.assign');
 Route::post('/salir', [S::class,'logout'])->name('logout');
 Route::get('/', [S::class,'dashboard'])->name('admin.dashboard');
 Route::get('/solicitudes', [S::class,'requests'])->middleware('permission:solicitudes ver')->name('admin.requests');
 Route::post('/solicitudes/{id}/contactado', [S::class,'contacted'])->middleware('permission:solicitudes ver')->name('admin.contacted');
 Route::get('/citas', [S::class,'appointments'])->middleware('permission:citas gestionar')->name('admin.appointments');
 Route::post('/citas', [S::class,'adminBook'])->middleware('permission:citas gestionar')->name('admin.appointments.store');
 Route::post('/citas/{id}/estado', [S::class,'appointmentStatus'])->middleware('permission:citas gestionar')->name('admin.appointment.status');
 Route::get('/citas/{id}/ticket', [S::class,'ticket'])->middleware('permission:citas gestionar')->name('admin.ticket');
 Route::get('/equipo', [S::class,'adminTeam'])->middleware('permission:contenido gestionar')->name('admin.team');
 Route::post('/equipo', [S::class,'saveMember'])->middleware('permission:contenido gestionar')->name('admin.member.save');
 Route::post('/equipo/{id}/eliminar', [S::class,'deleteMember'])->middleware('permission:contenido gestionar')->name('admin.member.delete');
 Route::get('/testimonios', [S::class,'adminTestimonials'])->middleware('permission:contenido gestionar')->name('admin.testimonials');
 Route::post('/testimonios', [S::class,'saveTestimonial'])->middleware('permission:contenido gestionar')->name('admin.testimonial.save');
 Route::post('/testimonios/{id}/visibilidad', [S::class,'toggleTestimonial'])->middleware('permission:contenido gestionar')->name('admin.testimonial.toggle');
 Route::get('/contacto', [S::class,'adminContact'])->middleware('permission:contenido gestionar')->name('admin.contact');
 Route::post('/contacto', [S::class,'saveContact'])->middleware('permission:contenido gestionar')->name('admin.contact.save');
 Route::get('/horarios', [S::class,'availability'])->middleware('permission:horarios gestionar')->name('admin.availability');
 Route::post('/horarios', [S::class,'saveAvailability'])->middleware('permission:horarios gestionar')->name('admin.availability.save');
 Route::get('/usuarios', [S::class,'users'])->middleware('role:ADMINISTRADOR')->name('admin.users');
 Route::post('/usuarios', [S::class,'saveUser'])->middleware('role:ADMINISTRADOR')->name('admin.users.save');
});
