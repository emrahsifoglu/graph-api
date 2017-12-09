<?php

namespace App\Vendor\Container;

interface ContainerInterface
{

    public function getService($name);
    public function registerService($name, callable $resolver);
    public function setInstance($instance);

}
