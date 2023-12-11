<?php

namespace Tests\Unit\Imports\Etl;

use App\Imports\Etl\AttributeHeader;
use Tests\TestCase;

class AttributeHeaderTest extends TestCase
{
    /** @test */
    public function test_no_units_or_type()
    {
        $header = "temp";
        $attrHeader = AttributeHeader::parse($header);
        $this->assertEquals("activity", $attrHeader->attrType);
        $this->assertEquals("temp", $attrHeader->name);
        $this->assertEquals("", $attrHeader->unit);

        $header = " temp";
        $attrHeader = AttributeHeader::parse($header);
        $this->assertEquals("activity", $attrHeader->attrType);
        $this->assertEquals("temp", $attrHeader->name);
        $this->assertEquals("", $attrHeader->unit);

        $header = "temp ";
        $attrHeader = AttributeHeader::parse($header);
        $this->assertEquals("activity", $attrHeader->attrType);
        $this->assertEquals("temp", $attrHeader->name);
        $this->assertEquals("", $attrHeader->unit);

        $header = " temp ";
        $attrHeader = AttributeHeader::parse($header);
        $this->assertEquals("activity", $attrHeader->attrType);
        $this->assertEquals("temp", $attrHeader->name);
        $this->assertEquals("", $attrHeader->unit);
    }

    /** @test */
    public function test_no_units_with_type()
    {
        $header = "p:temp";
        $attrHeader = AttributeHeader::parse($header);
        $this->assertEquals("activity", $attrHeader->attrType);
        $this->assertEquals("temp", $attrHeader->name);
        $this->assertEquals("", $attrHeader->unit);

        $header = "p: temp";
        $attrHeader = AttributeHeader::parse($header);
        $this->assertEquals("activity", $attrHeader->attrType);
        $this->assertEquals("temp", $attrHeader->name);
        $this->assertEquals("", $attrHeader->unit);

        $header = " p: temp";
        $attrHeader = AttributeHeader::parse($header);
        $this->assertEquals("activity", $attrHeader->attrType);
        $this->assertEquals("temp", $attrHeader->name);
        $this->assertEquals("", $attrHeader->unit);
    }

    /** @test */
    public function test_units_with_type()
    {
        $header = "p:temp(m)";
        $attrHeader = AttributeHeader::parse($header);
        $this->assertEquals("activity", $attrHeader->attrType);
        $this->assertEquals("temp", $attrHeader->name);
        $this->assertEquals("m", $attrHeader->unit);

        $header = "p: temp(m)";
        $attrHeader = AttributeHeader::parse($header);
        $this->assertEquals("activity", $attrHeader->attrType);
        $this->assertEquals("temp", $attrHeader->name);
        $this->assertEquals("m", $attrHeader->unit);

        $header = "p: temp (m)";
        $attrHeader = AttributeHeader::parse($header);
        $this->assertEquals("activity", $attrHeader->attrType);
        $this->assertEquals("temp", $attrHeader->name);
        $this->assertEquals("m", $attrHeader->unit);

        $header = "p:temp (m)";
        $attrHeader = AttributeHeader::parse($header);
        $this->assertEquals("activity", $attrHeader->attrType);
        $this->assertEquals("temp", $attrHeader->name);
        $this->assertEquals("m", $attrHeader->unit);
    }

    /** @test */
    public function test_units_with_no_type()
    {
        $header = "temp(m)";
        $attrHeader = AttributeHeader::parse($header);
        $this->assertEquals("activity", $attrHeader->attrType);
        $this->assertEquals("temp", $attrHeader->name);
        $this->assertEquals("m", $attrHeader->unit);

        $header = "temp (m)";
        $attrHeader = AttributeHeader::parse($header);
        $this->assertEquals("activity", $attrHeader->attrType);
        $this->assertEquals("temp", $attrHeader->name);
        $this->assertEquals("m", $attrHeader->unit);

        $header = "temp ( m)";
        $attrHeader = AttributeHeader::parse($header);
        $this->assertEquals("activity", $attrHeader->attrType);
        $this->assertEquals("temp", $attrHeader->name);
        $this->assertEquals("m", $attrHeader->unit);
    }

    /** @test */
    public function test_file_type()
    {
        $header = "file:/a/b/c";
        $attrHeader = AttributeHeader::parse($header);
        $this->assertEquals("file", $attrHeader->attrType);
        $this->assertEquals("/a/b/c", $attrHeader->name);
        $this->assertEquals("", $attrHeader->unit);

        $header = "file: /a/b/c";
        $attrHeader = AttributeHeader::parse($header);
        $this->assertEquals("file", $attrHeader->attrType);
        $this->assertEquals("/a/b/c", $attrHeader->name);
        $this->assertEquals("", $attrHeader->unit);
    }

    /** @test */
    public function test_ignore_type()
    {
        $header = "ignore: abc";
        $attrHeader = AttributeHeader::parse($header);
        $this->assertEquals("ignore", $attrHeader->attrType);
        $this->assertEquals("abc", $attrHeader->name);
        $this->assertEquals("", $attrHeader->unit);

        $header = "note: abc";
        $attrHeader = AttributeHeader::parse($header);
        $this->assertEquals("ignore", $attrHeader->attrType);
        $this->assertEquals("abc", $attrHeader->name);
        $this->assertEquals("", $attrHeader->unit);
    }

    /** @test */
    public function test_bad_type()
    {
        $header = "bad: abc";
        $attrHeader = AttributeHeader::parse($header);
        $this->assertEquals("unknown", $attrHeader->attrType);
        $this->assertEquals("abc", $attrHeader->name);
        $this->assertEquals("", $attrHeader->unit);
    }

    /** @test */
    public function test_calculation()
    {
        $header = "calc:my calculations";
        $attrHeader = AttributeHeader::parse($header);
        $this->assertEquals("calculation", $attrHeader->attrType);
        $this->assertEquals("my calculations", $attrHeader->name);
        $this->assertEquals("", $attrHeader->unit);
    }

    /** @test */
    public function test_calculation_no_name()
    {
        $header = "c:";
        $attrHeader = AttributeHeader::parse($header);
        $this->assertEquals("calculation", $attrHeader->attrType);

        $header = "cal:";
        $attrHeader = AttributeHeader::parse($header);
        $this->assertEquals("calculation", $attrHeader->attrType);

        $header = "calc:";
        $attrHeader = AttributeHeader::parse($header);
        $this->assertEquals("calculation", $attrHeader->attrType);
    }
}
