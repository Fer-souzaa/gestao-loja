<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('social.person', function (Blueprint $table) {
            $table->dropColumn('registration_date');
        });
    }
};
