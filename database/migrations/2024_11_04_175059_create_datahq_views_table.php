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
        Schema::create('datahq_views', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('view_type');

            $table->foreignId('owner_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->foreignId('datahq_instance_id')
                  ->constrained('datahq_instances')
                  ->onDelete('cascade');

            $table->datetime('active_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datahq_views');
    }
};
