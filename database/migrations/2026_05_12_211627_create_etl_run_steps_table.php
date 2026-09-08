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
        Schema::create('etl_run_steps', function (Blueprint $table) {
            $table->id();

            $table->foreignId('etl_run_id')
                  ->constrained('etl_runs')
                  ->cascadeOnDelete();

            $table->string('key');
            $table->string('label');
            $table->string('status')->default('waiting');
            $table->text('message')->nullable();

            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();

            $table->timestamps();

            $table->unique(['etl_run_id', 'key']);
            $table->index(['etl_run_id', 'sort_order']);
            $table->index(['etl_run_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etl_run_steps');
    }
};
