<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTeamToProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->unsignedBigInteger('team_id')->nullable();
            $table->foreign('team_id')
                  ->references('id')
                  ->on('teams')
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
        Schema::table('projects', function (Blueprint $table) {
            $this->dropForeign($table, 'projects_team_id_foreign');
            $table->dropColumn('team_id');
        });
    }
}
