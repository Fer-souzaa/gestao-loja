<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('public.users', function (Blueprint $table) {
            $table->unsignedBigInteger('person_id')->nullable();
            $table->foreign('person_id', 'user_person_id_fk')->references('id')->on('social.person');
        });
    }

    public function down(): void {
        Schema::table('public.users', function (Blueprint $table) {
            $table->dropForeign('user_person_id_fk');
            $table->dropColumn('person_id');
        });
    }
};
