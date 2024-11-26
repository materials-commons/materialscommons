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
        Schema::create('datahq_charts', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->string('name');
            $table->string('description')->nullable();
            $table->string('attribute1')->nullable();
            $table->string('attribute1_type')->nullable();
            $table->string('attribute2')->nullable();
            $table->string('attribute2_type')->nullable();
            $table->string('chart_type')->nullable();
            $table->string('xaxis_name')->nullable();
            $table->string('yaxis_name')->nullable();
            $table->datetime('shared_at')->nullable();

            $table->foreignId('owner_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->foreignId('datahq_subview_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charts');
    }
};
