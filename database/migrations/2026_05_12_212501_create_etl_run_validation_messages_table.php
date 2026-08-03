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
        Schema::create('etl_run_validation_messages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('etl_run_id')
                  ->constrained('etl_runs')
                  ->cascadeOnDelete();

            $table->string('severity')->default('info');
            $table->string('code')->nullable();

            $table->string('worksheet_name')->nullable();
            $table->unsignedInteger('row_number')->nullable();
            $table->string('column_name')->nullable();
            $table->string('cell_coordinate')->nullable();

            $table->string('title');
            $table->text('message')->nullable();

            $table->json('context')->nullable();

            $table->timestamps();

            $table->index(['etl_run_id', 'severity']);
            $table->index(['etl_run_id', 'worksheet_name']);
            $table->index(['etl_run_id', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etl_run_validation_messages');
    }
};
