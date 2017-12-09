<?php

namespace App\Vendor\Http\Request;

use App\Vendor\Router\Uri\Uri;

class RequestFacade
{

    /**
     * @return string
     */
    public function getRequestMethod() {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @param string $method;
     * @return bool
     */
    public function isRequestMethod($method){
        return ($this->getRequestMethod() === $method) ? true : false;
    }

    /**
     * @param string $method
     * @return array
     */
    public function getRequestParams($method) {
        $params = [];

        switch ($method) {
            case 'GET':
                $params = $_GET;
                break;
            case 'POST':
                $params = $_POST;
                break;
            case 'PUT':
                parse_str(file_get_contents("php://input"), $params);
                break;
        }

        return $params;
    }

    /**
     * @return mixed|string
     */
    public function getRequestUri() {
        if (!isset($_SERVER['REQUEST_URI'])) {
            return '/';
        }

        $uri = $_SERVER['REQUEST_URI'];
        $uri = preg_replace('/([^:])(\/{2,})/', '$1/', $uri);

        return $uri;
    }

    /**
     * @return bool
     */
    public function isAJAX() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    /**
     * @param $string
     * @return bool
     */
    function isJson($string) {
        return ((is_string($string) && (is_object(json_decode($string)) || is_array(json_decode($string))))) ? true : false;
    }

    /**
     * @return Request
     */
    public function getRequest() {
        $method = $this->getRequestMethod();
        $uri = new Uri($this->getRequestUri());

        return new Request($uri, $method, $this->getRequestParams($method), $this->isAJAX());
    }

}
