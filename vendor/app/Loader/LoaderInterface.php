<?php

namespace App\Vendor\Loader;

interface LoaderInterface
{

    public function loadFromString($str);
    public function loadFromFile($path);

}
