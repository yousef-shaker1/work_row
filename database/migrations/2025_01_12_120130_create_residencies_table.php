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
        Schema::create('residencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('civil_id_number')->nullable();
            $table->date('civil_id_expiration')->nullable();
            $table->string('passport_number')->nullable();
            $table->date('passport_expiration')->nullable();
            $table->string('iqama_number')->nullable();
            $table->date('iqama_expiration')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residencies');
    }
};
