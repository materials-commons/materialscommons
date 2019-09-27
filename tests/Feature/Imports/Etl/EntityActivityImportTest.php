<?php

namespace Tests\Feature\Imports\Etl;

use App\Imports\Etl\EntityActivityImporter;
use App\Models\Experiment;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class EntityActivityImportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_spreadsheet_d1_headers()
    {
        $importer = new EntityActivityImporter(1, 1, 1);
        Excel::import($importer, storage_path("test_data/etl/d1_headers.xlsx"));
        $headers = $importer->getHeaders()->headersByIndex;

        // Spreadsheet headers
        // P:Temperature(c)
        // p:stress relief time (hr)
        // S:wire composition
        // S:wire diameter (mm)
        // S:wire density (g/cm^3)
        // P:stress relief temperature (°C)
        // S:bead width (mm)
        // S:bead width (mm)

        $this->assertEquals("c", $headers[0]->unit);
        $this->assertEquals("Temperature", $headers[0]->name);
        $this->assertEquals("activity", $headers[0]->attrType);

        $this->assertEquals("hr", $headers[1]->unit);
        $this->assertEquals("stress relief time", $headers[1]->name);
        $this->assertEquals("activity", $headers[1]->attrType);

        $this->assertEquals("", $headers[2]->unit);
        $this->assertEquals("wire composition", $headers[2]->name);
        $this->assertEquals("entity", $headers[2]->attrType);

        $this->assertEquals("mm", $headers[3]->unit);
        $this->assertEquals("wire diameter", $headers[3]->name);
        $this->assertEquals("entity", $headers[3]->attrType);

        $this->assertEquals("g/cm^3", $headers[4]->unit);
        $this->assertEquals("wire density", $headers[4]->name);
        $this->assertEquals("entity", $headers[4]->attrType);

        $this->assertEquals('°C', $headers[5]->unit);
        $this->assertEquals("stress relief temperature", $headers[5]->name);
        $this->assertEquals("activity", $headers[5]->attrType);

        $this->assertEquals("mm", $headers[6]->unit);
        $this->assertEquals("bead width", $headers[6]->name);
        $this->assertEquals("entity", $headers[6]->attrType);

        $this->assertEquals("mm", $headers[7]->unit);
        $this->assertEquals("bead width", $headers[7]->name);
        $this->assertEquals("entity", $headers[7]->attrType);
    }

    /** @test */
    public function test_simple_import_d1()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $project = factory(Project::class)->create([
            'owner_id' => $user->id,
        ]);
        $experiment = factory(Experiment::class)->create([
            'owner_id'   => $user->id,
            'project_id' => $project->id,
        ]);

        $importer = new EntityActivityImporter($project->id, $experiment->id, $user->id);
        Excel::import($importer, storage_path("test_data/etl/d1.xlsx"));
        $this->assertDatabaseHas('entities', ['project_id' => $project->id, 'name' => 'DOUBLES1']);
    }
}
