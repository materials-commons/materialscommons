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
        Schema::create('datahq_tab2chart', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('tab_id');
            $table->foreign('tab_id')
                  ->references('id')
                  ->on('datahq_tabs')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('chart_id');
            $table->foreign('chart_id')
                  ->references('id')
                  ->on('datahq_charts')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datahq_tab2chart');
    }
};
