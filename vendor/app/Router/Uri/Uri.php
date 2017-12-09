<?php

namespace App\Vendor\Router\Uri;

class Uri {

    /** @var string */
    private $pattern;

    /** @var array */
    private $params;

	public function __construct(
        $pattern
    ) {
        $this->pattern = $pattern;
    }

    /**
     * @return string
     */
    public function getPattern() {
        return $this->pattern;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params) {
        $this->params = $params;
    }

    /**
     * @return array
     */
    public function getParams() {
        return $this->params;
    }
    
}
