<?php

use App\Enums\FileType;
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
            $table->increments('id');
            $table->uuid('uuid');
            $table->string('name');
            $table->text('description')->default("");
            $table->text('path')->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->string('checksum')->default("");
            $table->boolean('current')->default(true);
            $table->string('mime_type');
            $table->string('media_type_description')->default("unknown");

            $table->unsignedInteger('project_id');
            $table->foreign('project_id')
                ->references('id')
                ->on('projects')
                ->onDelete('cascade');

            $table->uuid('uses_uuid')->nullable();
//            $table->foreign('uses_uuid')
//                ->references('uuid')
//                ->on('files')
//                ->onDelete('cascade');

            $table->unsignedBigInteger('uses_id')->nullable();
//            $table->foreign('uses_id')
//                ->references('id')
//                ->on('files')
//                ->onDelete('cascade');

            $table->tinyInteger('file_type')->unsigned()->default(FileType::File);
            $table->timestamps();
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
