<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGlobusTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('globus_transfers', function (Blueprint $table) {
            $table->id();

            $table->uuid('uuid')->unique();

            $table->string('state', 20)->nullable()->index();

            // Globus columns
            $table->string('globus_acl_id')->nullable();
            $table->string('globus_endpoint_id')->nullable();
            $table->string('globus_identity_id')->nullable();
            $table->string('globus_path')->nullable();
            $table->string('globus_url')->nullable();
            $table->string('last_globus_transfer_id_completed')->nullable();

            // Globus string for date
            $table->string('latest_globus_transfer_completed_date')->nullable();

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

            $table->unsignedBigInteger('transfer_request_id');
            $table->foreign('transfer_request_id')
                  ->references('id')
                  ->on('transfer_requests')
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
        Schema::dropIfExists('globus_transfers');
    }
}
