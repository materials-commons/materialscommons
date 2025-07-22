<?php

namespace Tests\Unit\Http\Controllers\Web\Folders;

use App\Http\Controllers\Web\Folders\ShowAddUrlWebController;
use App\Http\Controllers\Web\Folders\StoreUrlWebController;
use App\Models\File;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class UrlControllersTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Project $project;
    private File $folder;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user
        $this->user = User::factory()->create();

        // Create a project
        $this->project = Project::factory()->create([
            'owner_id' => $this->user->id
        ]);

        // Create a root directory
        $this->folder = File::factory()->create([
            'project_id' => $this->project->id,
            'owner_id' => $this->user->id,
            'name' => '/',
            'path' => '/',
            'mime_type' => 'directory'
        ]);
    }

    /** @test */
    public function show_add_url_controller_returns_correct_view()
    {
        // Create a request with query parameters
        $request = Request::create('/test', 'GET', [
            'destdir' => 1,
            'destproj' => 1,
            'arg' => 'test'
        ]);

        // Create the controller
        $controller = new ShowAddUrlWebController();

        // Call the controller
        $response = $controller->__invoke($request, $this->project, $this->folder);

        // Assert the correct view is returned
        $this->assertEquals('app.projects.folders.add-url', $response->getName());

        // Assert the view has the correct data
        $this->assertEquals($this->project, $response->getData()['project']);
        $this->assertEquals($this->folder, $response->getData()['directory']);
        $this->assertEquals(1, $response->getData()['destDir']);
        $this->assertEquals(1, $response->getData()['destProj']);
        $this->assertEquals('test', $response->getData()['arg']);
    }

    /** @test */
    public function store_url_controller_creates_url_file()
    {
        // Login as the user
        Auth::login($this->user);

        // Create a request with form data
        $request = Request::create('/test', 'POST', [
            'name' => 'Test URL',
            'url' => 'https://example.com'
        ]);

        // Create the controller
        $controller = new StoreUrlWebController();

        // Call the controller
        $response = $controller->__invoke($request, $this->project, $this->folder);

        // Assert a URL file was created
        $this->assertDatabaseHas('files', [
            'name' => 'Test URL',
            'url' => 'https://example.com',
            'mime_type' => 'url',
            'project_id' => $this->project->id,
            'directory_id' => $this->folder->id
        ]);

        // Assert the response is a redirect
        $this->assertEquals(302, $response->getStatusCode());

        // Assert the redirect is to the correct route
        $this->assertEquals(
            route('projects.folders.show', [$this->project, $this->folder]),
            $response->getTargetUrl()
        );
    }

    /** @test */
    public function store_url_controller_validates_input()
    {
        // Login as the user
        Auth::login($this->user);

        // Create a request with invalid data (missing URL)
        $request = Request::create('/test', 'POST', [
            'name' => 'Test URL'
        ]);

        // Create the controller
        $controller = new StoreUrlWebController();

        // Expect a validation exception
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        // Call the controller
        $controller->__invoke($request, $this->project, $this->folder);
    }

    /** @test */
    public function store_url_controller_validates_url_format()
    {
        // Login as the user
        Auth::login($this->user);

        // Create a request with invalid URL format
        $request = Request::create('/test', 'POST', [
            'name' => 'Test URL',
            'url' => 'not-a-url'
        ]);

        // Create the controller
        $controller = new StoreUrlWebController();

        // Expect a validation exception
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        // Call the controller
        $controller->__invoke($request, $this->project, $this->folder);
    }
}
