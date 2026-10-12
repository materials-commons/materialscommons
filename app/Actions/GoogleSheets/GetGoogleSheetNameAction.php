<?php

namespace App\Actions\GoogleSheets;

use DOMDocument;

class GetGoogleSheetNameAction
{
    public function execute($url)
    {
        $url = $url."&headers=true";
        $contents = file_get_contents($url);

        if (!$contents) {
            return null;
        }

        $doc = new DOMDocument();
        @$doc->loadHTML($contents);
        $meta = $doc->getElementsByTagName('meta');

        foreach ($meta as $element) {
            $property = $element->getAttribute("property");
            if ($property == "og:title") {
                $title = $element->getAttribute("content");
                return blank($title) ? null : $title;
            }
        }

        return null;
    }
}
