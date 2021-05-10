<?php

namespace Tests\Feature\Storage;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadUsingS3Test extends TestCase
{
    /** @test */
    public function test_uploading_to_b2_s3()
    {
        $this->markTestSkipped('S3 testing');
        Storage::disk('s3')->putFileAs('d1', "/tmp/file.txt", "file.txt");
        $this->assertTrue(true);
    }

    /** @test */
    public function test_getting_file_url_from_b2_s3()
    {
        $this->markTestSkipped('S3 testing');
        $url = Storage::disk('s3')->url('d1/file.txt');
        echo "URL = {$url}\n";
        $this->assertTrue(true);
    }

    /** @test */
    public function test_downloading_file_from_b2_s3()
    {
        $this->markTestSkipped('S3 testing');
        $what = Storage::disk('s3')->get('d1/file.txt');
        echo "WHAT = {$what}";
        Storage::readStream();

        $this->assertTrue(true);
    }
}
