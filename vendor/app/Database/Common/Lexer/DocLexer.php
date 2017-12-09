<?php

namespace App\Vendor\Database\Common\Lexer;

use ReflectionMethod;
use ReflectionProperty;

class DocLexer
{

    public static function getMethodAnnotations($class, $mutator) {
        return self::parseMethodDocComment($class, $mutator)[2];
    }

    public static function getPropertyAnnotations($class, $name) {
        return self::parsePropertyDocComment($class, $name)[2];
    }

    public static function parseMethodDocComment($class, $mutator) {
        $method = new ReflectionMethod($class, $mutator);
        $pattern = "/(@[a-zA-Z]+)\s*(\w+)\s*(\\$[a-zA-Z]+)/";

        preg_match_all($pattern,  $method->getDocComment(), $matches, 2);

        return $matches[0];
    }

    public static function parsePropertyDocComment($class, $name) {
        $property = new ReflectionProperty($class, $name);
        $pattern = "/(@[a-zA-Z]+)\s*(\w+)/";

        preg_match_all($pattern,  $property->getDocComment(), $matches, 2);

        return $matches[0];
    }

    public static function getProperty($field)  {
        return lcfirst(str_replace('_','', ucwords($field,'_')));
    }

}
