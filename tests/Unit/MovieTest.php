<?php

namespace Tests\Unit;

use App\Services\Movie;
use Tests\TestCase;

class MovieTest extends TestCase
{
    public function test_get_movie_by_id_success(): void
    {
        $service = new Movie();
        $get = $service->getById("tt0816692");
        $this->assertTrue($get['success']);
        $this->assertEquals(200, $get['code']);
        $this->assertArrayHasKey("Title", $get['data']);
        $this->assertEquals($get['data']["Title"], "Interstellar");
    }

    public function test_get_movie_by_id_failed_because_id_doesnt_exists(): void
    {
        $service = new Movie();
        $get = $service->getById("123123123");
        $this->assertFalse($get['success']);
        $this->assertEquals(404, $get['code']);
    }

    public function test_get_movie_by_name_success(): void
    {
        $service = new Movie();
        $get = $service->getByName("Interstellar");
        $this->assertTrue($get['success']);
        $this->assertEquals(200, $get['code']);
        $this->assertArrayHasKey("Title", $get['data']);
        $this->assertEquals($get['data']["Title"], "Interstellar");
    }


    public function test_get_movie_by_name_failed_because_name_doesnt_exists(): void
    {
        $service = new Movie();
        $get = $service->getByName("NameNotExists");
        $this->assertFalse($get['success']);
        $this->assertEquals(404, $get['code']);
    }
}
