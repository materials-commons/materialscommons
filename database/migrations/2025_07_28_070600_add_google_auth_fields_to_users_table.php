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
        Schema::table('users', function (Blueprint $table) {
            $table->text('google_access_token')->nullable();
            $table->text('google_refresh_token')->nullable();
            $table->string('google_token_type')->default('Bearer')->nullable();
            $table->timestamp('google_expires_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'google_access_token',
                'google_refresh_token',
                'google_token_type',
                'google_expires_at',
                'google_spreadsheet_id',
            ]);
        });
    }
};
