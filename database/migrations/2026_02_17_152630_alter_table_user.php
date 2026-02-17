<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('public.users', function (Blueprint $table) {
            $table->dropColumn('email');
            $table->dropColumn('remember_token');
            $table->dropColumn('email_verified_at');
            $table->dropColumn('name');
            $table->text('password')->change();
            $table->text('username')->unique();
        });
    }

};
