<?php

namespace App\Vendor\Http\Request;

use App\Vendor\Router\Uri\Uri;

class Request
{

    /** @var Uri */
    private $uri;

    /** @var string */
    private $method;

    /** @var array */
    private $params;

    /** @var bool */
    private $isAjax;

    /**
     * @param Uri $uri
     * @param $method
     * @param array $params
     * @param bool $isAjax
     */
    public function __construct(
        Uri $uri,
        $method,
        $params = [],
        $isAjax = false
    )  {
        $this->uri = $uri;
        $this->method = $method;
        $this->params = $params;
        $this->isAjax = $isAjax;
    }

    /**
     * @return Uri
     */
    public function getUri() {
        return $this->uri;
    }

    /**
     * @param Uri $uri
     */
    public function setUri(Uri $uri) {
        $this->uri = $uri;
    }

    /**
     * @return string
     */
    public function getMethod() {
        return $this->method;
    }

    /**
     * @return array
     */
    public function getParams() {
        return $this->params;
    }

    /**
     * @return bool
     */
    public function isAjax() {
        return $this->isAjax;
    }

    /**
     * @param bool $isAjax
     */
    public function setIsAjax($isAjax) {
        $this->isAjax = $isAjax;
    }

}
