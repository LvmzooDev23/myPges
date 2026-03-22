<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('student_number')->nullable()->unique();
            $table->string('phone', 64)->nullable()->index();
            $table->text('bio')->nullable();
            $table->string('cv_path')->nullable();
            $table->foreignId('supervisor_id')->nullable()->constrained('supervisors')->nullOnDelete();
            $table->timestamps();

            $table->index('supervisor_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
