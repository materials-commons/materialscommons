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
        create view entity_attrs_by_dataset as
           select e.name as entity_name, e.dataset_id, act.name as activity_name, a.name as attribute_name, json_extract(av.val, '$.value') as attribute_value, av.unit as attribute_unit
           from entities e
               join entity_states es on es.entity_id = e.id
               join activity2entity_state a2es on a2es.entity_state_id = es.id
               join activities as act on a2es.activity_id = act.id
               join attributes a on a.attributable_id = es.id and attributable_type = 'App\\\Models\\\EntityState'
               join attribute_values av on av.attribute_id = a.id
           where e.dataset_id is not null
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("drop view if exists entity_attrs_by_dataset");
    }
};
