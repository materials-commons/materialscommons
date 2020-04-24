<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnDeleteCascadeForActivity2entityStateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activity2entity_state', function (Blueprint $table) {
            $this->dropForeign($table, 'activity2entity_state_activity_id_foreign');
            $table->foreign('activity_id')
                  ->references('id')
                  ->on('activities')
                  ->onDelete('cascade');

            $this->dropForeign($table, 'activity2entity_state_entity_state_id_foreign');
            $table->foreign('entity_state_id')
                  ->references('id')
                  ->on('entity_states')
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
        Schema::table('activity2entity_state', function (Blueprint $table) {
            //
        });
    }
}
