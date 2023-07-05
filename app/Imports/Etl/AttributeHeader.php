<?php

namespace App\Imports\Etl;

use Illuminate\Support\Str;

class AttributeHeader
{
    public $name;
    public $unit;
    public $attrType;
    public $important;

    private static $entityKeywords = [
        "s"      => true,
        "sample" => true,
    ];

    private static $importantEntityKeywords = [
        "is"      => true,
        "si"      => true,
        "isample" => true,
    ];

    private static $entityTagKeywords = [
        "st" => true,
    ];

    private static $activityKeywords = [
        "p"       => true,
        "process" => true,
    ];

    private static $importantActivityKeywords = [
        "ip"       => true,
        "pi"       => true,
        "iprocess" => true,
    ];

    private static $activityTagKeywords = [
        "pt" => true,
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

    private static $calculationKeywords = [
        "c"    => true,
        "calc" => true,
        "cal"  => true,
    ];

    public function __construct($name, $unit, $attrType)
    {
        $this->name = $name;
        $this->unit = $unit;
        $this->important = false;
        $this->attrType = $attrType;
        if ($attrType == "important-entity") {
            $this->attrType = "entity";
            $this->important = true;
        } elseif ($attrType == "important-activity") {
            $this->attrType = "activity";
            $this->important = true;
        }
    }

    public static function parse($header)
    {
        // Set the default header type if the user didn't specify one
        $attrType = "activity";

        // See if the user specified a header type (that is there is a colon in the header), and use it.
        $headerTrimmed = trim($header);
        $headerLower = Str::lower($headerTrimmed);
        $colon = strpos($headerLower, ':');

        // If there was a colon then set attrType to the specified header type. If there wasn't
        // a colon, then the user didn't specify a header type, so it will default to what $attrType
        // was originally set to at the top.
        if ($colon !== false) {
            $attrType = AttributeHeader::getHeaderType(substr($headerLower, 0, $colon));
        }

        return AttributeHeader::parseHeaderType($attrType, $colon, $headerTrimmed);
    }

    private static function getHeaderType($str)
    {
        if (array_key_exists($str, AttributeHeader::$entityKeywords)) {
            return "entity";
        } elseif (array_key_exists($str, AttributeHeader::$importantEntityKeywords)) {
            return "important-entity";
        } elseif (array_key_exists($str, AttributeHeader::$entityTagKeywords)) {
            return "tags-entity";
        } elseif (array_key_exists($str, AttributeHeader::$activityKeywords)) {
            return "activity";
        } elseif (array_key_exists($str, AttributeHeader::$importantActivityKeywords)) {
            return "important-activity";
        } elseif (array_key_exists($str, AttributeHeader::$activityTagKeywords)) {
            return "tags-activity";
        } elseif (array_key_exists($str, AttributeHeader::$fileKeywords)) {
            return "file";
        } elseif (array_key_exists($str, AttributeHeader::$calculationKeywords)) {
            return "calculation";
        } elseif (array_key_exists($str, AttributeHeader::$ignoreKeywords)) {
            return "ignore";
        } else {
            return "unknown";
        }
    }

    private static function parseHeaderType($attrType, $colon, $header)
    {
        if ($attrType === "file") {
            return AttributeHeader::parseFileHeader($colon, $header);
        } else {
            return AttributeHeader::parseEntityOrActivityHeader($attrType, $colon, $header);
        }
    }

    private static function parseFileHeader($colon, $header)
    {
        $firstColon = strpos($header, ":");
        $secondColon = strrpos($header, ":");
        if ($firstColon != $secondColon) {
            // if firstColon != secondColon then there is a description and a path
            // ie, the format is:  FILE:My description:directory-path/to/file/in/cell/in/materials-commons
            $filePath = trim(substr($header, $secondColon + 1));
            return new AttributeHeader($filePath, "", "file");
        }

        // If we are here then firstColon == secondColon, which means the format is:
        // FILE:directory-path/to/file/in/cell/in/materials-commons
        $filePath = trim(substr($header, $firstColon + 1));
        return new AttributeHeader($filePath, "", "file");
    }

    private static function parseEntityOrActivityHeader($attrType, $colon, $header)
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