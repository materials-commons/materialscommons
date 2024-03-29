<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('experiments', function (Blueprint $table) {
            $table->string('loaded_file_path')->nullable();

            $table->unsignedBigInteger('sheet_id')->nullable();
            $table->unsignedBigInteger('job_id')->nullable();

            $table->datetime('loading_started_at')->nullable();
            $table->datetime('loading_finished_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('experiments', function (Blueprint $table) {
            //
        });
    }
};
