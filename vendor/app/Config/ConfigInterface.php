<?php

namespace App\Vendor\Config;

interface ConfigInterface
{

    public function getConfig();
    public function getConnectionOptions();
    public function getDataSource();

}
