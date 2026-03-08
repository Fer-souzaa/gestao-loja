<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('social.customer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('social.person');
            $table->foreignId('company_id')->constrained('social.company');
            $table->date('billing_date')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social.customer');
    }
};
