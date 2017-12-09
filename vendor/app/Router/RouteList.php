<?php

namespace App\Vendor\Router;

use App\Vendor\Collection\Collection;
use App\Vendor\Router\Uri\Uri;

class RouteList extends Collection {

    public function getRoute(Uri $uri, $httpMethod) {
        /** @var Route $route */
        foreach ($this->toArray() as $route) {
            if ($route->isMatched($uri, $httpMethod, $params)) {
                $route->getUri()->setParams($params);
                return $route;
                break;
            }
        }

        return false;
    }

    public function addRoute(Route $route) {
        $this->append($route);
    }

}
