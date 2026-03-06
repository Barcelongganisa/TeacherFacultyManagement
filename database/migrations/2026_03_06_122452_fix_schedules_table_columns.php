<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            if (!Schema::hasColumn('schedules', 'teacher_id')) {
                $table->unsignedBigInteger('teacher_id')->after('id');
            }

            if (!Schema::hasColumn('schedules', 'subject_id')) {
                $table->unsignedBigInteger('subject_id')->after('teacher_id');
            }

            if (!Schema::hasColumn('schedules', 'classroom_id')) {
                $table->unsignedBigInteger('classroom_id')->after('subject_id');
            }

            if (!Schema::hasColumn('schedules', 'time_slot_id')) {
                $table->unsignedBigInteger('time_slot_id')->after('classroom_id');
            }

            if (!Schema::hasColumn('schedules', 'day_of_week')) {
                $table->enum('day_of_week', ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'])->after('time_slot_id');
            }

            if (!Schema::hasColumn('schedules', 'status')) {
                $table->enum('status', ['active', 'inactive'])->default('active')->after('day_of_week');
            }
        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $columns = ['teacher_id', 'subject_id', 'classroom_id', 'time_slot_id', 'day_of_week', 'status'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('schedules', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};