<?php

namespace App\Vendor\Helper;

class ArrayUtils
{

    public static function isIterable($var) {
        return $var !== null
            && (is_array($var)
                || $var instanceof \Traversable
                || $var instanceof \Iterator
                || $var instanceof \IteratorAggregate
            );
    }

}
