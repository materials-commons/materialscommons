<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGlobusUploadsDownloadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('globus_uploads_downloads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();

            $table->string('name');
            $table->string('description')->nullable();
            $table->string('type')->nullable();

            // Globus columns
            $table->string('globus_acl_id')->nullable();
            $table->string('globus_endpoint_id')->nullable();
            $table->string('globus_identity_id')->nullable();
            $table->string('globus_path')->nullable();
            $table->string('globus_url')->nullable();

            $table->string('path', 700)->nullable();

            // True if the request is already being processed
            $table->boolean('loading')->nullable();

            // Initially true - status information so a user can check if the files
            // are being uploaded, or being processed.
            $table->boolean('uploading')->nullable();

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

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('globus_uploads');
    }
}