<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferRequestFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_request_files', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name', 150);
            $table->string('state', 20)->nullable();

            $table->unsignedBigInteger("transfer_request_id");
            $table->foreign("transfer_request_id")
                  ->references("id")
                  ->on("transfer_requests")
                  ->onDelete('cascade');

            $table->unsignedBigInteger('directory_id')->index();
            $table->foreign('directory_id')
                  ->references('id')
                  ->on('files')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('file_id');
            $table->foreign('file_id')
                  ->references('id')
                  ->on('files')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('project_id')->index();
            $table->foreign('project_id')
                  ->references('id')
                  ->on('projects')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('owner_id');
            $table->foreign('owner_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->timestamps();

            $table->index(['directory_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transfer_request_files');
    }
}
