# MQL Extension for CommonMark

This extension handles MQL (Materials Query Language) code blocks in Markdown content. When a code block with the language identifier "mql" is encountered, it executes the code and displays the results.

## Usage

To use this extension, simply include MQL code blocks in your Markdown content:

```markdown
Here is an MQL query:

```mql
$samples->where('type', 'metal')
```

The above will execute the code and display the results.

## Features

The MQL extension provides 4 implicit variables that you can use in your queries:

1. `$samples` - A Laravel collection of sample data
2. `$processes` - A Laravel collection of process data
3. `$computations` - A Laravel collection of computation data
4. `$steps` - A Laravel collection of step data

You can use any Laravel collection methods to filter, map, sort, or otherwise manipulate these collections. For example:

```mql
// Filter samples by type
$samples->where('type', 'metal')

// Filter samples by a property value
$samples->filter(function($sample) {
    return $sample['properties']['hardness'] > 7.0;
})

// Sort processes by temperature
$processes->sortByDesc('temperature')

// Combine multiple collections
$samples->where('type', 'metal')
    ->map(function($sample) {
        return $sample['name'];
    })
    ->merge($steps->pluck('name'))
```

## Security Measures

The MQL extension includes several security measures to prevent arbitrary PHP code execution and protect the system:

### Restrictions

The following restrictions are enforced on MQL code blocks:

1. **PHP Tags**: PHP tags (`<?php`, `<?`, `?>`, etc.) are not allowed
2. **Variable Creation**: Only the 4 provided collection variables can be used; creating new variables is not allowed
3. **Variable Reassignment**: Reassigning the provided collection variables is not allowed
4. **Dangerous Functions**: System execution, code evaluation, file system operations, and other potentially dangerous functions are blocked
5. **Backticks**: Shell execution via backticks is not allowed
6. **Superglobals**: Access to PHP superglobals (`$_GET`, `$_POST`, etc.) is not allowed
7. **Object Instantiation**: Creating new objects with `new` is not allowed
8. **Function/Class Declarations**: Declaring functions or classes is not allowed

### Allowed Methods

Only Laravel collection methods are allowed to be called on the provided collection variables. These include methods like:

- `all`, `average`, `avg`, `chunk`, `collapse`, `collect`, `combine`, `concat`
- `contains`, `containsStrict`, `count`, `countBy`, `crossJoin`, `diff`
- `filter`, `first`, `firstWhere`, `flatMap`, `flatten`, `flip`, `forget`, `forPage`, `get`, `groupBy`
- `has`, `implode`, `intersect`, `isEmpty`, `isNotEmpty`, `join`, `keyBy`, `keys`, `last`
- `map`, `mapInto`, `mapSpread`, `mapToGroups`, `mapWithKeys`, `max`, `median`, `merge`
- `min`, `mode`, `only`, `pad`, `partition`, `pipe`, `pluck`, `pop`, `prepend`, `pull`, `push`
- `put`, `random`, `reduce`, `reject`, `reverse`, `search`, `shift`, `shuffle`, `slice`
- `sort`, `sortBy`, `sortByDesc`, `sortDesc`, `sortKeys`, `sortKeysDesc`, `splice`, `split`
- `sum`, `take`, `tap`, `times`, `toArray`, `toJson`, `transform`, `union`, `unique`
- `values`, `when`, `where`, `whereStrict`, `whereBetween`, `whereIn`, `whereInStrict`, `zip`

### Validation Process

Before executing any MQL code, the extension:

1. Validates that the code doesn't contain any PHP tags or dangerous patterns
2. Checks that the code only uses the provided collection variables
3. Verifies that only allowed Laravel collection methods are called
4. Ensures the code starts with one of the provided collection variables

If any of these validations fail, the code is not executed and an error message is displayed instead.

## Implementation Details

The extension consists of two main components:

1. `MqlExtension.php` - Implements the ExtensionInterface and registers the renderer
2. `MqlRenderer.php` - Implements the NodeRendererInterface and executes MQL code blocks

The renderer:
1. Checks if a fenced code block has the "mql" language identifier
2. Creates the 4 implicit variables as Laravel collections
3. Validates the code for security
4. If valid, executes the code in the MQL block
5. Captures and displays the results

If the language is not "mql", it returns null, allowing other renderers to handle the node.

## Registration

The extension is automatically registered with the CommonMark environment in the `app/View/Components/Markdown.php` file.

## Testing

The MQL extension is tested using PHPUnit. The tests are located in the `tests/Unit/Markdown/Extensions/MqlExtension` directory.

The tests verify:
1. Basic rendering of MQL code blocks
2. Processing of MqlCollection objects
3. Availability of collection methods like `where` and `pluck`
4. Security measures that block dangerous code patterns

To run the tests, use the following command from the project root:

```bash
./vendor/bin/phpunit tests/Unit/Markdown/Extensions/MqlExtension/MqlExtensionTest.php
```
