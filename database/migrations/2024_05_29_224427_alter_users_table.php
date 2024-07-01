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
        Schema::table('users',function(Blueprint $table) {
            // $table->string('phone')->nullable()->after('email');
            $table->string('google_id')->unique()->after('phone');
            $table->rememberToken()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users',function(Blueprint $table) {
            // $table->removeColumn('phone');
            $table->dropColumn('google_id');
            $table->dropColumn('remember_token');
        });
    }
};
