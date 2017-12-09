<?php

namespace App\GraphQL\Type;

use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Config\Object\ObjectTypeConfig;

class TickType extends AbstractObjectType
{

    /**
     * @param ObjectTypeConfig $config
     */
    public function build($config)
    {
        $config->addFields([
            'id' => new NonNullType(new IdType()),
            'title' => new StringType(),
            'name' => new StringType(),
            'data' => new IntType(),
            'backgroundColor' => new StringType(),
            'backgroundColorOpacity' => new StringType(),
            'borderColor' => new StringType(),
            'borderColorOpacity' => new StringType(),
            'borderWidth' => new IntType(),
        ]);
    }
}
