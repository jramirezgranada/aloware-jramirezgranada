<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    /**
     * @test
     */
    public function comments_api_is_accessible()
    {
        $this->json('get', 'api/comments')
            ->assertStatus(200);
    }

    /**
     * @test
     */
    public function should_return_comments_data()
    {
        $this->json('get', '/api/comments')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'message',
                    ],
                ],
            ]);
    }

    /**
     * @test
     */
    public function should_return_validation_errors()
    {
        $post = DB::table('posts')->first();

        $comment = $this->json('post', 'api/comments', [
            'message' => $this->faker->text,
            'post_id' => $post->id,
        ])->assertStatus(422)->assertJsonStructure([
            'message',
            'errors',
        ]);
    }

    /**
     * @test
     */
    public function should_insert_first_comment(): int
    {
        $post = DB::table('posts')->first();

        $comment = $this->json('post', 'api/comments', [
            'name' => $this->faker->name,
            'message' => $this->faker->text,
            'post_id' => $post->id,
        ])->assertStatus(200)->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'message',
            ]
        ]);

        $commentId = json_decode($comment->getContent())->data->id;

        $this->assertDatabaseHas('comments', ['id' => $commentId]);

        return $commentId;
    }

    /**
     * @test
     */
    public function should_insert_second_level_comment()
    {
        $post = DB::table('posts')->first();

        $comment = $this->json('post', 'api/comments', [
            'name' => $this->faker->name,
            'message' => $this->faker->text,
            'post_id' => $post->id,
            'comment_id' => 1
        ])->assertStatus(200)->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'message',
            ]
        ]);

        $comment = json_decode($comment->getContent())->data;

        $this->assertDatabaseHas('comments', ['id' => $comment->id, 'level' => 2]);
    }

    /**
     * @test
     */
    public function should_throw_an_exception_when_try_to_insert_fourth_comment()
    {
        $post = DB::table('posts')->first();

        $comment = $this->json('post', 'api/comments', [
            'name' => $this->faker->name,
            'message' => $this->faker->text,
            'post_id' => $post->id,
            'comment_id' => 1
        ])->assertStatus(200)->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'message',
            ]
        ]);

        $comment = json_decode($comment->getContent())->data;

        $this->assertDatabaseHas('comments', ['id' => $comment->id, 'level' => 2]);

        $comment = $this->json('post', 'api/comments', [
            'name' => $this->faker->name,
            'message' => $this->faker->text,
            'post_id' => $post->id,
            'comment_id' => $comment->id
        ])->assertStatus(200)->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'message',
            ]
        ]);

        $comment = json_decode($comment->getContent())->data;

        $this->assertDatabaseHas('comments', ['id' => $comment->id, 'level' => 3]);

        $comment = $this->json('post', 'api/comments', [
            'name' => $this->faker->name,
            'message' => $this->faker->text,
            'post_id' => $post->id,
            'comment_id' => $comment->id
        ])->assertStatus(422)->assertJsonStructure(['error']);

        $error = json_decode($comment->getContent());

        $this->assertEquals('Comments system only allows 3 layers of comments', $error->error);
    }
}
