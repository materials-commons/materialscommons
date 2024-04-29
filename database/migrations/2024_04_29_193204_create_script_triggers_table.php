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
        Schema::create('script_triggers', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();

            $table->string('name');
            $table->string('description')->nullable();

            $table->string('what');
            $table->string('when');
            $table->foreignId('script_id')
                  ->constrained('scripts')
                  ->onDelete('cascade');

            $table->foreignId('project_id')
                  ->constrained('projects')
                  ->onDelete('cascade');

            $table->foreignId('owner_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->foreignId('script_id')
                  ->constrained('scripts')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('script_triggers');
    }
};
