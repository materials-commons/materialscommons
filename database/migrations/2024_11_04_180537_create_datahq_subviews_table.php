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
        Schema::create('datahq_subviews', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->integer('eindex');

            $table->foreignId('owner_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->foreignId('datahq_tab_id')
                  ->constrained('datahq_tabs')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datahq_sub_views');
    }
};
