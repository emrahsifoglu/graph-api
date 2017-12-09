<?php

namespace App\Vendor\Http\Request;

use App\Vendor\Router\Uri\Uri;

class Request
{

    /** @var Uri */
    private $uri;

    /** @var string */
    private $method;

    /** @var string */
    private $contentType;

    /** @var string */
    private $httpContentType;

    /** @var array */
    private $params;

    /** @var bool */
    private $isAjax;

    /**
     * @param Uri $uri
     * @param string $method
     * @param string $contentType
     * @param string $httpContentType
     * @param array $params
     * @param bool $isAjax
     */
    public function __construct(
        Uri $uri,
        $method,
        $contentType,
        $httpContentType,
        $params = [],
        $isAjax = false
    )  {
        $this->uri = $uri;
        $this->method = $method;
        $this->contentType = $contentType;
        $this->httpContentType = $httpContentType;
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
     * @return string
     */
    public function getContentType() {
        return $this->contentType;
    }

    /**
     * @return string
     */
    public function getHttpContentType() {
        return $this->httpContentType;
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
