<?php

use App\Enums\GenderEnum;
use App\Enums\EmploymentTypeEnum;
use App\Enums\EmploymentStatusEnum;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('work_email')->unique()->nullable();
            $table->string('phone')->unique()->nullable();
            $table->string('password');
            $table->enum('gender', GenderEnum::toArray())->nullable();
            $table->date('date_of_birth')->nullable();
            $table->date('hire_date');
            $table->string('job_title');
            $table->foreignId('departments_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('branches_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('status', EmploymentStatusEnum::toArray())->default(EmploymentStatusEnum::ACTIVE);
            $table->enum('employement_type', EmploymentTypeEnum::toArray())->nullable();
            $table->decimal('salary', 10, 2)->nullable();
            $table->foreignId('country_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('governorate_id')->nullable()->constrained()->nullOnDelete();
            $table->text('address')->nullable();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_admin')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
