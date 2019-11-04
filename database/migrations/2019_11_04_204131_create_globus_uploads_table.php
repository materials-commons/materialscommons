<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGlobusUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('globus_uploads', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();

            // Globus columns
            $table->string('globus_acl_id');
            $table->string('globus_endpoint_id');
            $table->string('globus_identity_id');

            $table->string('path', 700);

            // True if the request is already being processed
            $table->boolean('loading')->nullable();

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
