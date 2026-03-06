<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            // Add day_of_week if missing
            if (!Schema::hasColumn('schedules', 'day_of_week')) {
                $table->enum('day_of_week', [
                    'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'
                ])->after('teacher_id');
            }

            // Add status if missing
            if (!Schema::hasColumn('schedules', 'status')) {
                $table->enum('status', ['active', 'inactive'])->default('active')->after('day_of_week');
            }
        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            if (Schema::hasColumn('schedules', 'day_of_week')) {
                $table->dropColumn('day_of_week');
            }
            if (Schema::hasColumn('schedules', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};