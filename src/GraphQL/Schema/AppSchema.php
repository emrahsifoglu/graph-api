<?php

namespace App\GraphQL\Schema;

use Youshido\GraphQL\Schema\AbstractSchema;
use Youshido\GraphQL\Config\Schema\SchemaConfig;
use Youshido\GraphQL\Type\Scalar\StringType;

class AppSchema extends AbstractSchema
{
    public function build(SchemaConfig $config)
    {
        $config->getQuery()->addFields([
            'hello' => [
                'type'    => new StringType(),
                'args'    => [
                    'name' => [
                        'type' => new StringType(),
                        'defaultValue' => 'Stranger'
                    ]
                ],
                'resolve' => function ($context, $args) {
                    return 'Hello ' . $args['name'];
                }
            ]
        ]);
    }
}
