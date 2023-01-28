<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use VoyagerDataTransport\Traits\VoyagerImportData;

class MockImportDataTest extends TestCase
{

    use VoyagerImportData;

    const SKIP_HEADER = 10;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
