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
        Schema::create('globus_endpoint_user_paths', function (Blueprint $table) {
            $table->id();
            $table->uuid()->index();
            $table->string('globus_path');
            $table->string('globus_acl_id')->nullable();

            $table->unsignedBigInteger('globus_endpoint_user_id');
            $table->foreign('globus_endpoint_user_id')
                  ->references('id')
                  ->on('globus_endpoint_users')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('globus_endpoint_user_paths');
    }
};
