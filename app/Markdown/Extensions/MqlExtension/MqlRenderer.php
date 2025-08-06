<?php

namespace App\Markdown\Extensions\MqlExtension;

use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use App\Collections\MqlCollection;

class MqlRenderer implements NodeRendererInterface
{
    /**
     * List of allowed Laravel collection methods
     * @var array
     */
    private $allowedMethods = [
        'all', 'average', 'avg', 'chunk', 'collapse', 'collect', 'combine', 'concat',
        'contains', 'containsStrict', 'count', 'countBy', 'crossJoin', 'dd', 'diff',
        'diffAssoc', 'diffKeys', 'dump', 'duplicates', 'duplicatesStrict', 'each',
        'every', 'except', 'filter', 'first', 'firstWhere', 'flatMap', 'flatten',
        'flip', 'forget', 'forPage', 'get', 'groupBy', 'has', 'implode', 'intersect',
        'intersectByKeys', 'isEmpty', 'isNotEmpty', 'join', 'keyBy', 'keys', 'last',
        'macro', 'make', 'map', 'mapInto', 'mapSpread', 'mapToGroups', 'mapWithKeys',
        'max', 'median', 'merge', 'mergeRecursive', 'min', 'mode', 'nth', 'only',
        'pad', 'partition', 'pipe', 'pluck', 'pop', 'prepend', 'pull', 'push',
        'put', 'random', 'reduce', 'reject', 'reverse', 'search', 'shift', 'shuffle',
        'slice', 'some', 'sort', 'sortBy', 'sortByDesc', 'sortDesc', 'sortKeys',
        'sortKeysDesc', 'splice', 'split', 'sum', 'take', 'tap', 'times', 'toArray',
        'toJson', 'transform', 'union', 'unique', 'uniqueStrict', 'unless', 'unlessEmpty',
        'unlessNotEmpty', 'unwrap', 'values', 'when', 'whenEmpty', 'whenNotEmpty',
        'where', 'whereStrict', 'whereBetween', 'whereIn', 'whereInStrict', 'whereNotBetween',
        'whereNotIn', 'whereNotInStrict', 'wrap', 'zip'
    ];


    /**
     * List of dangerous PHP functions and patterns that should be blocked
     * @var array
     */
    private $blockedPatterns = [
        // PHP tags
        '/<\?php/i', '/<\?/i', '/\?>/i', '/<%/i', '/%>/i', '/<script\s+language\s*=\s*["\']?php["\']?\s*>/i',

        // System execution
        '/system\s*\(/i', '/exec\s*\(/i', '/shell_exec\s*\(/i', '/passthru\s*\(/i',
        '/proc_open\s*\(/i', '/popen\s*\(/i', '/pcntl_exec\s*\(/i',

        // Code evaluation
        '/eval\s*\(/i', '/assert\s*\(/i', '/create_function\s*\(/i',

        // File inclusion
        '/include\s*\(/i', '/include_once\s*\(/i', '/require\s*\(/i', '/require_once\s*\(/i',

        // File system operations
        '/file_get_contents\s*\(/i', '/file_put_contents\s*\(/i', '/fopen\s*\(/i',
        '/unlink\s*\(/i', '/rmdir\s*\(/i', '/mkdir\s*\(/i', '/copy\s*\(/i',
        '/rename\s*\(/i', '/symlink\s*\(/i', '/link\s*\(/i', '/chmod\s*\(/i',
        '/chown\s*\(/i', '/touch\s*\(/i',

        // Directory operations
        '/glob\s*\(/i', '/scandir\s*\(/i', '/opendir\s*\(/i', '/readdir\s*\(/i',
        '/dir\s*\(/i', '/DirectoryIterator/i', '/RecursiveDirectoryIterator/i',

        // Other dangerous patterns
        '/`/i', // Backticks for shell execution
        '/\$_GET/i', '/\$_POST/i', '/\$_REQUEST/i', '/\$_FILES/i', '/\$_COOKIE/i',
        '/\$_SESSION/i', '/\$_SERVER/i', '/\$_ENV/i', '/\$GLOBALS/i',
        '/\$\w*\s*=\s*(?!collect)/i', // Variable assignment except for collection variables
        '/new\s+/i', // Creating new objects
        '/function\s+\w+/i', // Function declaration
        '/class\s+\w+/i', // Class declaration
        '/namespace\s+/i', // Namespace declaration
        '/use\s+/i', // Use statement
        '/require/i', '/include/i', // Include/require without parentheses
        '/\becho\b/i', '/\bprint\b/i', '/\bdie\b/i', '/\bexit\b/i' // Output/exit functions
    ];

