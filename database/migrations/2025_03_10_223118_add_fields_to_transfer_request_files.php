<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transfer_request_files', function (Blueprint $table) {
            $table->bigInteger('expected_size')->nullable();
            $table->string('expected_checksum')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transfer_request_files', function (Blueprint $table) {
            //
        });
    }
};
