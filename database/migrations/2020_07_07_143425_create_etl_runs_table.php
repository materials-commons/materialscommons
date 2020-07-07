<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEtlRunsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('etl_runs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->unsignedInteger('n_activities')->nullable();
            $table->unsignedInteger('n_activity_attributes')->nullable();
            $table->unsignedInteger('n_activity_attribute_values')->nullable();

            $table->unsignedInteger('n_entities')->nullable();
            $table->unsignedInteger('n_entity_attributes')->nullable();
            $table->unsignedInteger('n_entity_attribute_values')->nullable();

            $table->unsignedInteger('n_sheets')->nullable();
            $table->unsignedBigInteger('n_sheets_processed')->nullable();

            $table->unsignedInteger('n_files')->nullable();
            $table->unsignedInteger('n_files_not_found')->nullable();
            $table->text('files_not_found')->nullable();

            $table->unsignedInteger('n_columns')->nullable();
            $table->unsignedInteger('n_columns_skipped')->nullable();
            $table->text('columns_skipped')->nullable();

            $table->unsignedBigInteger('owner_id');
            $table->foreign('owner_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->nullableMorphs('etlable');
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
        Schema::dropIfExists('etl_runs');
    }
}
