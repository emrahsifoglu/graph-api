<?php

namespace App\Vendor\Router;

use App\Vendor\Collection\CollectionInterface;
use App\Vendor\Http\Request\Request;
use App\Vendor\Router\Uri\Uri;
use Closure;

class Route implements CollectionInterface
{

    private $httpMethods;
    private $uri;
    private $callable;
    private $request;
	
    public function __construct(
        $httpMethods,
        Uri $uri,
        $callable
    ) {
        $this->httpMethods = $httpMethods;
        $this->uri = $uri;
        $this->callable = $callable;
    }

    /**
     * @return array
     */
    public function getHttpMethods() {
        return $this->httpMethods;
    }

    /**
     * @return Uri
     */
    public function getUri() {
        return $this->uri;
    }

    /**
     * @return string
     */
    public function getUriPattern() {
        return $this->uri->getPattern();
    }

    /**
     * @return array
     */
    public function getQueryParams() {
        return $this->uri->getParams();
    }

    /**
     * @param Request $request
     */
    public function setRequest(Request $request) {
        $this->request = $request;
    }

    /**
     * @return Request
     */
    public function getRequest() {
        return $this->request;
    }

    /**
     * @return array
     */
    public function getRequestParams() {
        return $this->getRequest()->getParams();
    }

    public function getParams() {
        return array_merge([$this->getRequest()], $this->getQueryParams());
    }

    /**
     * @return mixed
     */
    public function getCallable() {
        return $this->callable;
    }

    /**
     * @param Uri $uri
     * @param $httpMethod
     * @param array $params
     * @return array|bool
     */
    public function isMatched(Uri $uri, $httpMethod, &$params = []) {
        $matches = [];
        $pattern = $this->getUriPattern();

        $isMatched =
            in_array($httpMethod, $this->httpMethods) &&
            preg_match("#^$pattern$#", $uri->getPattern(), $matches);

        if ($isMatched) {
            array_shift($matches);
            $params = $matches;
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function runCallable() {
        if ($this->isCallable()) {
            call_user_func_array($this->callable, $this->getParams());
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isCallable() {
        return ($this->callable instanceof Closure);
    }

    /**
     * @return array
     */
    public function getArrayCopy() {
        return get_object_vars($this);
    }
}
