<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\ParameterTrait;
use Tests\Feature\Traits\UserTrait;
use Tests\TestCase;

/**
 * Class AccessDataTransportPagesTest
 * @package Tests\Feature
 */
class AccessDataTransportPagesTest extends TestCase
{
    use ParameterTrait;
    use UserTrait;

    /**
     * Access import page and export page.
     *
     * @return void
     */
    public function test_access_page (): void
    {
        $response = $this->post('/admin/login', [
            'email' => $this->_email,
            'password' => $this->_password,
        ]);

        $tableName = $this->_getTableName();

        $response->assertRedirect('/admin');

        $response = $this->get("/admin/import_{$tableName}");
        $response->assertStatus(200);

        $view = $this->view("vendor.voyager.{$tableName}.import-data");
        $view->assertSee("import_{$tableName}");
        $view->assertSee("userfile");
        $view->assertSee("shouldSkipHeader");

        $response = $this->get("/admin/export_{$tableName}");
        $response->assertStatus(200);

        $view = $this->view("vendor.voyager.{$tableName}.export-data");
        $view->assertSee("export_{$tableName}");
        $view->assertSee("export_type");
        $view->assertSee("Download");

        $response = $this->post("/admin/import_{$tableName}/upload");
        $response->assertRedirect();

        $response = $this->post("/admin/export_{$tableName}/download");
        $response->assertSuccessful();

    }


}
