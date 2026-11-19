<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('requests', function (Blueprint $t) { $t->id(); $t->string('name'); $t->string('phone'); $t->string('email'); $t->string('matter'); $t->text('description'); $t->string('status')->default('Pendiente'); $t->timestamps(); });
        Schema::create('appointments', function (Blueprint $t) { $t->id(); $t->foreignId('request_id')->constrained('requests'); $t->date('day'); $t->string('time'); $t->string('status')->default('Confirmada'); $t->timestamps(); $t->unique(['day','time']); });
        Schema::create('members', function (Blueprint $t) { $t->id(); $t->string('name'); $t->string('role'); $t->string('photo')->nullable(); $t->boolean('example')->default(false); $t->timestamps(); });
        Schema::create('testimonials', function (Blueprint $t) { $t->id(); $t->string('name'); $t->text('body'); $t->boolean('visible')->default(true); $t->boolean('example')->default(false); $t->timestamps(); });
        Schema::create('settings', function (Blueprint $t) { $t->string('key')->primary(); $t->text('value')->nullable(); });
        Schema::create('availability', function (Blueprint $t) { $t->id(); $t->date('day'); $t->string('time'); $t->unique(['day','time']); });
    }
    public function down(): void { foreach (['availability','settings','testimonials','members','appointments','requests'] as $table) Schema::dropIfExists($table); }
};

