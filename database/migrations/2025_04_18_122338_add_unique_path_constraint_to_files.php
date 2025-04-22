<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $driver = DB::connection()->getDriverName();

            if ($driver === 'sqlite') {
                // SQLite syntax for partial index
                DB::statement("
                CREATE UNIQUE INDEX files_project_path_unique ON files(project_id, path) 
                WHERE dataset_id IS NULL 
                        AND deleted_at IS NULL 
                        AND mime_type = 'directory'");
            } else {
                // MySQL workaround using generated column. This guarantees that project directories are unique. The code
                // has been changed so that if a create directory fails, then it queries for it again. This solves race
                // condition issues associated with querying and then creating the directory.
                DB::statement("
                ALTER TABLE files ADD COLUMN unique_proj_dir
                    BOOLEAN GENERATED ALWAYS AS
                        (CASE WHEN dataset_id IS NULL AND deleted_at IS NULL AND mime_type = 'directory' THEN 1 END) VIRTUAL");

                DB::statement("CREATE UNIQUE INDEX files_project_path_unique on files(project_id, path, unique_proj_dir)");
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
        });
    }
};
