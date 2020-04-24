<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnDeleteCascadeForActivity2fileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activity2file', function (Blueprint $table) {
            $this->dropForeign($table, 'activity2file_activity_id_foreign');
            $table->foreign('activity_id')
                  ->references('id')
                  ->on('activities')
                  ->onDelete('cascade');

            $this->dropForeign($table, 'activity2file_file_id_foreign');
            $table->foreign('file_id')
                  ->references('id')
                  ->on('files')
                  ->onDelete('cascade');
        });
    }

    private function dropForeign(Blueprint $table, $name)
    {
        if (env('DB_CONNECTION') == 'sqlite') {
            return;
        }
        $table->dropForeign($name);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activity2file', function (Blueprint $table) {
            //
        });
    }
}
