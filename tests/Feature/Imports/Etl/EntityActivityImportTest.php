<?php

namespace Tests\Feature\Imports\Etl;

use App\Imports\Etl\EntityActivityImporter;
use App\Models\Activity;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Entity;
use App\Models\EntityState;
use App\Models\Experiment;
use App\Models\File;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class EntityActivityImportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_spreadsheet_d1_headers()
    {
        $importer = new EntityActivityImporter(1, 1, 1);
        Excel::import($importer, Storage::disk('test_data')->path("etl/d1_headers.xlsx"));
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

        $this->assertEquals("", $headers[8]->unit);
        $this->assertEquals("p1/d1", $headers[8]->name);
        $this->assertEquals("file", $headers[8]->attrType);

        $this->assertEquals("", $headers[9]->unit);
        $this->assertEquals("p1", $headers[9]->name);
        $this->assertEquals("file", $headers[9]->attrType);
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
        Excel::import($importer, Storage::disk('test_data')->path("etl/d1.xlsx"));

        // Check entities and entity attributes
        $this->assertDatabaseHas('entities', ['project_id' => $project->id, 'name' => 'DOUBLES1']);
        $this->assertEquals(4, Attribute::where('attributable_type', EntityState::class)->count());
        $this->assertDatabaseHas('attributes', ['name' => 'wire composition']);
        $attrWith2Values = Attribute::where('name', 'bead width')->first();
        $this->assertDatabaseHas('attribute_values',
            ['attribute_id' => $attrWith2Values->id, 'val' => '{"value":122.4135}']);

        // Check activity and activity attributes
        $this->assertDatabaseHas('activities', ['name' => 'sem']);
        $this->assertEquals(1, Activity::count());

        $this->assertEquals(2, Attribute::where('attributable_type', Activity::class)->count());
        $this->assertDatabaseHas('attributes',
            ['name' => 'stress relief time', 'attributable_type' => Activity::class]);
    }

    /** @test */
    public function test_non_spreadsheet_named_file()
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
        Excel::import($importer, Storage::disk('test_data')->path('etl/d006-abc-113'), null, \Maatwebsite\Excel\Excel::XLSX);

        // Check entities and entity attributes
        $this->assertDatabaseHas('entities', ['project_id' => $project->id, 'name' => 'DOUBLES1']);
        $this->assertEquals(4, Attribute::where('attributable_type', EntityState::class)->count());
        $this->assertDatabaseHas('attributes', ['name' => 'wire composition']);
        $attrWith2Values = Attribute::where('name', 'bead width')->first();
        $this->assertDatabaseHas('attribute_values',
            ['attribute_id' => $attrWith2Values->id, 'val' => '{"value":122.4135}']);

        // Check activity and activity attributes
        $this->assertDatabaseHas('activities', ['name' => 'sem']);
        $this->assertEquals(1, Activity::count());

        $this->assertEquals(2, Attribute::where('attributable_type', Activity::class)->count());
        $this->assertDatabaseHas('attributes',
            ['name' => 'stress relief time', 'attributable_type' => Activity::class]);
    }

    /** @test */
    public function test_simple_import_of_2_entities()
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
        Excel::import($importer, Storage::disk('test_data')->path("etl/double.xlsx"));

        // Check entities and entity attributes
        $this->assertDatabaseHas('entities', ['project_id' => $project->id, 'name' => 'DOUBLES1']);
        $this->assertDatabaseHas('entities', ['project_id' => $project->id, 'name' => 'DOUBLES5']);
        $this->assertEquals(8, Attribute::where('attributable_type', EntityState::class)->count());

        // Check activity and activity attributes
        $this->assertDatabaseHas('activities', ['name' => 'sem']);
        $this->assertEquals(2, Activity::count());
        $this->assertEquals(5, Attribute::where('attributable_type', Activity::class)->count());
        $this->assertDatabaseHas('attributes',
            ['name' => 'stress relief temperature', 'attributable_type' => Activity::class]);
        $stressReliefAttr = Attribute::where('name', 'stress relief temperature')->first();
        $this->assertDatabaseHas('attribute_values',
            ['attribute_id' => $stressReliefAttr->id, 'val' => '{"value":2}', 'unit' => "°C"]);
    }

    /** @test */
    public function test_simple_import_2_worksheets()
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
        Excel::import($importer, Storage::disk('test_data')->path("etl/two-worksheets.xlsx"));

        $this->assertDatabaseHas('entities', ['project_id' => $project->id, 'name' => 'DOUBLES1']);
        $this->assertDatabaseHas('entities', ['project_id' => $project->id, 'name' => 'DOUBLES5']);
        $this->assertEquals(2, Activity::count());
        $this->assertEquals(8, Attribute::where('attributable_type', EntityState::class)->count());
        // Check activity and activity attributes
        $this->assertDatabaseHas('activities', ['name' => 'sem']);
        $this->assertDatabaseHas('activities', ['name' => 'tem']);
        $doubles5 = Entity::where('name', 'DOUBLES5')->first();
        $temActivity = Activity::where('name', 'tem')->first();
        $this->assertDatabaseHas('activity2entity', ['activity_id' => $temActivity->id, 'entity_id' => $doubles5->id]);
    }

    /** @test */
    public function test_import_same_entity_and_activity_different_activity_attributes()
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
        Excel::import($importer, Storage::disk('test_data')->path("etl/one-sheet-same-sample-different-activity-attributes.xlsx"));

        $this->assertDatabaseHas('entities', ['project_id' => $project->id, 'name' => 'd1']);
        $this->assertEquals(1, Entity::count());
        $this->assertEquals(2, EntityState::count());
        $this->assertDatabaseHas('activity2entity', ['activity_id' => 1, 'entity_id' => 1]);
        $this->assertDatabaseHas('activity2entity', ['activity_id' => 2, 'entity_id' => 1]);
        $this->assertEquals(2, Activity::count());
    }

    /** @test */
    public function test_import_same_entity_and_activity_same_activity_attributes_different_entity_attribute_values()
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
        Excel::import($importer, Storage::disk('test_data')->path("etl/one-sheet-same-sample-same-activity-attributes.xlsx"));

        $this->assertDatabaseHas('entities', ['project_id' => $project->id, 'name' => 'd1']);
        $this->assertEquals(1, Entity::count());
        $this->assertEquals(1, EntityState::count());
        $this->assertEquals(4, Attribute::where('attributable_type', EntityState::class)->count());
        $entityAttributes = Attribute::where('attributable_type', EntityState::class)->get();
        $entityAttributeIds = $entityAttributes->map(function (Attribute $attr) {
            return $attr->id;
        });
        $this->assertEquals(10, AttributeValue::whereIn('attribute_id', $entityAttributeIds->toArray())->count());
        $beadWidthAttr = Attribute::where('name', 'bead width')->first();
        $this->assertEquals(4, AttributeValue::where('attribute_id', $beadWidthAttr->id)->count());
        $wireCompAttr = Attribute::where('name', 'wire composition')->first();
        $this->assertEquals(2, AttributeValue::where('attribute_id', $wireCompAttr->id)->count());
    }

    /** @test */
    public function test_import_with_parent_2_worksheets()
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
        Excel::import($importer, Storage::disk('test_data')->path("etl/two-worksheets-with-parent.xlsx"));

        $this->assertDatabaseHas('entities', ['project_id' => $project->id, 'name' => 'd1']);
        $this->assertDatabaseHas('entities', ['project_id' => $project->id, 'name' => 'd2']);
        $this->assertEquals(3, Activity::count());
        $activity = Activity::where('name', 'sem')->first();
        $this->assertEquals(2, $activity->entityStates()->count());
        $this->assertDatabaseHas('activity2entity_state', ['activity_id' => $activity->id, 'direction' => 'in']);
        $this->assertDatabaseHas('activity2entity_state', ['activity_id' => $activity->id, 'direction' => 'out']);
    }

    /** @test */
    public function test_file_associations()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $project = factory(Project::class)->create([
            'name'     => 'P1',
            'owner_id' => $user->id,
        ]);

        $rootDir = factory(File::class)->create([
            'project_id' => $project->id,
            'name'       => '/',
            'path'       => '/',
            'mime_type'  => 'directory',
            'owner_id'   => $user->id,
        ]);

        $d1Dir = factory(File::class)->create([
            'project_id'   => $project->id,
            'name'         => 'D1',
            'path'         => '/D1',
            'directory_id' => $rootDir->id,
            'mime_type'    => 'directory',
            'owner_id'     => $user->id,
        ]);

        $f1 = factory(File::class)->create([
            'project_id'   => $project->id,
            'name'         => 'f1.txt',
            'mime_type'    => 'text',
            'directory_id' => $d1Dir->id,
            'owner_id'     => $user->id,
        ]);

        $f2 = factory(File::class)->create([
            'project_id'   => $project->id,
            'name'         => 'f2.txt',
            'mime_type'    => 'text',
            'directory_id' => $d1Dir->id,
            'owner_id'     => $user->id,
        ]);

        $f3 = factory(File::class)->create([
            'project_id'   => $project->id,
            'name'         => 'f3.txt',
            'mime_type'    => 'text',
            'directory_id' => $d1Dir->id,
            'owner_id'     => $user->id,
        ]);

        $f4 = factory(File::class)->create([
            'project_id'   => $project->id,
            'name'         => 'f4.txt',
            'mime_type'    => 'text',
            'directory_id' => $rootDir->id,
            'owner_id'     => $user->id,
        ]);

        $f5 = factory(File::class)->create([
            'project_id'   => $project->id,
            'name'         => 'f5.txt',
            'mime_type'    => 'text',
            'directory_id' => $d1Dir->id,
            'owner_id'     => $user->id,
        ]);

        $experiment = factory(Experiment::class)->create([
            'owner_id'   => $user->id,
            'project_id' => $project->id,
        ]);

        $importer = new EntityActivityImporter($project->id, $experiment->id, $user->id);
        Excel::import($importer, Storage::disk('test_data')->path("etl/file_associations.xlsx"));

        $activity = Activity::where('name', 'sem')->first();
        $this->assertEquals(5, $activity->files()->count());
    }

    /** @test */
    public function test_loading_ff_spreadsheet()
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
        Excel::import($importer, Storage::disk('test_data')->path("etl/FF_Ti5553_ETL.xlsx"));

        $this->assertTrue(true);
    }

    /** @test */
    public function test_dates_load_properly()
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
        Excel::import($importer, Storage::disk('test_data')->path("etl/d1_with_date.csv"));
        $this->assertTrue(true);
    }

    /** @test */
    public function read_date_cell()
    {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load(Storage::disk('test_data')->path("etl/d1_with_date.xlsx"));
        $cell = $spreadsheet->getActiveSheet()->getCell("E2");
        $value = $cell->getValue();
        $valueFormatted = $cell->getFormattedValue();
        $valueCalculated = $cell->getCalculatedValue();
        $dataType = $cell->getDataType();
        echo "cell value = {$value}\n";
        echo "cell valueFormatted = {$valueFormatted}\n";
        echo "cell valueCalculated = {$valueCalculated}\n";
        echo "dataType = {$dataType}\n";
        $numberFormat = $cell->getStyle()->getNumberFormat()->getFormatCode();
        echo "numberFormat = {$numberFormat}\n";
        $this->assertTrue(true);
    }
}
