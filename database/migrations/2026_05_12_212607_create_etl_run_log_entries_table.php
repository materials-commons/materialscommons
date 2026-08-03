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
        Schema::create('etl_run_log_entries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('etl_run_id')
                  ->constrained('etl_runs')
                  ->cascadeOnDelete();

            $table->string('level')->default('info');
            $table->text('message');
            $table->json('context')->nullable();

            $table->timestamps();

            $table->index(['etl_run_id', 'created_at']);
            $table->index(['etl_run_id', 'level']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etl_run_log_entries');
    }
};
