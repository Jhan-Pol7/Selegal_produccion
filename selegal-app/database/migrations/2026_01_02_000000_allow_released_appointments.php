<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::table('appointments', function(Blueprint $t) { $t->dropUnique(['day','time']); }); }
 public function down(): void { Schema::table('appointments', function(Blueprint $t) { $t->unique(['day','time']); }); }
};
