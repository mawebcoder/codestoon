<?php


namespace App\Facades;


use Illuminate\Support\Facades\Facade;
/**
 *video streaming
 *
 * @method  static binary start($path)
 *
 */
class VideoStream extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'VideoStream';
    }
}
