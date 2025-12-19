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
        Schema::create('remote_client_transfers', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();

            $table->string('state', 20)->nullable();
            $table->string('transfer_type')->nullable();
            $table->unsignedBigInteger('expected_size')->nullable();
            $table->string('expected_checksum')->nullable();

            $table->unsignedBigInteger('remote_client_id');
            $table->foreign('remote_client_id')
                  ->references('id')
                  ->on('remote_clients')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('project_id');
            $table->foreign('project_id')
                  ->references('id')
                  ->on('projects')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('owner_id');
            $table->foreign('owner_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('file_id');
            $table->foreign('file_id')
                  ->references('id')
                  ->on('files')
                  ->onDelete('cascade');

            $table->datetime('last_active_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remote_client_transfers');
    }
};
