<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleToUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'dentist', 'patient'])->default('patient')->after('email');
            $table->string('specialty')->nullable()->after('role');
            $table->string('license_number')->nullable()->after('specialty');
            $table->text('biography')->nullable()->after('license_number');
            $table->string('profile_photo_path')->nullable()->after('biography');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
            $table->dropColumn('specialty');
            $table->dropColumn('license_number');
            $table->dropColumn('biography');
            $table->dropColumn('profile_photo_path');
        });
    }
}
