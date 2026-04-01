<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Only add columns that don't exist
            if (!Schema::hasColumn('students', 'university')) {
                $table->string('university')->nullable();
            }
            if (!Schema::hasColumn('students', 'degree')) {
                $table->string('degree')->nullable();
            }
            if (!Schema::hasColumn('students', 'skills')) {
                $table->text('skills')->nullable();
            }
            if (!Schema::hasColumn('students', 'linkedin_url')) {
                $table->string('linkedin_url')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'university',
                'degree', 
                'skills',
                'linkedin_url'
            ]);
        });
    }
};
