<?php

namespace HosseinSheikh\ViewModel\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use HosseinSheikh\ViewModel\Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
        dd(__METHOD__);
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
