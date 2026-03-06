<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::table('reservations', function (Blueprint $table) {
        $table->foreignId('time_slot_id')->nullable()->after('classroom_id');
        $table->date('reservation_date')->nullable()->after('time_slot_id');
    });

    Schema::create('availability', function (Blueprint $table) {
        $table->id();
        $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
        $table->enum('day_of_week', ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday']);
        $table->time('start_time');
        $table->time('end_time');
        $table->string('status')->default('available');
        $table->text('notes')->nullable();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::table('reservations', function (Blueprint $table) {
        $table->dropColumn(['time_slot_id', 'reservation_date']);
    });

    Schema::dropIfExists('availability');
}
};
