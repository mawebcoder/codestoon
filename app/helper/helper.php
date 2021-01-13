<?php

if (!function_exists('CreateFakeFile')) {

    /**
     * create a fake file
     *
     * @param $path
     */
    function CreateFakeFile($path)
    {
        $file = fopen($path, 'w+');
        fwrite($file, 'here is');
        fclose($file);
    }


}
