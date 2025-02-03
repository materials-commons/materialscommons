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
        Schema::create('file_selections', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('path');

            $table->unsignedBigInteger('project_id');
            $table->foreign('project_id')
                  ->references('id')
                  ->on('projects')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('file_id')->nullable();
            $table->foreign('file_id')
                ->references('id')
                ->on('files')
                ->onDelete('cascade');

            $table->morphs('selectable');

            $table->datetime('included_at')->nullable();
            $table->datetime('excluded_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_selections');
    }
};
