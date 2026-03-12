<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->unsignedBigInteger('time_slot_id')->nullable()->change();
            if (!Schema::hasColumn('schedules', 'start_time')) {
                $table->time('start_time')->nullable()->after('time_slot_id');
            }

            if (!Schema::hasColumn('schedules', 'end_time')) {
                $table->time('end_time')->nullable()->after('start_time');
            }
        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->unsignedBigInteger('time_slot_id')->nullable(false)->change();
            $table->dropColumn(['start_time', 'end_time']);
        });
    }
};