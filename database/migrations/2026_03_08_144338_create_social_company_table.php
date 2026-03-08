<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('social.company')) {
            Schema::create('social.company', function (Blueprint $table) {
                $table->id();
                $table->text('name');
                $table->text('cnpj')->nullable();
                $table->text('email');
                $table->text('phone')->nullable();
                $table->timestamps();
                $table->unique(['cnpj']);
                $table->unique(['email']);
            });
        }
    }

    public function down(): void {
        Schema::dropIfExists('social.company');
    }
};
