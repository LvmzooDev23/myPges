<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('supervisor_id')->constrained('supervisors')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('score')->index();
            $table->json('criteria')->nullable();
            $table->text('comments')->nullable();
            $table->timestamps();

            $table->index(['supervisor_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
