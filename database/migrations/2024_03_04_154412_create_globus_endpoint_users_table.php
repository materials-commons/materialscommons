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
        Schema::create('globus_endpoint_users', function (Blueprint $table) {
            $table->id();
            $table->uuid()->index();
            $table->string('globus_identity_id');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('globus_endpoint_id');
            $table->foreign('globus_endpoint_id')
                  ->references('id')
                  ->on('globus_endpoints')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('globus_endpoint_users');
    }
};
