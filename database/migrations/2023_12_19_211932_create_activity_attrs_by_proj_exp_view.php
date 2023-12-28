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
        create view activity_attrs_by_proj_exp as
             select act.name as activity_name, e.name as entity_name, act.project_id, e2a.experiment_id, a.name as attribute_name, json_extract(av.val, '$.value') as attribute_value, av.unit as attribute_unit
             from activities act
                join experiment2activity e2a on act.id = e2a.activity_id
                join activity2entity a2e on act.id = a2e.activity_id
                join entities e on a2e.entity_id = e.id
                left join attributes a on a.attributable_id = act.id and attributable_type = 'App\\\Models\\\Activity'
                left join attribute_values av on av.attribute_id = a.id
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("drop view if exists activity_attrs_by_proj_exp");
    }
};
