<?php

namespace App\Services;

class SphinxSearchService
{
    private string $searchIndexPath;

    public function __construct(string $sphinxBuildPath)
    {
        $this->searchIndexPath = $sphinxBuildPath.'/_build/html/searchindex.js';
    }

    public function loadSearchIndex(): array
    {
        if (!file_exists($this->searchIndexPath)) {
            throw new \RuntimeException('Search index not found');
        }

        $content = file_get_contents($this->searchIndexPath);

//        // First remove the Search.setIndex wrapper
//        $content = preg_replace('/^Search\.setIndex\((.*)\);?$/s', '$1', $content);
//
//        // Handle JavaScript object keys that aren't quoted
//        $content = preg_replace('/(\w+):/i', '"$1":', $content);
//
//        // Convert JavaScript literal values to JSON format
//        $content = str_replace("'", '"', $content); // Replace single quotes with double quotes
//
//        // Additional cleanup for JavaScript syntax
//        $content = preg_replace('/(\d+)n\b/', '$1', $content); // Handle BigInt notation if present
//        $content = preg_replace('/,\s*}/', '}', $content); // Remove trailing commas in objects
//        $content = preg_replace('/,\s*\]/', ']', $content); // Remove trailing commas in arrays

        $data = json_decode($content, true);

        if (json_last_error() === JSON_ERROR_SYNTAX) {
//            $this->debugJsonError($content);
            throw new \RuntimeException('Invalid JSON syntax');
        }

        return $data;

    }

    private function debugJsonError(string $json): void
    {
        $json = trim($json);
        for ($i = 0; $i < strlen($json); $i++) {
            $json_validate = substr($json, 0, $i + 1);
            json_decode($json_validate);
            if (json_last_error() === JSON_ERROR_SYNTAX) {
                $context = substr($json, max(0, $i - 50), 100);
                $position = min(50, $i);
                echo "Syntax error around position {$i}:\n";
                echo $context."\n";
                echo str_repeat(' ', $position)."^ Error here\n";
                break;
            }
        }
    }


    public function search(string $query): array
    {
        $index = $this->loadSearchIndex();
        $results = [];

        // Convert query to search terms
        $terms = preg_split('/\s+/', strtolower($query));

        foreach ($terms as $term) {
            foreach ($index['words'] as $word => $locations) {
                if (str_contains(strtolower($word), $term)) {
                    foreach ($locations as [$docIndex, $objIndex]) {
                        $obj = $index['objects'][$docIndex][$objIndex];
                        $results[] = [
                            'title'   => $index['titles'][$docIndex],
                            'path'    => $obj[0],
                            'context' => $obj[1],
                            'type'    => $index['objnames'][$obj[2]][1]
                        ];
                    }
                }
            }
        }

        return array_unique($results, SORT_REGULAR);
    }
}
