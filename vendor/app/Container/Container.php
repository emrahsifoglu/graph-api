<?php

namespace App\Vendor\Container;

use ReflectionClass;
use ReflectionParameter;
use RuntimeException;

class Container implements ContainerInterface
{

    /**
     * @var array
     */
    private $registries;

    /**
     * @var array
     */
    private $instances;

    /**
     * Container constructor.
     * @param array $registries
     */
    public function __construct(
        $registries = []
    ) {
        $this->registries = $registries;
        $this->instances = [];
    }

    /**
     * @param $instance
     */
    public function setInstance($instance) {
        $reflectionClass = new ReflectionClass($instance);
        $this->instances[$reflectionClass->getName()] = $instance;
    }

    /**
     * @param $name
     * @param callable $resolver
     */
    public function registerService($name, callable $resolver) {
        if(!isset($this->registries[$name])) {
            $this->registries[$name] = $resolver;
        }
    }

    /**
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public function getService($name) {
        if (!isset($this->instances[$name])) {
            $this->instances[$name] = isset($this->registries[$name])
                ? $this->registries[$name]()
                : $this->resolve($name);
        }

        return $this->instances[$name];
    }

    /**
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public function __get($name) {
        return $this->getService($name);
    }

    /**
     * @param $name
     * @param callable $resolver
     */
    public function __set($name, callable $resolver) {
        $this->registerService($name, $resolver);
    }

    /**
     * @param $class
     * @return object
     * @throws \Exception
     */
    private function resolve($class) {
        $reflectionClass = new ReflectionClass($class);

        if(!$reflectionClass->isInstantiable()) {
            throw new RuntimeException("$class is not an instantiable Class.");
        }

        if (!$constructor = $reflectionClass->getConstructor()) {
            return $reflectionClass->newInstanceWithoutConstructor();
        }

        $params = $constructor->getParameters();

        if (count($params) === 0) {
            return $reflectionClass->newInstance();
        }

        $newInstanceParams = [];
        /** @var ReflectionParameter $param */
        foreach ($params as $param) {
            if (is_null($param->getClass())) {
                $newInstanceParams[] = $param->isDefaultValueAvailable() ? $param->getDefaultValue() : null;
                continue;
            }

            $newInstanceParams[] = $this->getService($param->getClass()->getName());
        }

        return $reflectionClass->newInstanceArgs(
            $newInstanceParams
        );
    }

}
