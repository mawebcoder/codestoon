<?php

namespace App\Providers;

use App\services\VideoStream;
use Illuminate\Support\ServiceProvider;

class VideoStreamProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('VideoStream',function (){

            return new VideoStream();

        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
