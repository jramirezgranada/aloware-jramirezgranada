<?php

namespace App\Providers;

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        CommentResource::withoutWrapping();
        PostResource::withoutWrapping();
    }
}
