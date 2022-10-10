<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function index()
    {
        $posts = DB::table('posts')->get();
        return PostResource::collection($posts);
    }
}
