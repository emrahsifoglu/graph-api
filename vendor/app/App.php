<?php

namespace App\Vendor;

use App\Vendor\Container\Container;
use App\Vendor\Container\ContainerInterface;
use App\Vendor\Database\Exception\MySqlRepositoryException;
use App\Vendor\Http\Request\RequestFacade;
use App\Vendor\Router\Route;
use App\Vendor\Router\RouteList;
use App\Vendor\Router\Router;
use App\Vendor\Router\Uri\Uri;
use Exception;

class App {

    /** @var ContainerInterface */
    protected $container;

    /** @var Router */
    protected $router;

    /** @var RequestFacade */
    protected $requestFacade;

    /**
     * App constructor.
     *
     * @param array $container
     */
	public function __construct(
	    $container = []
    ) {
        $this->setContainer($container);
        $this->post('/login_check', 'Authenticator:login');
        $this->get('/logout', 'Authenticator:logout');
        $this->requestFacade = $this->container->getService('App\Vendor\Http\Request\RequestFacade');
        $this->container->getService('App\Vendor\Http\Session\Session')->start();
	}

    /**
     * @return ContainerInterface
     */
	public function getContainer() {
		return $this->container;
	}

    /**
     * @param $container
     */
	public function setContainer($container) {
        if (is_array($container)) {
            $container = new Container($container);
        }

        if (!$container instanceof ContainerInterface) {
            throw new \InvalidArgumentException('Expected a ContainerInterface');
        }

        $this->container = $container;
	}

    /**
     * @return Router
     */
	public function getRouter() {
	    return $this->container->getService('App\Vendor\Router\Router');
    }

    /**
	 * @return RouteList
	 */
	public function getRouteList(){
		return $this->getRouter()->getRouteList();
	}

	/**
	 * @param RouteList $routeList
	 */
	public function setRouteList(RouteList $routeList) {
		$this->getRouter()->setRouteList($routeList);
	}

	/**
	 * @param array $httpMethods
	 * @param string $pattern
	 * @param mixed $callable
	 * @return void
	 */
	public function addRoute($httpMethods = [], $pattern, $callable) {
		$route = new Route($httpMethods, new Uri($pattern), $callable);
		$this->getRouter()->addRoute($route);
	}

	/**
	 * @param $pattern
	 * @param $callable
	 */
	public function get($pattern, $callable) {
		$this->addRoute(['GET'], $pattern, $callable);
	}

	/**
	 * @param $pattern
	 * @param $callable
	 */
	public function post($pattern, $callable) {
		$this->addRoute(['POST'], $pattern, $callable);
	}

	/**
	 * @param $pattern
	 * @param $callable
	 */
	public function put($pattern, $callable) {
		$this->addRoute(['PUT'], $pattern, $callable);
	}

	/**
	 * @param $pattern
	 * @param $callable
	 */
	public function delete($pattern, $callable) {
		$this->addRoute(['DELETE'], $pattern, $callable);
	}

    public function run() {
	    try {
            $request = $this->requestFacade->getRequest();

            $route = $this->getRouter()->getCurrentRouteFromRequest($request);
            if($route->isCallable()) {
                return $route->runCallable();
            }

            list($controller, $action) = explode(':',  $route->getCallable());

            $name = 'App\Controller\\'. $controller . 'Controller';
            if ($controller == 'Authenticator') {
                $name = "App\Vendor\Security\Auth\Authenticator";
            }

            $method = $action . 'Action';
            $controller = $this->container->getService($name);

            return $controller->{$method}(...$route->getParams());
        } catch (MySqlRepositoryException $exception) {
            die($exception->getMessage());
        } catch (Exception $exception) {
            die($exception->getMessage());
        }
	}

}
