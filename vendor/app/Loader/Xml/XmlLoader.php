<?php

namespace App\Vendor\Loader\Xml;

use App\Vendor\Loader\Loader;

class XmlLoader extends Loader
{

    public function loadFromFile($realPath) {
        if (!file_exists($realPath) || ($xml = simplexml_load_file($realPath)) === false) {
            throw new XmlLoaderException('Unable to load from ' . $realPath);
        }

        return $xml;
    }

}
