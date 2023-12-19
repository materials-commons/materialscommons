<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
        create view activity_attrs_by_dataset as
             select act.name as activity_name, act.dataset_id, a.name as attribute_name, json_extract(av.val, '$.value') as attribute_value, av.unit as attribute_unit
             from activities act
                join attributes a on a.attributable_id = act.id and attributable_type = 'App\\\Models\\\Activity'
                join attribute_values av on av.attribute_id = a.id
             where act.dataset_id is not null
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("drop view if exists activity_attrs_by_dataset");
    }
};
