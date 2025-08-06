<?php

namespace Tests\Unit\Markdown\Extensions\MqlExtension;

/**
 * This test class was converted from the standalone test script tests/test_mql_extension.php
 * to follow PHPUnit testing conventions. It tests the functionality of the MQL extension,
 * including basic rendering, collection methods, and security measures.
 */

use App\Markdown\Extensions\MqlExtension\MqlExtension;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\MarkdownConverter;
use PHPUnit\Framework\TestCase;

class MqlExtensionTest extends TestCase
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

        // Add our MqlExtension
        $environment->addExtension(new MqlExtension());

        // Create a new converter
        $this->converter = new MarkdownConverter($environment);
    }

    // Using PHPUnit's built-in assertStringContainsString method instead of defining our own

    /**
     * Helper function to check if any string in an array contains a substring
     */
    private function assertArrayContainsStringPartial(array $haystack, string $needle, string $message = ''): void
    {
        $found = false;
        foreach ($haystack as $item) {
            if (strpos($item, $needle) !== false) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found, $message ?: "Array does not contain any string with '$needle'");
    }

    /**
     * Test that the MQL extension is properly rendering MQL code blocks
     */
    public function testMqlExtensionRendering()
    {
        $markdown = "```mql\n\$samples->where('type', 'metal')\n```";
        $html = $this->converter->convert($markdown)->getContent();

        $this->assertStringContainsString('<div class="mql-result">', $html, 'MQL Extension is not rendering correctly');
    }

    /**
     * Test that MqlCollection objects are being processed correctly
     */
    public function testMqlCollectionsProcessing()
    {
        $markdown = "```mql\n\$samples->where('type', 'metal')\n```";
        $html = $this->converter->convert($markdown)->getContent();

        $this->assertStringContainsString('object(App\Collections\MqlCollection)', $html, 'MqlCollection objects are not being processed correctly');
    }

    /**
     * Test that the where method is available
     */
    public function testWhereMethodAvailability()
    {
        $markdown = "```mql\n\$samples->where('type', 'metal')\n```";
        $html = $this->converter->convert($markdown)->getContent();

        // Check that we don't get a security error for using the where method
        $this->assertStringNotContainsString('MQL Security Error', $html, 'Where method is not available');
        $this->assertStringContainsString('array(0)', $html, 'Where method is not returning expected result');
    }

    /**
     * Test filtering samples by properties
     */
    public function testSampleFilteringByProperties()
    {
        $markdown = "```mql\n\$samples->filter(function(\$sample) {\n    return \$sample['properties']['hardness'] > 7.0;\n})\n```";
        $html = $this->converter->convert($markdown)->getContent();

        $this->assertStringContainsString('hardness', $html, 'Sample filtering by properties is not working');
    }

    /**
     * Test sorting processes by temperature
     */
    public function testProcessSorting()
    {
        $markdown = "```mql\n\$processes->sortByDesc('temperature')\n```";
        $html = $this->converter->convert($markdown)->getContent();

        $this->assertStringContainsString('temperature', $html, 'Process sorting is not working');
    }

    /**
     * Test using the pluck method on collections
     */
    public function testPluckMethod()
    {
        $markdown = "```mql\n\$steps->pluck('name')\n```";
        $html = $this->converter->convert($markdown)->getContent();

        $this->assertStringContainsString('Step 1', $html, 'Pluck method is not working (Step 1 not found)');
        $this->assertStringContainsString('Step 2', $html, 'Pluck method is not working (Step 2 not found)');
        $this->assertStringContainsString('Step 3', $html, 'Pluck method is not working (Step 3 not found)');
        $this->assertStringContainsString('Step 4', $html, 'Pluck method is not working (Step 4 not found)');
    }

    /**
     * Test that PHP tags are properly blocked
     */
    public function testPhpTagsBlocking()
    {
        $markdown = "```mql\n<?php echo \"This should be blocked\"; ?>\n```";
        $html = $this->converter->convert($markdown)->getContent();

        $this->assertStringContainsString('MQL Security Error:', $html, 'Security error message not found');
        $this->assertStringContainsString('PHP tags are not allowed', $html, 'PHP tags are not properly blocked');
    }

    /**
     * Test that file system functions are properly blocked
     */
    public function testFileSystemFunctionsBlocking()
    {
        $markdown = "```mql\n\$samples->map(function(\$sample) {\n    file_put_contents('/tmp/test.txt', 'This should be blocked');\n    return \$sample;\n})\n```";
        $html = $this->converter->convert($markdown)->getContent();

        $this->assertStringContainsString('MQL Security Error:', $html, 'Security error message not found');

        // Extract all security error messages from the HTML
        preg_match_all('/MQL Security Error: ([^<]+)/', $html, $errorMatches);
        $errorMessages = $errorMatches[1] ?? [];

        // Convert error messages to lowercase for case-insensitive matching
        $errorMessagesLower = array_map('strtolower', $errorMessages);

        $this->assertArrayContainsStringPartial($errorMessagesLower, 'file_put_contents', 'File system functions are not properly blocked');
    }

    /**
     * Test that system execution is properly blocked
     */
    public function testSystemExecutionBlocking()
    {
        $markdown = "```mql\n\$samples->map(function(\$sample) {\n    system('ls -la');\n    return \$sample;\n})\n```";
        $html = $this->converter->convert($markdown)->getContent();

        $this->assertStringContainsString('MQL Security Error:', $html, 'Security error message not found');

        // Extract all security error messages from the HTML
        preg_match_all('/MQL Security Error: ([^<]+)/', $html, $errorMatches);
        $errorMessages = $errorMatches[1] ?? [];

        // Convert error messages to lowercase for case-insensitive matching
        $errorMessagesLower = array_map('strtolower', $errorMessages);

        $this->assertArrayContainsStringPartial($errorMessagesLower, 'system', 'System execution is not properly blocked');
    }

    /**
     * Test that eval function is properly blocked
     */
    public function testEvalFunctionBlocking()
    {
        $markdown = "```mql\n\$samples->map(function(\$sample) {\n    eval('echo \"This should be blocked\";');\n    return \$sample;\n})\n```";
        $html = $this->converter->convert($markdown)->getContent();

        $this->assertStringContainsString('MQL Security Error:', $html, 'Security error message not found');

        // Extract all security error messages from the HTML
        preg_match_all('/MQL Security Error: ([^<]+)/', $html, $errorMatches);
        $errorMessages = $errorMatches[1] ?? [];

        // Convert error messages to lowercase for case-insensitive matching
        $errorMessagesLower = array_map('strtolower', $errorMessages);

        $this->assertArrayContainsStringPartial($errorMessagesLower, 'eval', 'Eval function is not properly blocked');
    }

    /**
     * Test that non-whitelisted methods are properly blocked
     */
    public function testNonWhitelistedMethodsBlocking()
    {
        $markdown = "```mql\n\$samples->nonExistentMethod()\n```";
        $html = $this->converter->convert($markdown)->getContent();

        $this->assertStringContainsString('MQL Security Error:', $html, 'Security error message not found');

        // Extract all security error messages from the HTML
        preg_match_all('/MQL Security Error: ([^<]+)/', $html, $errorMatches);
        $errorMessages = $errorMatches[1] ?? [];

        // Convert error messages to lowercase for case-insensitive matching
        $errorMessagesLower = array_map('strtolower', $errorMessages);

        $this->assertArrayContainsStringPartial($errorMessagesLower, 'method', 'Non-whitelisted methods are not properly blocked');
        $this->assertArrayContainsStringPartial($errorMessagesLower, 'not allowed', 'Non-whitelisted methods are not properly blocked');
    }

    /**
     * Test that unauthorized variables are properly blocked
     */
    public function testUnauthorizedVariablesBlocking()
    {
        $markdown = "```mql\n\$unauthorizedVariable = \"This should be blocked\";\n\$samples->count()\n```";
        $html = $this->converter->convert($markdown)->getContent();

        $this->assertStringContainsString('MQL Security Error:', $html, 'Security error message not found');

        // Extract all security error messages from the HTML
        preg_match_all('/MQL Security Error: ([^<]+)/', $html, $errorMatches);
        $errorMessages = $errorMatches[1] ?? [];

        // Convert error messages to lowercase for case-insensitive matching
        $errorMessagesLower = array_map('strtolower', $errorMessages);

        $this->assertArrayContainsStringPartial($errorMessagesLower, 'creating new variables is not allowed', 'Unauthorized variables are not properly blocked');
    }

    /**
     * Test that direct variable assignment is properly blocked
     */
    public function testDirectVariableAssignmentBlocking()
    {
        $markdown = "```mql\n\$samples = \"Overwriting variables should be blocked\";\n\$samples->count()\n```";
        $html = $this->converter->convert($markdown)->getContent();

        $this->assertStringContainsString('MQL Security Error:', $html, 'Security error message not found');

        // Extract all security error messages from the HTML
        preg_match_all('/MQL Security Error: ([^<]+)/', $html, $errorMatches);
        $errorMessages = $errorMatches[1] ?? [];

        // Convert error messages to lowercase for case-insensitive matching
        $errorMessagesLower = array_map('strtolower', $errorMessages);

        $this->assertArrayContainsStringPartial($errorMessagesLower, 'reassigning the provided collection variables is not allowed', 'Direct variable assignment is not properly blocked');
    }
}
