<?php

namespace App\Markdown\Extensions\QueryExtension;

use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;

class QueryRenderer implements NodeRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): string
    {
        if (!($node instanceof QueryBlock)) {
            throw new \InvalidArgumentException('Incompatible block type: ' . get_class($node));
        }

        $queryContent = $node->getQueryContent();
        $attributes = $node->getAttributes();

        // Here you would execute your query logic
        $result = $this->executeQuery($queryContent, $attributes);

        return new HtmlElement(
            'div',
            ['class' => 'query-result'],
            '<pre>' . htmlspecialchars($result) . '</pre>'
        );
    }

    private function executeQuery(string $query, array $attributes): string
    {
        // Your query execution logic here
        try {
            // Execute the query and return results
            return "Query executed: " . $query . "\nAttributes: " . json_encode($attributes);
        } catch (\Exception $e) {
            return "Query Error: " . $e->getMessage();
        }
    }
}
