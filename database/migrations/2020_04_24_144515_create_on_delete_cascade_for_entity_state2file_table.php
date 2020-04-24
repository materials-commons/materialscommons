<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnDeleteCascadeForEntityState2fileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entity_state2file', function (Blueprint $table) {
            $this->dropForeign($table, 'entity_state2file_entity_state_id_foreign');
            $table->foreign('entity_state_id')
                  ->references('id')
                  ->on('entity_states')
                  ->onDelete('cascade');

            $this->dropForeign($table, 'entity_state2file_file_id_foreign');
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
        Schema::table('entity_state2file', function (Blueprint $table) {
            //
        });
    }
}
