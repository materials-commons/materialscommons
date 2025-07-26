<?php

namespace Tests\Unit\Markdown\Extensions\BracketExtension;

use App\Markdown\Extensions\BracketExtension\BracketExtension;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\MarkdownConverter;
use PHPUnit\Framework\TestCase;

class BracketExtensionTest extends TestCase
{
    private MarkdownConverter $converter;

    protected function setUp(): void
    {
        // Create a new environment
        $environment = new Environment([
            'html_input' => 'allow',
            'allow_unsafe_links' => true,
        ]);

        // Add the CommonMark core extension
        $environment->addExtension(new CommonMarkCoreExtension());

        // Add our BracketExtension
        $environment->addExtension(new BracketExtension());

        // Create a new converter
        $this->converter = new MarkdownConverter($environment);
    }

    public function testSCommandRendering()
    {
        $markdown = 'This is a test with {{s:hello}}';
        $expected = "<p>This is a test with <div>s:hello</div></p>\n";

        $html = $this->converter->convert($markdown)->getContent();
        $this->assertEquals($expected, $html);
    }

    public function testMultipleCommands()
    {
        $markdown = 'This is a test with {{s:hello}} and {{s:world}}';
        $expected = "<p>This is a test with <div>s:hello</div> and <div>s:world</div></p>\n";

        $html = $this->converter->convert($markdown)->getContent();
        $this->assertEquals($expected, $html);
    }

    public function testUnknownCommand()
    {
        $markdown = 'This is a test with {{unknown:command}}';
        $expected = "<p>This is a test with {{unknown:command}}</p>\n";

        $html = $this->converter->convert($markdown)->getContent();
        $this->assertEquals($expected, $html);
    }

    public function testEmptyCommand()
    {
        $markdown = 'This is a test with {{}}';
        $expected = "<p>This is a test with {{}}</p>\n";

        $html = $this->converter->convert($markdown)->getContent();
        $this->assertEquals($expected, $html);
    }

    public function testCommandWithoutValue()
    {
        $markdown = 'This is a test with {{s}}';
        $expected = "<p>This is a test with <div>s:</div></p>\n";

        $html = $this->converter->convert($markdown)->getContent();
        $this->assertEquals($expected, $html);
    }

    public function testCommandWithSpecialCharacters()
    {
        $markdown = 'This is a test with {{s:hello & world}}';
        $expected = "<p>This is a test with <div>s:hello & world</div></p>\n";

        $html = $this->converter->convert($markdown)->getContent();
        $this->assertEquals($expected, $html);
    }
}
