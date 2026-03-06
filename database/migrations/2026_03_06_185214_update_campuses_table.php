<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('campuses', function (Blueprint $table) {
            $table->renameColumn('name', 'campus_name'); // rename existing column
            $table->string('campus_code', 50)->unique()->after('campus_name');
            $table->string('address')->after('campus_code');
            $table->string('contact_email')->nullable()->after('address');
            $table->string('contact_phone')->nullable()->after('contact_email');
            $table->text('description')->nullable()->after('contact_phone');
        });
    }

    public function down()
    {
        Schema::table('campuses', function (Blueprint $table) {
            $table->renameColumn('campus_name', 'name');
            $table->dropColumn(['campus_code', 'address', 'contact_email', 'contact_phone', 'description']);
        });
    }
};
