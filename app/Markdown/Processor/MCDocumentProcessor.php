<?php

namespace App\Markdown\Processor;

use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\Table\Table;

class MCDocumentProcessor
{
    public function onDocumentParsed(DocumentParsedEvent $event): void
    {
        $document = $event->getDocument();
        $walker = $document->walker();
        while ($event = $walker->next()) {
            $node = $event->getNode();
            if (!($node instanceof Table) || !$event->isEntering()) {
                continue;
            }

            $node->data->append('attributes/class', 'table table-bordered table-striped');
        }
    }
}