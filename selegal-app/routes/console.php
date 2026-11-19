<?php
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
Artisan::command('selegal:admin', function () {
 $name=$this->ask('Nombre del administrador');$email=$this->ask('Correo electrónico');$password=$this->secret('Contraseña (mínimo 12 caracteres)');
 if(!$name || !filter_var($email,FILTER_VALIDATE_EMAIL) || strlen($password??'')<12 || User::where('email',$email)->exists()){$this->error('Datos inválidos o correo ya registrado.');return 1;}
 DB::transaction(function()use($name,$email,$password){$user=User::create(['nombre'=>$name,'usuario'=>'admin_'.bin2hex(random_bytes(8)),'email'=>$email,'password'=>$password,'rol'=>'ADMINISTRADOR']);$user->assignRole('ADMINISTRADOR');});$this->info('Administrador creado.');
})->purpose('Crear un administrador de SELEGAL');

Artisan::command('selegal:instalar-db', function () {
 $config=config('database.connections.mysql');
 $dsn=sprintf('mysql:host=%s;port=%s;charset=utf8mb4',$config['host'],$config['port']);
 $pdo=new PDO($dsn,$config['username'],$config['password'],[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
 $sql=file_get_contents(database_path('selegal_db.sql'));
 foreach(explode(';',$sql) as $statement){$statement=trim(preg_replace('/^\s*--.*$/m','',$statement));if($statement!=='' && !preg_match('/^(SELECT|SHOW)\b/i',$statement))$pdo->exec($statement);}
 $this->info('Esquema SELEGAL instalado.');
})->purpose('Instalar el esquema MySQL proporcionado');

Artisan::command('selegal:casos-ejemplo', function () {
 $prefix='Contenido de ejemplo · Caso '; $center=DB::table('centros')->where('nombre_comercial','SELEGAL')->first();
 if(!$center) throw new RuntimeException('Centro SELEGAL no encontrado.');
 if(DB::table('solicitantes')->where('nombre_completo','like',$prefix.'%')->exists()) { $this->error('Ya existen casos de ejemplo; no se duplicaron.'); return 1; }
 $civil=DB::table('materias')->where('nombre','Civil')->value('id');$familiar=DB::table('materias')->where('nombre','Familiar')->value('id');
 if(!$civil || !$familiar) throw new RuntimeException('Faltan las materias iniciales.');
 $today=\Carbon\Carbon::now('America/Lima')->startOfDay();$begin=$today->copy()->subMonth();$days=(int)$begin->diffInDays($today)+1;
 $count=DB::transaction(function()use($prefix,$center,$civil,$familiar,$today,$begin,$days){$created=0;
  for($round=0;$round<5 && $created<70;$round++) for($n=0;$n<$days && $created<70;$n++){
   $date=$today->copy()->subDays($n);$time=['09:00:00','10:00:00','11:00:00','15:00:00','16:00:00'][$round];
   if(DB::table('citas')->where('centro_id',$center->id)->where('fecha',$date->toDateString())->where('hora_inicio',$time)->where('estado','!=','CANCELADA')->exists())continue;
   $end=\Carbon\Carbon::parse($time)->addHour()->format('H:i:s');
   $hour=DB::table('horarios_atencion')->where('centro_id',$center->id)->where('dia_semana',$date->dayOfWeekIso)->where('hora_inicio',$time)->where('hora_fin',$end)->value('id');
   if(!$hour)$hour=DB::table('horarios_atencion')->insertGetId(['centro_id'=>$center->id,'dia_semana'=>$date->dayOfWeekIso,'hora_inicio'=>$time,'hora_fin'=>$end,'duracion_minutos'=>60,'activo'=>false]);
   $created++;$stamp=$date->copy()->setTime(8,0);$p=DB::table('solicitantes')->insertGetId(['nombre_completo'=>$prefix.str_pad($created,2,'0',STR_PAD_LEFT),'telefono'=>'000000000','email'=>'ejemplo'.str_pad($created,2,'0',STR_PAD_LEFT).'@example.invalid','created_at'=>$stamp,'updated_at'=>$stamp]);
   $s=DB::table('solicitudes')->insertGetId(['centro_id'=>$center->id,'solicitante_id'=>$p,'materia_id'=>$created%2?$civil:$familiar,'descripcion'=>'Contenido de ejemplo: solicitud ficticia de conciliación.','estado'=>$date->isSameDay($today)?'PENDIENTE':'ATENDIDA','fecha_solicitud'=>$stamp]);
   DB::table('citas')->insert(['solicitud_id'=>$s,'centro_id'=>$center->id,'horario_atencion_id'=>$hour,'fecha'=>$date->toDateString(),'hora_inicio'=>$time,'hora_fin'=>$end,'estado'=>$date->isSameDay($today)?'CONFIRMADA':'FINALIZADA','observacion'=>'Contenido de ejemplo','fecha_registro'=>$stamp]);
  }if($created!==70)throw new RuntimeException('No hay suficientes horarios libres para 70 ejemplos.');return $created;
 });$this->info("Se crearon {$count} casos de ejemplo, del último mes hasta hoy.");
})->purpose('Crear 70 casos ficticios identificados, sin modificar casos existentes');
