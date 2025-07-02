<?php

namespace Tests\Unit\Services;

use App\Services\SphinxSearchService;
use Tests\TestCase;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;

class SphinxSearchServiceTest extends TestCase
{
    private vfsStreamDirectory $root;
    private string $searchIndexContent;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a virtual file system
        $this->root = vfsStream::setup('root');

        // Create a directory structure for the search index
        vfsStream::newDirectory('_build/html')->at($this->root);

        // Sample search index content (simplified JSON)
        $this->searchIndexContent = json_encode([
            'words' => [
                'test' => [[0, 0]],
                'search' => [[0, 1]],
                'sphinx' => [[0, 2]]
            ],
            'titles' => ['Test Document'],
            'objects' => [
                [
                    ['path/to/test', 'Test context', 0],
                    ['path/to/search', 'Search context', 1],
                    ['path/to/sphinx', 'Sphinx context', 2]
                ]
            ],
            'objnames' => [
                [0, 'Test Type'],
                [1, 'Search Type'],
                [2, 'Sphinx Type']
            ]
        ]);
    }

    /** @test */
    public function it_throws_exception_when_search_index_not_found()
    {
        $service = new SphinxSearchService(vfsStream::url('root'));

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Search index not found');

        $service->loadSearchIndex();
    }

    /** @test */
    public function it_loads_search_index_successfully()
    {
        // Create the search index file
        file_put_contents(
            vfsStream::url('root/_build/html/searchindex.js'),
            $this->searchIndexContent
        );

        $service = new SphinxSearchService(vfsStream::url('root'));
        $result = $service->loadSearchIndex();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('words', $result);
        $this->assertArrayHasKey('titles', $result);
        $this->assertArrayHasKey('objects', $result);
        $this->assertArrayHasKey('objnames', $result);
    }

    /** @test */
    public function it_throws_exception_when_json_is_invalid()
    {
        // Create an invalid JSON file
        file_put_contents(
            vfsStream::url('root/_build/html/searchindex.js'),
            '{invalid json'
        );

        $service = new SphinxSearchService(vfsStream::url('root'));

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Invalid JSON syntax');

        $service->loadSearchIndex();
    }

    /** @test */
    public function it_searches_and_returns_results()
    {
        // Create the search index file
        file_put_contents(
            vfsStream::url('root/_build/html/searchindex.js'),
            $this->searchIndexContent
        );

        $service = new SphinxSearchService(vfsStream::url('root'));
        $results = $service->search('test');

        $this->assertIsArray($results);
        $this->assertCount(1, $results);
        $this->assertEquals('Test Document', $results[0]['title']);
        $this->assertEquals('path/to/test', $results[0]['path']);
        $this->assertEquals('Test context', $results[0]['context']);
        $this->assertEquals('Test Type', $results[0]['type']);
    }

    /** @test */
    public function it_searches_and_returns_multiple_results()
    {
        // Create the search index file
        file_put_contents(
            vfsStream::url('root/_build/html/searchindex.js'),
            $this->searchIndexContent
        );

        $service = new SphinxSearchService(vfsStream::url('root'));
        $results = $service->search('test search');

        $this->assertIsArray($results);
        $this->assertCount(2, $results);
    }

    /** @test */
    public function it_returns_empty_array_when_no_results_found()
    {
        // Create the search index file
        file_put_contents(
            vfsStream::url('root/_build/html/searchindex.js'),
            $this->searchIndexContent
        );

        $service = new SphinxSearchService(vfsStream::url('root'));
        $results = $service->search('nonexistent');

        $this->assertIsArray($results);
        $this->assertEmpty($results);
    }
}
