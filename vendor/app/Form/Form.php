<?php

namespace App\Vendor\Form;

use App\Vendor\Database\Entity\EntityInterface;
use App\Vendor\Http\Request\Request;

abstract class Form
{

    /** @var Request */
    protected $request;

    /** @var bool */
    protected $isValid;

    /** @var array */
    protected $errors = [];

    /**
     * @param Request $request
     * @param EntityInterface|null $entity
     * @return mixed
     */
    abstract public function handleRequest(Request $request, EntityInterface &$entity = null);

    public function isSubmitted() {
        return $this->request->getMethod() === 'POST';
    }

    /**
     * @return bool
     */
    public function isValid() {
        return $this->isValid;
    }

    /**
     * @param $error
     */
    protected function addError($error) {
        $this->errors[] = $error;
    }

    /**
     * @return array
     */
    public function getErrors() {
        return $this->errors;
    }

}
