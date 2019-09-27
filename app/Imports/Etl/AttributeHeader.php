<?php

namespace App\Imports\Etl;

use Illuminate\Support\Str;

class AttributeHeader
{
    public $name;
    public $unit;
    public $attrType;

    private static $entityKeywords = [
        "s"      => true,
        "sample" => true,
    ];

    private static $activityKeywords = [
        "p"       => true,
        "process" => true,
    ];

    private static $fileKeywords = [
        "f"     => true,
        "file"  => true,
        "files" => true,
    ];

    private static $ignoreKeywords = [
        "i"      => true,
        "ignore" => true,
        "note"   => true,
        "notes"  => true,
    ];

    public function __construct($name, $unit, $attrType)
    {
        $this->name = $name;
        $this->unit = $unit;
        $this->attrType = $attrType;
    }

    public static function parse($header)
    {
        $attrType = "entity";
        $headerTrimmed = trim($header);
        $headerLower = Str::lower($headerTrimmed);
        $colon = strpos($headerLower, ':');
        if ($colon !== false) {
            $attrType = AttributeHeader::getHeaderType(substr($headerLower, 0, $colon));
        }

        return AttributeHeader::parseHeaderType($attrType, $colon, $headerTrimmed);
    }

    private static function getHeaderType($str)
    {
        if (array_key_exists($str, AttributeHeader::$entityKeywords)) {
            return "entity";
        } elseif (array_key_exists($str, AttributeHeader::$activityKeywords)) {
            return "activity";
        } elseif (array_key_exists($str, AttributeHeader::$fileKeywords)) {
            return "file";
        } elseif (array_key_exists($str, AttributeHeader::$ignoreKeywords)) {
            return "ignore";
        } else {
            return "unknown";
        }
    }

    private static function parseHeaderType($attrType, $colon, $header)
    {
        $openParen = strpos($header, "(");
        $closeParen = strpos($header, ")");
        $unit = "";
        // Setting starting position to -1 if colon is true. Setting it to -1 means that we
        // don't have to special case the $startingPos+1 to either add one or not depending
        // on the colon. We just always add 1.
        $startingPos = $colon === false ? -1 : $colon;
        if (!$openParen) {
            $name = trim(substr($header, $startingPos + 1));
        } else {
            $name = trim(substr($header, $startingPos + 1, $openParen - $startingPos - 1));
            $unit = trim(substr($header, $openParen + 1, $closeParen - ($openParen + 1)));
        }
        return new AttributeHeader($name, $unit, $attrType);
    }
}