    /**
     * Validates that the MQL code is safe to execute
     *
     * @param  string  $code  The MQL code to validate
     * @return array [bool $isValid, string $errorMessage]
     */
    private function validateCode($code)
    {
        // Check for PHP tags
        if (preg_match('/<\?(?:php|=|\s)/i', $code) || preg_match('/\?>/', $code)) {
            return [false, "PHP tags are not allowed in MQL code"];
        }

        // Check for variable assignments (except in function parameters)
        if (preg_match('/\$(?!samples|processes|computations|steps)(\w+)\s*=\s*/i', $code)) {
            return [
                false,
                "Creating new variables is not allowed. Only use the provided collection variables: \$samples, \$processes, \$computations, or \$steps"
            ];
        }

        // Check for reassignment of provided variables
        if (preg_match('/\$(samples|processes|computations|steps)\s*=\s*(?!collect\()/i', $code)) {
            return [false, "Reassigning the provided collection variables is not allowed"];
        }

        // Check for dangerous functions
        $dangerousFunctions = [
            // System execution
            'system', 'exec', 'shell_exec', 'passthru', 'proc_open', 'popen', 'pcntl_exec',
            // Code evaluation
            'eval', 'assert', 'create_function',
            // File inclusion
            'include', 'include_once', 'require', 'require_once',
            // File system operations
            'file_get_contents', 'file_put_contents', 'fopen', 'unlink', 'rmdir', 'mkdir',
            'copy', 'rename', 'symlink', 'link', 'chmod', 'chown', 'touch',
            // Directory operations
            'glob', 'scandir', 'opendir', 'readdir', 'dir'
        ];

        foreach ($dangerousFunctions as $function) {
            if (preg_match('/\b'.preg_quote($function, '/').'\s*\(/i', $code)) {
                return [false, "The function '{$function}' is not allowed in MQL code"];
            }
        }

        // Check for backticks (shell execution)
        if (preg_match('/`/', $code)) {
            return [false, "Backticks (shell execution) are not allowed in MQL code"];
        }

        // Check for superglobals
        $superglobals = ['_GET', '_POST', '_REQUEST', '_FILES', '_COOKIE', '_SESSION', '_SERVER', '_ENV', 'GLOBALS'];
        foreach ($superglobals as $global) {
            if (preg_match('/\$'.preg_quote($global, '/').'\b/i', $code)) {
                return [false, "The superglobal \${$global} is not allowed in MQL code"];
            }
        }

        // Check for object instantiation
        if (preg_match('/new\s+/i', $code)) {
            return [false, "Creating new objects is not allowed in MQL code"];
        }

        // Check for function/class declarations
        if (preg_match('/\bfunction\s+\w+/i', $code) || preg_match('/\bclass\s+\w+/i', $code)) {
            return [false, "Declaring functions or classes is not allowed in MQL code"];
        }

        // Validate that the code starts with one of the allowed collection variables
        $lines = explode("\n", trim($code));
        $firstLine = trim($lines[0]);
        if (!preg_match('/^\$(?:samples|processes|computations|steps)->/i', $firstLine)) {
            return [
                false,
                "Code must start with one of the provided collection variables: \$samples, \$processes, \$computations, or \$steps"
            ];
        }

        // Validate that only allowed collection methods are used
        preg_match_all('/->(\w+)\s*\(/i', $code, $matches);
        if (!empty($matches[1])) {
            foreach ($matches[1] as $method) {
                if (!in_array(strtolower($method), array_map('strtolower', $this->allowedMethods))) {
                    return [false, "Method '{$method}' is not allowed. Only Laravel collection methods can be used."];
                }
            }
        }

        return [true, ""];
    }

