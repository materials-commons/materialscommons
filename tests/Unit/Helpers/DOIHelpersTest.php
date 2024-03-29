<?php

namespace Tests\Unit\Helpers;

use App\Helpers\DOIHelpers;
use GuzzleHttp\Client;
use Tests\TestCase;

class DOIHelpersTest extends TestCase
{
    /** @test */
    public function test_make_doi_ez_api()
    {
        $this->markTestSkipped('DOI Creation skipped for now');
        $client = new Client();
        $serviceUrl = config('doi.service_url');
        $uri = "${serviceUrl}/shoulder/doi:".config('doi.namespace');
        $body = "_target:https://materialscommons.org\n".
            "datacite.creator: Test Author\n".
            "datacite.title: Test publish 1111\n".
            "datacite.publisher: Materials Commons\n".
            "datacite.publicationyear: 2019\n".
            "datacite.resourcetype: Dataset";

        $resp = $client->request('POST', $uri, [
            'headers' => ['Content-Type' => 'text/plain'],
            'body'    => $body,
            'auth'    => [config('doi.user'), config('doi.password')],
        ]);

        $respBody = (string) $resp->getBody();
        $matches = [];
        preg_match("/doi:\S*/", $respBody, $matches);
        error_log("the match '{$matches[0]}'");
        $this->assertTrue(true);
    }

    /** @test */
    public function test_parse_out_response()
    {
        $matches = [];
        $resp = "success: doi:10.33587/mjxa-rm95\n".
            "_target: https://materialscommons.org\n".
            'datacite: <?xml version="1.0" encoding="UTF-8"?>%0A<resource xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://datacite.org/schema/kernel-4" xsi:schemaLocation="http://datacite.org/schema/kernel-4 http://schema.datacite.org/meta/kernel-4/metadata.xsd">%0A  <identifier identifierType="DOI">10.33587/MJXA-RM95</identifier>%0A  <creators>%0A    <creator>%0A      <creatorName>Test Author</creatorName>%0A    </creator>%0A  </creators>%0A  <titles>%0A    <title>Test publish 1111</title>%0A  </titles>%0A  <publisher>Materials Commons</publisher>%0A  <publicationYear>2019</publicationYear>%0A  <resourceType resourceTypeGeneral="Dataset"/>%0A  <version/>%0A</resource>%0A'."\n".
            "_profile: datacite\n".
            "_datacenter: UMICH.MC\n".
            "_export: yes\n".
            "_created: 1570810597\n".
            "_updated: 1570810597\n".
            "_status: public";
        preg_match("/doi:\S*/", $resp, $matches);
        $doi = str_replace("doi:", "", $matches[0]);
        $this->assertEquals("10.33587/mjxa-rm95", $doi);
    }

    /** @test */
    public function test_mintDOI()
    {
        $this->markTestSkipped('DOI Creation skipped for now');
        $doi = DOIHelpers::mintDOI("A title", "a author", 1);
        echo "doi = {$doi}\n";
        $this->assertNotEmpty($doi);
    }
}
