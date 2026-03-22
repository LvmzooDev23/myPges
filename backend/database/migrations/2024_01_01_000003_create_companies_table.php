<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('legal_name')->nullable();
            $table->string('siret', 32)->nullable()->index();
            $table->text('description')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable()->index();
            $table->string('country', 2)->nullable()->index();
            $table->string('logo_path')->nullable();
            $table->string('approval_status', 32)->default('pending')->index();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
