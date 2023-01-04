<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOverviewFieldsToDatasets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('datasets', function (Blueprint $table) {
            $table->unsignedBigInteger('file1_id')->nullable();
            $table->foreign('file1_id')
                  ->references('id')
                  ->on('files')
                  ->cascadeOnUpdate()
                  ->nullOnDelete();

            $table->unsignedBigInteger('file2_id')->nullable();
            $table->foreign('file2_id')
                  ->references('id')
                  ->on('files')
                  ->cascadeOnUpdate()
                  ->nullOnDelete();

            $table->unsignedBigInteger('file3_id')->nullable();
            $table->foreign('file3_id')
                  ->references('id')
                  ->on('files')
                  ->cascadeOnUpdate()
                  ->nullOnDelete();

            $table->unsignedBigInteger('file4_id')->nullable();
            $table->foreign('file4_id')
                  ->references('id')
                  ->on('files')
                  ->cascadeOnUpdate()
                  ->nullOnDelete();

            $table->unsignedBigInteger('file5_id')->nullable();
            $table->foreign('file5_id')
                  ->references('id')
                  ->on('files')
                  ->cascadeOnUpdate()
                  ->nullOnDelete();

            $table->string("app_url")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('datasets', function (Blueprint $table) {
            //
        });
    }
}
