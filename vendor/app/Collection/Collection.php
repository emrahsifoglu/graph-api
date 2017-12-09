<?php

namespace App\Vendor\Collection;

use ArrayObject;

class Collection extends ArrayObject implements CollectionInterface {

    public function __construct($array = []) {
        parent::__construct($array, ArrayObject::ARRAY_AS_PROPS);
    }

    public function toArray() {
        return parent::getArrayCopy();
    }

    public function __ToString() {
        return 'Array';
    }

    public function getArrayCopy() {
        return array_map(function(CollectionInterface $interface) {
            return $interface->getArrayCopy();
        }, parent::getArrayCopy());
    }

}
