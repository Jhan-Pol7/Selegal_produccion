<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable {
 use Notifiable, \Spatie\Permission\Traits\HasRoles;
 protected $table='usuarios';
 protected $fillable=['nombre','usuario','email','password','rol','activo'];
 protected $hidden=['password'];
 public function getNameAttribute(){return $this->nombre;}
 public function getRememberTokenName(){return '';}
 protected function casts(): array{return ['password'=>'hashed','activo'=>'boolean'];}
}
