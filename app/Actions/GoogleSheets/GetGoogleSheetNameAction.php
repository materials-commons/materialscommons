<?php

namespace App\Actions\GoogleSheets;

use DOMDocument;

class GetGoogleSheetNameAction
{
    public function execute($url)
    {
        $url = $url."&headers=true";
        $contents = file_get_contents($url);

        $doc = new DOMDocument();
        @$doc->loadHTML($contents);
        $meta = $doc->getElementsByTagName('meta');
        $title = "";
        $sawTitle = false;

        foreach ($meta as $element) {
            foreach ($element->attributes as $node) {
                if ($sawTitle) {
                    if ($node->name == "content") {
                        $title = $node->value;
                        break;
                    }
                }
                if ($node->name == "property" && $node->value == "og:title") {
                    $sawTitle = true;
                }
            }
            if ($sawTitle) {
                return $title;
            }
        }

        return null;
    }
}