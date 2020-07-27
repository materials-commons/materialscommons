<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\QueryException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DropForeignKeyUsesIdAddIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('files', function (Blueprint $table) {
            $this->dropForeign($table, 'files_uses_id_foreign');
            $table->index(['uses_id']);
        });
    }

    private function dropForeign(Blueprint $table, $name)
    {
        if (env('DB_CONNECTION') == 'sqlite') {
            return;
        }

        try {
            DB::statement("ALTER TABLE files DROP FOREIGN KEY {$name}");
        } catch (QueryException $e) {
            $msg = $e->getMessage();
            echo "Caught exception for alter table: {$msg}\n";
        }

        try {
            DB::statement("DROP INDEX {$name} ON files");
        } catch (QueryException $e) {
            $msg = $e->getMessage();
            echo "Caught exception for drop index: {$msg}\n";
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('files', function (Blueprint $table) {
            //
        });
    }
}
