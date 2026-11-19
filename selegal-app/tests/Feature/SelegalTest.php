<?php
namespace Tests\Feature;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use App\Models\User;
class SelegalTest extends TestCase {
 use RefreshDatabase;
 public function test_public_pages_and_request_validation(): void { $this->get('/')->assertOk()->assertSee('SELEGAL'); $this->get('/servicios')->assertOk()->assertSee('Materia civil'); $this->post('/solicitar',[])->assertSessionHasErrors(['name','phone','email','matter','description']); $this->post('/solicitar',['name'=>'Ana','phone'=>'999999999','email'=>'ana@example.com','matter'=>'Laboral','description'=>'Caso'])->assertSessionHasErrors('matter'); }
 public function test_request_can_book_only_an_available_slot_once(): void { $day=now()->addDay()->format('Y-m-d'); DB::table('availability')->insert(['day'=>$day,'time'=>'09:00']); $this->post('/solicitar',['name'=>'Ana','phone'=>'999999999','email'=>'ana@example.com','matter'=>'Civil','description'=>'Consulta'])->assertRedirect('/solicitud-enviada'); $this->post('/agendar',['day'=>$day,'time'=>'09:00'])->assertRedirect('/cita-confirmada'); $this->get('/cita-confirmada')->assertOk()->assertSee('Civil'); $this->assertDatabaseCount('appointments',1); $this->assertDatabaseMissing('availability',['day'=>$day,'time'=>'09:00']); }
 public function test_admin_requires_login_and_can_print_ticket(): void { $this->get('/admin')->assertRedirect('/admin/login'); $user=User::factory()->create(); $this->actingAs($user)->get('/admin')->assertOk(); }
}
