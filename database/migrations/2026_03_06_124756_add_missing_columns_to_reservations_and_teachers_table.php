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
        $table->foreignId('classroom_id')->nullable()->after('teacher_id');
        $table->string('purpose')->nullable()->after('room');
        $table->text('notes')->nullable()->after('purpose');
    });

    Schema::table('teachers', function (Blueprint $table) {
        $table->string('phone')->nullable()->after('email');
        $table->string('profile_image')->nullable()->after('phone');
        $table->string('employee_id')->nullable()->after('id');
        $table->string('specialization')->nullable()->after('department');
    });
}

public function down(): void
{
    Schema::table('reservations', function (Blueprint $table) {
        $table->dropColumn(['classroom_id', 'purpose', 'notes']);
    });

    Schema::table('teachers', function (Blueprint $table) {
        $table->dropColumn(['phone', 'profile_image', 'employee_id', 'specialization']);
    });
}
};
