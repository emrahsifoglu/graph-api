<?php

namespace App\Vendor\Router;

use App\Vendor\Collection\CollectionInterface;
use App\Vendor\Http\Request\Request;
use Exception;

class Router {

    /** @var RouteList */
	private $routeList;

    /**
     * Router constructor.
     *
     * @param array $routes
     */
	public function __construct(
        $routes = []
    ) {
        $this->setRouteList($routes);
	}

    /**
     * @return RouteList
     */
    public function getRouteList() {
        return $this->routeList;
    }

    /**
     * @param Request $request
     * @return Route|bool
     * @throws Exception
     */
    public function getCurrentRouteFromRequest(Request $request) {
        if ($route = $this->routeList->getRoute($request->getUri(), $request->getMethod())) {
            $route->setRequest($request);
            return $route;
        }

        $uri = $request->getUri()->getPattern();
        throw new Exception("Route {$uri} is not found.");
    }

    /**
     * @param Route $route
     */
    public function addRoute(Route $route) {
		$this->routeList->addRoute($route);
	}

    /**
     * @param $routes
     */
    public function setRouteList($routes) {
        if (is_array($routes)) {
            $routes = new RouteList($routes);
        }

        if (!$routes instanceof CollectionInterface) {
            throw new \InvalidArgumentException('Expected a CollectionInterface');
        }

        $this->routeList = $routes;
    }

}
