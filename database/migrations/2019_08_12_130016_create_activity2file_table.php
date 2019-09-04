<?php

use App\Enums\RelationshipDirection;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivity2fileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity2file', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('activity_id');
            $table->foreign('activity_id')
                  ->references('id')
                  ->on('activities');

            $table->unsignedBigInteger('file_id');
            $table->foreign('file_id')
                  ->references('id')
                  ->on('files');

            $table->tinyInteger('direction')
                  ->unsigned()
                  ->default(RelationshipDirection::In);

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
        Schema::dropIfExists('activity2file');
    }
}
