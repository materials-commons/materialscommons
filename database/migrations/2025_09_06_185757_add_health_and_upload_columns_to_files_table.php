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
        Schema::table('files', function (Blueprint $table) {
            $table->string('upload_source')->nullable();
            $table->datetime('file_missing_at')->nullable();
            $table->string('file_missing_determined_by')->nullable();
            $table->string('health')->nullable();
            $table->datetime('last_health_check_at')->nullable();
            $table->datetime('health_fixed_at')->nullable();
            $table->string('health_fixed_by')->nullable();
            $table->datetime('thumbnail_created_at')->nullable();
            $table->string('thumbnail_status')->nullable();
            $table->datetime('conversion_created_at')->nullable();
            $table->string('conversion_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            //
        });
    }
};
