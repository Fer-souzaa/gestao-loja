<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        DB::statement("CREATE SCHEMA IF NOT EXISTS social;");
        Schema::create('social.person', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('cpf')->unique()->nullable();
            $table->text('phone')->nullable();
            $table->text('email')->unique()->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('social.person');
    }
};
