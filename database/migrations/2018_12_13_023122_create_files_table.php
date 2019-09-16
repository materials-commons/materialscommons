<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();

            $table->string('name');

            $table->text('path')->nullable()->index();

            $table->text('description')->default("");
            $table->unsignedBigInteger('size')->default(0);
            $table->string('checksum')->default("");
            $table->boolean('current')->default(true);
            $table->string('mime_type');
            $table->string('media_type_description')->default("unknown");

            $table->string('disk')->nullable()->default('local');

            $table->unsignedBigInteger('owner_id');
            $table->foreign('owner_id')
                  ->references('id')
                  ->on('users');

            $table->unsignedBigInteger('project_id');
            $table->foreign('project_id')
                  ->references('id')
                  ->on('projects')
                  ->onDelete('cascade');

            $table->boolean('is_shortcut')->default('false');

            $table->unsignedBigInteger('directory_id')->nullable();
            $table->foreign('directory_id')
                  ->references('id')
                  ->on('files');

            $table->uuid('uses_uuid')->nullable();
            $table->foreign('uses_uuid')
                  ->references('uuid')
                  ->on('files');

            $table->unsignedBigInteger('uses_id')->nullable();
            $table->foreign('uses_id')
                  ->references('id')
                  ->on('files');

            $table->timestamps();

            $table->index(['project_id', 'directory_id']);
            $table->index(['project_id', 'path']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
