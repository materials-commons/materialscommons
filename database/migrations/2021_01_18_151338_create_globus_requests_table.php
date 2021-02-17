<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGlobusRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('globus_requests', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            // Globus columns
            $table->string('globus_acl_id')->nullable();
            $table->string('globus_endpoint_id')->nullable();
            $table->string('globus_identity_id')->nullable();
            $table->string('globus_path')->nullable();
            $table->string('globus_url')->nullable();

            $table->string('state', 20)->nullable()->index();

            $table->datetime('last_active_at')->nullable();

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
        Schema::dropIfExists('globus_requests');
    }
}
