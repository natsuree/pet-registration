<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->string('owner_name', 120)->nullable()->after('microchip');
            $table->string('owner_email', 120)->nullable()->after('owner_name');
            $table->string('owner_number', 40)->nullable()->after('owner_email');
        });
    }

    public function down(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->dropColumn(['owner_name', 'owner_email', 'owner_number']);
        });
    }
};
