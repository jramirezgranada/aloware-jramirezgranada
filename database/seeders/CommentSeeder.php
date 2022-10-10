<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        $post = DB::table('posts')->first();

        $parentCommentId = DB::table('comments')->insertGetId([
            'name' => $faker->name,
            'message' => $faker->text(200),
            'level' => 1,
            'post_id' => $post->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('comments')->insertGetId([
            'name' => $faker->name,
            'message' => $faker->text(200),
            'level' => 2,
            'post_id' => $post->id,
            'comment_id' => $parentCommentId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
