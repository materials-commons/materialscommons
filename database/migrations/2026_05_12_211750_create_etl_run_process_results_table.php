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
        Schema::create('etl_run_process_results', function (Blueprint $table) {
            $table->id();

            $table->foreignId('etl_run_id')
                  ->constrained('etl_runs')
                  ->cascadeOnDelete();

            $table->string('worksheet_name');
            $table->string('process_name')->nullable();
            $table->string('process_type')->nullable();
            $table->string('category')->nullable();

            $table->unsignedInteger('sample_count')->default(0);
            $table->unsignedInteger('input_count')->default(0);
            $table->unsignedInteger('output_count')->default(0);
            $table->unsignedInteger('activity_count')->default(0);
            $table->unsignedInteger('attribute_count')->default(0);
            $table->unsignedInteger('measurement_count')->default(0);
            $table->unsignedInteger('file_count')->default(0);
            $table->unsignedInteger('warning_count')->default(0);
            $table->unsignedInteger('error_count')->default(0);

            $table->string('status')->default('created');
            $table->text('message')->nullable();

            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();

            $table->timestamps();

            $table->index(['etl_run_id', 'worksheet_name']);
            $table->index(['etl_run_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etl_process_results');
    }
};