    /**
     * @param  Node  $node
     * @param  ChildNodeRendererInterface  $childRenderer
     * @return string|null
     */
    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        if (!($node instanceof FencedCode)) {
            return null;
        }

        // Get the info string (language identifier)
        $infoWords = $node->getInfoWords();
        $language = count($infoWords) > 0 ? $infoWords[0] : '';

        // Only handle 'mql' language blocks
        if (strtolower($language) !== 'mql') {
            return null;
        }

        // Get the code content from the MQL block
        $code = $node->getLiteral();

        // Create the 4 implicit variables as Laravel collections
        $samples = collect();
        $processes = collect();
        $computations = collect();
        $steps = collect();

        // Add some sample data to the collections for testing
        $samples = MqlCollection::make([
            ['id' => 1, 'name' => 'Sample 1', 'type' => 'metal', 'properties' => ['hardness' => 7.5]],
            ['id' => 2, 'name' => 'Sample 2', 'type' => 'ceramic', 'properties' => ['hardness' => 9.0]],
            ['id' => 3, 'name' => 'Sample 3', 'type' => 'polymer', 'properties' => ['hardness' => 3.2]],
            ['id' => 4, 'name' => 'Sample 4', 'type' => 'metal', 'properties' => ['hardness' => 6.8]],
            ['id' => 5, 'name' => 'Sample 5', 'type' => 'composite', 'properties' => ['hardness' => 8.3]],
        ]);

        $processes = MqlCollection::make([
            ['id' => 1, 'name' => 'Heat Treatment', 'temperature' => 800, 'duration' => 2],
            ['id' => 2, 'name' => 'Annealing', 'temperature' => 600, 'duration' => 4],
            ['id' => 3, 'name' => 'Quenching', 'temperature' => 900, 'duration' => 0.5],
            ['id' => 4, 'name' => 'Tempering', 'temperature' => 400, 'duration' => 3],
        ]);

        $computations = MqlCollection::make([
            ['id' => 1, 'name' => 'DFT Calculation', 'method' => 'VASP', 'parameters' => ['cutoff' => 400]],
            ['id' => 2, 'name' => 'MD Simulation', 'method' => 'LAMMPS', 'parameters' => ['timestep' => 0.001]],
            ['id' => 3, 'name' => 'Phase Field', 'method' => 'MOOSE', 'parameters' => ['grid' => '100x100']],
        ]);

        $steps = MqlCollection::make([
            ['id' => 1, 'name' => 'Step 1', 'description' => 'Sample preparation'],
            ['id' => 2, 'name' => 'Step 2', 'description' => 'Measurement'],
            ['id' => 3, 'name' => 'Step 3', 'description' => 'Analysis'],
            ['id' => 4, 'name' => 'Step 4', 'description' => 'Reporting'],
        ]);

        // Validate the code before execution
        list($isValid, $errorMessage) = $this->validateCode($code);

        // Execute the code and capture the output
        $result = null;

        if (!$isValid) {
            $result = "MQL Security Error: ".$errorMessage;
        } else {
            try {
                // Create a closure that has access to the variables and executes the code
                $executionClosure = function ($code, $samples, $processes, $computations, $steps) {
                    // Extract variables to make them accessible in the eval scope
                    extract([
                        'samples'      => $samples,
                        'processes'    => $processes,
                        'computations' => $computations,
                        'steps'        => $steps,
                    ]);

                    // We still need to use eval, but we've validated the code is safe
                    // and only contains allowed collection methods

                    // Capture output
                    ob_start();

                    // Execute the code
                    $result = eval('return '.$code.';');

                    // If the code doesn't return a value, use the output buffer
                    $output = ob_get_clean();

                    return $result ?: $output;
                };

                $result = $executionClosure($code, $samples, $processes, $computations, $steps);
            } catch (\Throwable $e) {
                // If there's an error, display it
                $result = "Error executing MQL code: ".$e->getMessage();
            }
        }

        // Dump the result
        ob_start();
        var_dump($result);
        $dump = ob_get_clean();

        // Return the result in a div
        return new HtmlElement(
            'div',
            ['class' => 'mql-result'],
            '<pre>'.htmlspecialchars($dump).'</pre>'
        );
    }
}
