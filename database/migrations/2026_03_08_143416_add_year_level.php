<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->enum('year_level', ['1st', '2nd', '3rd', '4th'])
                  ->default('1st')
                  ->after('course_id');

            $table->enum('semester', ['1st Semester', '2nd Semester', 'Summer'])
                  ->default('1st Semester')
                  ->after('year_level');
        });
    }

    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn('year_level');
            $table->dropColumn('semester');
        });
    }
};