<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
        create view entity_attrs_by_proj_exp as
           select e.name as entity_name, e.project_id, e2e.experiment_id, act.name as activity_name,
                  a.name as attribute_name, json_extract(av.val, '$.value') as attribute_value, 
                  av.unit as attribute_unit
           from entities e
                  join entity_states es on es.entity_id = e.id
                  join experiment2entity e2e on e2e.entity_id = e.id
                  join activity2entity_state a2es on a2es.entity_state_id = es.id
                  join activities as act on a2es.activity_id = act.id
                  left join attributes a on a.attributable_id = es.id and attributable_type = 'App\\\Models\\\EntityState'
                  left join attribute_values av on av.attribute_id = a.id
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("drop view if exists entity_attrs_by_proj_exp");
    }
};
