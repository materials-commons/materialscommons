<?php

namespace Tests\Feature\Imports\Etl;

use App\Imports\Etl\EntityActivityImporter;
use App\Imports\Etl\EtlState;
use App\Models\Activity;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Entity;
use App\Models\EntityState;
use App\Models\Experiment;
use App\Models\File;
use App\Models\Project;
use App\Models\User;
use Facades\Tests\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EntityActivityImportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_spreadsheet_d1_headers()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'owner_id' => $user->id,
        ]);
        $experiment = Experiment::factory()->create([
            'owner_id'   => $user->id,
            'project_id' => $project->id,
        ]);

        $importer = new EntityActivityImporter($project->id, $experiment->id, $user->id, new EtlState($user->id));
        $importer->execute(Storage::disk('test_data')->path("etl/d1_headers.xlsx"));
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
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'owner_id' => $user->id,
        ]);
        $experiment = Experiment::factory()->create([
            'owner_id'   => $user->id,
            'project_id' => $project->id,
        ]);

        $importer = new EntityActivityImporter($project->id, $experiment->id, $user->id, new EtlState($user->id));
        $importer->execute(Storage::disk('test_data')->path("etl/d1.xlsx"));

        // Check entities and entity attributes
        $this->assertDatabaseHas('entities', ['project_id' => $project->id, 'name' => 'DOUBLES1']);
        $this->assertEquals(4, Attribute::where('attributable_type', EntityState::class)->count());
        $this->assertDatabaseHas('attributes', ['name' => 'wire composition']);

        $attrWith2Values = Attribute::where('name', 'bead width')->first();
        $attrValue = AttributeValue::where('attribute_id', $attrWith2Values->id)->first();
        $this->assertEquals(122.4135, $attrValue->val["value"]);

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
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'owner_id' => $user->id,
        ]);
        $experiment = Experiment::factory()->create([
            'owner_id'   => $user->id,
            'project_id' => $project->id,
        ]);

        $importer = new EntityActivityImporter($project->id, $experiment->id, $user->id, new EtlState($user->id));
        $importer->execute(Storage::disk('test_data')->path('etl/d006-abc-113'));

        // Check entities and entity attributes
        $this->assertDatabaseHas('entities', ['project_id' => $project->id, 'name' => 'DOUBLES1']);
        $this->assertEquals(4, Attribute::where('attributable_type', EntityState::class)->count());
        $this->assertDatabaseHas('attributes', ['name' => 'wire composition']);

        $attrWith2Values = Attribute::where('name', 'bead width')->first();
        $attrValue = AttributeValue::where('attribute_id', $attrWith2Values->id)->first();
        $this->assertEquals(122.4135, $attrValue->val["value"]);

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
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'owner_id' => $user->id,
        ]);
        $experiment = Experiment::factory()->create([
            'owner_id'   => $user->id,
            'project_id' => $project->id,
        ]);

        $importer = new EntityActivityImporter($project->id, $experiment->id, $user->id, new EtlState($user->id));
        $importer->execute(Storage::disk('test_data')->path("etl/double.xlsx"));

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
        $attrValue = AttributeValue::where('attribute_id', $stressReliefAttr->id)->first();
        $this->assertEquals(2, $attrValue->val["value"]);
        $this->assertEquals("°C", $attrValue->unit);
    }

    /** @test */
    public function test_simple_import_2_worksheets()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'owner_id' => $user->id,
        ]);
        $experiment = Experiment::factory()->create([
            'owner_id'   => $user->id,
            'project_id' => $project->id,
        ]);

        $importer = new EntityActivityImporter($project->id, $experiment->id, $user->id, new EtlState($user->id));
        $importer->execute(Storage::disk('test_data')->path("etl/two-worksheets.xlsx"));

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
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'owner_id' => $user->id,
        ]);
        $experiment = Experiment::factory()->create([
            'owner_id'   => $user->id,
            'project_id' => $project->id,
        ]);

        $importer = new EntityActivityImporter($project->id, $experiment->id, $user->id, new EtlState($user->id));
        $importer->execute(Storage::disk('test_data')->path("etl/one-sheet-same-sample-different-activity-attributes.xlsx"));

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
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'owner_id' => $user->id,
        ]);
        $experiment = Experiment::factory()->create([
            'owner_id'   => $user->id,
            'project_id' => $project->id,
        ]);

        $importer = new EntityActivityImporter($project->id, $experiment->id, $user->id, new EtlState($user->id));
        $importer->execute(Storage::disk('test_data')->path("etl/one-sheet-same-sample-same-activity-attributes.xlsx"));

        $this->assertDatabaseHas('entities', ['project_id' => $project->id, 'name' => 'd1']);
        $this->assertEquals(1, Entity::count());
        $this->assertEquals(2, EntityState::count());
        $this->assertEquals(8, Attribute::where('attributable_type', EntityState::class)->count());
        $entityAttributes = Attribute::where('attributable_type', EntityState::class)->get();
        $entityAttributeIds = $entityAttributes->map(function (Attribute $attr) {
            return $attr->id;
        });
        $this->assertEquals(10, AttributeValue::whereIn('attribute_id', $entityAttributeIds->toArray())->count());
        $beadWidthAttr = Attribute::where('name', 'bead width')->first();
        $this->assertEquals(2, AttributeValue::where('attribute_id', $beadWidthAttr->id)->count());
        $wireCompAttr = Attribute::where('name', 'wire composition')->first();
        $this->assertEquals(1, AttributeValue::where('attribute_id', $wireCompAttr->id)->count());
    }

    /** @test */
    public function test_import_with_parent_2_worksheets()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'owner_id' => $user->id,
        ]);
        $experiment = Experiment::factory()->create([
            'owner_id'   => $user->id,
            'project_id' => $project->id,
        ]);

        $importer = new EntityActivityImporter($project->id, $experiment->id, $user->id, new EtlState($user->id));
        $importer->execute(Storage::disk('test_data')->path("etl/two-worksheets-with-parent.xlsx"));

        $this->assertDatabaseHas('entities', ['project_id' => $project->id, 'name' => 'd1']);
        $this->assertDatabaseHas('entities', ['project_id' => $project->id, 'name' => 'd2']);
        $this->assertEquals(3, Activity::count());
        $activity = Activity::where('name', 'sem')->first();
        $this->assertEquals(2, $activity->entityStates()->count());
        $this->assertDatabaseHas('activity2entity_state', ['activity_id' => $activity->id, 'direction' => 'in']);
        $this->assertDatabaseHas('activity2entity_state', ['activity_id' => $activity->id, 'direction' => 'out']);
    }

    /** @test */
    public function test_single_file_associations()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'name'     => 'P1',
            'owner_id' => $user->id,
        ]);

        $rootDir = File::factory()->create([
            'project_id' => $project->id,
            'name'       => '/',
            'path'       => '/',
            'mime_type'  => 'directory',
            'owner_id'   => $user->id,
        ]);

        $d1Dir = File::factory()->create([
            'project_id'   => $project->id,
            'name'         => 'D1',
            'path'         => '/D1',
            'directory_id' => $rootDir->id,
            'mime_type'    => 'directory',
            'owner_id'     => $user->id,
        ]);

        $f1 = File::factory()->create([
            'project_id'   => $project->id,
            'name'         => 'f1.txt',
            'mime_type'    => 'text',
            'directory_id' => $d1Dir->id,
            'owner_id'     => $user->id,
        ]);

        $f2 = File::factory()->create([
            'project_id'   => $project->id,
            'name'         => 'f2.txt',
            'mime_type'    => 'text',
            'directory_id' => $d1Dir->id,
            'owner_id'     => $user->id,
        ]);

        $f3 = File::factory()->create([
            'project_id'   => $project->id,
            'name'         => 'f3.txt',
            'mime_type'    => 'text',
            'directory_id' => $d1Dir->id,
            'owner_id'     => $user->id,
        ]);

        $f4 = File::factory()->create([
            'project_id'   => $project->id,
            'name'         => 'f4.txt',
            'mime_type'    => 'text',
            'directory_id' => $rootDir->id,
            'owner_id'     => $user->id,
        ]);

        $f5 = File::factory()->create([
            'project_id'   => $project->id,
            'name'         => 'f5.txt',
            'mime_type'    => 'text',
            'directory_id' => $d1Dir->id,
            'owner_id'     => $user->id,
        ]);

        $experiment = Experiment::factory()->create([
            'owner_id'   => $user->id,
            'project_id' => $project->id,
        ]);

        $importer = new EntityActivityImporter($project->id, $experiment->id, $user->id, new EtlState($user->id));
        $importer->execute(Storage::disk('test_data')->path("etl/file_associations.xlsx"));

        $activity = Activity::where('name', 'sem')->first();
        $this->assertEquals(5, $activity->files()->count());

        $entity = Entity::where('name', 'G181030g')->first();
        $this->assertEquals(5, $entity->files()->count());
    }

    /** @test */
    public function test_directory_file_associations()
    {
        // Setup
        $project = ProjectFactory::create();
        $root = $project->rootDir;
        $d1 = ProjectFactory::createDirectory($project, $root, "d1");
        ProjectFactory::createFakeFile($project, $d1, "f1.txt");
        ProjectFactory::createFakeFile($project, $d1, "f2.txt");
        $d2 = ProjectFactory::createDirectory($project, $root, "d2");
        $d3 = ProjectFactory::createDirectory($project, $d2, "d3");
        ProjectFactory::createFakeFile($project, $d3, "f3.txt");
        $experiment = Experiment::factory()->create([
            'owner_id'   => $project->owner_id,
            'project_id' => $project->id,
        ]);

        // Run importer
        $importer = new EntityActivityImporter($project->id, $experiment->id, $project->owner_id,
            new EtlState($project->owner_id));
        $importer->execute(Storage::disk('test_data')->path('etl/single_with_dir.xlsx'));

        // Asserts
        $activity = Activity::where('name', 'sem')->first();
        $this->assertEquals(3, $activity->files()->count());

        $entity = Entity::where('name', 'G181030g')->first();
        $this->assertEquals(3, $entity->files()->count());
    }

    /** @test */
    public function test_wildcard_file_associations()
    {
        // Setup
        $project = ProjectFactory::create();
        $root = $project->rootDir;
        $d1 = ProjectFactory::createDirectory($project, $root, "d1");
        ProjectFactory::createFakeFile($project, $d1, "f1.txt");
        ProjectFactory::createFakeFile($project, $d1, "f2.txt");
        ProjectFactory::createFakeFile($project, $d1, "f3.jpeg");
        $experiment = Experiment::factory()->create([
            'owner_id'   => $project->owner_id,
            'project_id' => $project->id,
        ]);

        // Run importer
        $importer = new EntityActivityImporter($project->id, $experiment->id, $project->owner_id,
            new EtlState($project->owner_id));
        $importer->execute(Storage::disk('test_data')->path('etl/single_with_file_wildcard.xlsx'));

        // Asserts
        $activity = Activity::where('name', 'sem')->first();
        $this->assertEquals(2, $activity->files()->count());

        $entity = Entity::where('name', 'G181030g')->first();
        $this->assertEquals(2, $entity->files()->count());
    }

    /** @test */
    public function test_multiple_file_associations_in_one_cell()
    {
        // Setup
        $project = ProjectFactory::create();
        $root = $project->rootDir;
        $d1 = ProjectFactory::createDirectory($project, $root, "d1");
        ProjectFactory::createFakeFile($project, $d1, "f1.txt");
        ProjectFactory::createFakeFile($project, $d1, "f2.txt");
        $experiment = Experiment::factory()->create([
            'owner_id'   => $project->owner_id,
            'project_id' => $project->id,
        ]);

        // Run importer
        $importer = new EntityActivityImporter($project->id, $experiment->id, $project->owner_id,
            new EtlState($project->owner_id));
        $importer->execute(Storage::disk('test_data')->path('etl/single_with_multiple_files_in_cell.xlsx'));

        // Asserts
        $activity = Activity::where('name', 'sem')->first();
        $this->assertEquals(2, $activity->files()->count());

        $entity = Entity::where('name', 'G181030g')->first();
        $this->assertEquals(2, $entity->files()->count());
    }

    /** @test */
    public function test_loading_ff_spreadsheet()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'owner_id' => $user->id,
        ]);
        $experiment = Experiment::factory()->create([
            'owner_id'   => $user->id,
            'project_id' => $project->id,
        ]);

        $importer = new EntityActivityImporter($project->id, $experiment->id, $user->id, new EtlState($user->id));
        $importer->execute(Storage::disk('test_data')->path("etl/FF_Ti5553_ETL.xlsx"));

        $this->assertTrue(true);
    }

    /** @test */
    public function test_dates_load_properly()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'owner_id' => $user->id,
        ]);
        $experiment = Experiment::factory()->create([
            'owner_id'   => $user->id,
            'project_id' => $project->id,
        ]);

        $importer = new EntityActivityImporter($project->id, $experiment->id, $user->id, new EtlState($user->id));
        $importer->execute(Storage::disk('test_data')->path("etl/d1_with_date.xlsx"));

        $attr = Attribute::where('name', 'date completed')->first();
        $attrValue = AttributeValue::where('attribute_id', $attr->id)->first();
        $this->assertEquals("03/05/20", $attrValue->val["value"]);
    }

    /** @test */
    public function test_loading_spreadsheet_with_global_settings()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'owner_id' => $user->id,
        ]);
        $experiment = Experiment::factory()->create([
            'owner_id'   => $user->id,
            'project_id' => $project->id,
        ]);
        $importer = new EntityActivityImporter($project->id, $experiment->id, $user->id, new EtlState($user->id));
        $importer->execute(Storage::disk('test_data')->path("etl/d1_with_global_settings.xlsx"));
        $this->assertEquals(4, Attribute::where('attributable_type', Activity::class)->count());
        $this->assertDatabaseHas('attributes',
            ['name' => 'voltage', 'attributable_type' => Activity::class]);
        $attr = Attribute::where('name', 'voltage')->first();
        $attrValue = AttributeValue::where('attribute_id', $attr->id)->first();
        $this->assertEquals(10, $attrValue->val["value"]);
        $this->assertEquals("v", $attrValue->unit);

        $attr = Attribute::where('name', 'magnification')->first();
        $attrValue = AttributeValue::where('attribute_id', $attr->id)->first();
        $this->assertEquals(5, $attrValue->val["value"]);
    }

    public function test_loading_spreadsheet_with_boolean_values()
    {
        $this->withoutExceptionHandling();
        $project = ProjectFactory::withExperiment()->create();
        $experiment = $project->experiments->first();
        $importer = new EntityActivityImporter($project->id, $experiment->id, $project->owner_id,
            new EtlState($project->owner_id));
        $importer->execute(Storage::disk('test_data')->path("etl/single_with_bools.xlsx"));
        $this->assertEquals(2, Attribute::all()->count());
        $this->assertEquals(2, AttributeValue::all()->count());
        Attribute::all()->each(function (Attribute $attribute) {
            $attribute->load(['values']);
            switch ($attribute->name) {
                case "valid":
                    $this->assertEquals("TRUE", $attribute->values[0]->val["value"]);
                    break;
                case "finished":
                    $this->assertEquals("FALSE", $attribute->values[0]->val["value"]);
                    break;
            }
        });
    }
}
