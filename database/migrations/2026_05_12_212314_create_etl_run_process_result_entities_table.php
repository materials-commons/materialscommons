<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('etl_run_process_result_entities', function (Blueprint $table) {
            $table->id();

            $table->foreignId('etl_run_process_result_id')
                  ->constrained('etl_run_process_results')
                  ->cascadeOnDelete();

            $table->foreignId('entity_id')
                  ->nullable()
                  ->constrained('entities')
                  ->nullOnDelete();

            $table->string('entity_name');
            $table->unsignedInteger('row_number')->nullable();

            $table->string('role')->default('output');
            $table->string('status')->default('created');

            $table->timestamps();

            $table->index(['etl_run_process_result_id', 'entity_name']);
            $table->index(['entity_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etl_run_process_result_entities');
    }
};
