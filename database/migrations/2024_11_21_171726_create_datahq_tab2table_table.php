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
        Schema::create('datahq_tab2table', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('tab_id');
            $table->foreign('tab_id')
                  ->references('id')
                  ->on('datahq_tabs')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('table_id');
            $table->foreign('table_id')
                  ->references('id')
                  ->on('datahq_tables')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datahq_tabs2table');
    }
};
