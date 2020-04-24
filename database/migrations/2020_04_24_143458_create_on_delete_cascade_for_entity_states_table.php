<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnDeleteCascadeForEntityStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entity_states', function (Blueprint $table) {
            $this->dropForeign($table, 'entity_states_entity_id_foreign');
            $table->foreign('entity_id')
                  ->references('id')
                  ->on('entities')
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
        Schema::table('entity_states', function (Blueprint $table) {
            //
        });
    }
}
