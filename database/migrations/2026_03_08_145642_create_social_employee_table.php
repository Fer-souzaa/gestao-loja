<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('social.employee', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('person_id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('employee_function_id');
            $table->date('start_at');
            $table->date('ends_at')->nullable();
            $table->timestamps();
            $table->foreign('person_id', 'employee_person_id_fk')->references('id')->on('social.person');
            $table->foreign('company_id', 'employee_company_id_fk')->references('id')->on('social.company');
            $table->foreign('employee_function_id', 'employee_function_id_fk')->references('id')->on('social.employee_function');
        });
    }

    public function down(): void {
        Schema::dropIfExists('social.employee');
    }
};
