# MQL Extension Test

This is a test file to verify that the MQL extension works correctly and that security measures are properly implemented.

## Regular Markdown

This is regular markdown content with **bold** and *italic* text.

## MQL Query with Laravel Collection Methods

Below is an MQL query that uses Laravel collection methods to filter samples:

```mql
$samples->where('type', 'metal')
```

## Regular Code Block

Below is a regular code block that should be rendered normally:

```php
<?php
echo "Hello, World!";
```

## MQL Query with Multiple Filters

Here's an MQL query that filters samples by hardness:

```mql
$samples->filter(function($sample) {
    return $sample['properties']['hardness'] > 7.0;
})
```

## MQL Query with Sorting

Here's an MQL query that sorts processes by temperature:

```mql
$processes->sortByDesc('temperature')
```

## MQL Query with Multiple Collections

Here's an MQL query that combines multiple collections:

```mql
$samples->where('type', 'metal')->map(function($sample) {
    return $sample['name'];
})->merge(
    $steps->pluck('name')
)
```

## Security Test Cases

The following test cases should be blocked by the security measures:

### PHP Tags (Should Be Blocked)

```mql
<?php echo "This should be blocked"; ?>
```

### File System Functions (Should Be Blocked)

```mql
$samples->map(function($sample) {
    file_put_contents('/tmp/test.txt', 'This should be blocked');
    return $sample;
})
```

### System Execution (Should Be Blocked)

```mql
$samples->map(function($sample) {
    system('ls -la');
    return $sample;
})
```

### Eval Function (Should Be Blocked)

```mql
$samples->map(function($sample) {
    eval('echo "This should be blocked";');
    return $sample;
})
```

### Non-Whitelisted Methods (Should Be Blocked)

```mql
$samples->nonExistentMethod()
```

### Unauthorized Variables (Should Be Blocked)

```mql
$unauthorizedVariable = "This should be blocked";
$samples->count()
```

### Direct Variable Assignment (Should Be Blocked)

```mql
$samples = "Overwriting variables should be blocked";
$samples->count()
```
