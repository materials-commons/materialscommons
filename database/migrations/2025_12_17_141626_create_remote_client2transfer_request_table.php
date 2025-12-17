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
        Schema::create('remote_client2transfer_request', function (Blueprint $table) {
            $table->unsignedBigInteger('remote_client_id');
            $table->foreign('remote_client_id')
                  ->references('id')
                  ->on('remote_clients')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('transfer_request_id');
            $table->foreign('transfer_request_id')
                  ->references('id')
                  ->on('transfer_requests')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remote_client2transfer_request');
    }
};
