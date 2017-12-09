<?php

namespace App\Vendor\Config;

class Config implements ConfigInterface
{

    private $config;

    public function __construct(
        $config
    ) {
        $this->config = $config;
    }

    public function getConfig() {
        return $this->config;
    }

    public function getConnectionOptions() {
        return $this->config['dataSource']['database'];
    }

    public function getDataSource() {
        return $this->config['dataSource'];
    }

}
