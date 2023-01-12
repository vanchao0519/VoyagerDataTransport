<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{

    private $_email_addr = 'admin@admin.com';
    private $_password = 'password';

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_voyager_route()
    {
        $response = $this->post('/admin/login', [
            'email' => $this->_email_addr,
            'password' => $this->_password,
        ]);

        $response->assertRedirect('/admin');

        $response = $this->get('/admin/posts');

        $response->assertStatus(200);
    }
}
