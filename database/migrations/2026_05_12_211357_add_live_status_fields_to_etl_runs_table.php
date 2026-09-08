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
        Schema::table('etl_runs', function (Blueprint $table) {
            $table->string('status')->default('queued')->after('uuid');
            $table->unsignedTinyInteger('progress_percent')->default(0)->after('status');
            $table->string('current_step')->nullable()->after('progress_percent');
            $table->text('current_message')->nullable()->after('current_step');

            $table->string('source_type')->nullable()->after('current_message');
            $table->string('source_name')->nullable()->after('source_type');
            $table->text('source_uri')->nullable()->after('source_name');

            $table->timestamp('started_at')->nullable()->after('source_uri');
            $table->timestamp('finished_at')->nullable()->after('started_at');
            $table->timestamp('cancel_requested_at')->nullable()->after('finished_at');

            $table->json('summary')->nullable()->after('cancel_requested_at');
            $table->text('error_message')->nullable()->after('summary');

            $table->index(['status', 'created_at']);
            $table->index(['etlable_type', 'etlable_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('etl_runs', function (Blueprint $table) {
            //
        });
    }
};
