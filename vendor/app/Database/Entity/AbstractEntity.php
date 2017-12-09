<?php

namespace App\Vendor\Database\Entity;

use App\Vendor\Collection\CollectionInterface;
use App\Vendor\Database\Common\Lexer\DocLexer;
use InvalidArgumentException;

abstract class AbstractEntity implements EntityInterface, CollectionInterface
{

    public function __set($field, $value) {
        $property = DocLexer::getProperty($field);

        $mutator = "set" . ucfirst($property);

        if (!property_exists($this, $property)) {
            throw new InvalidArgumentException("Getting the field '$property' is not valid for this entity.");
        }

        if ($type = DocLexer::getPropertyAnnotations($this, $property)) {
            switch ($type) {
                case 'DateTime':
                    $value = new \DateTime($value);
                    break;
            }
        }

        method_exists($this, $mutator) && is_callable([$this, $mutator])
            ? $this->$mutator($value)
            : $this->$property = $value;

        return $this;
    }

    public function __get($field) {
        $property = DocLexer::getProperty($field);

        if (!property_exists($this, $field)) {
            throw new InvalidArgumentException("Getting the field '$property' is not valid for this entity.");
        }

        $accessor = "get" . ucfirst($property);

        return method_exists($this, $accessor) && is_callable(array($this, $accessor))
            ? $this->$accessor()
            : $this->$property;
    }

    public function getArrayCopy() {
        return get_object_vars($this);
    }

}
