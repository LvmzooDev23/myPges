<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('user_id');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('university')->nullable()->after('student_number');
            $table->string('degree')->nullable()->after('university');
            $table->text('skills')->nullable()->after('degree');
            $table->string('linkedin_url')->nullable()->after('skills');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->string('industry')->nullable()->after('name');
        });

        Schema::table('internships', function (Blueprint $table) {
            $table->string('duration')->nullable()->after('location');
            $table->decimal('stipend', 12, 2)->nullable()->after('duration');
            $table->text('required_skills')->nullable()->after('requirements');
            $table->date('deadline')->nullable()->after('required_skills');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'university', 'degree', 'skills', 'linkedin_url']);
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('industry');
        });

        Schema::table('internships', function (Blueprint $table) {
            $table->dropColumn(['duration', 'stipend', 'required_skills', 'deadline']);
        });
    }
};
