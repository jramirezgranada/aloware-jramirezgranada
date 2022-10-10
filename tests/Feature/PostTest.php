<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    /**
     * @test
     */
    public function blog_api_is_accessible()
    {
        $this->json('get', 'api/posts')
            ->assertStatus(200);
    }

    /**
     * @test
     */
    public function should_return_posts_data()
    {
        $this->json('get', '/api/posts')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                    ],
                ],
            ]);
    }
}
