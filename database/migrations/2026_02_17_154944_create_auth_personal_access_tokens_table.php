<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        DB::statement("CREATE SCHEMA IF NOT EXISTS auth;");

        Schema::create('auth.personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->text('tokenable_type');
            $table->unsignedBigInteger('tokenable_id');
            $table->text('token');
            $table->timestamp('created_at');
            $table->boolean('is_revoked')->default(false);
        });
    }

    public function down(): void {
        Schema::dropIfExists('auth.personal_access_tokens');
    }
};
