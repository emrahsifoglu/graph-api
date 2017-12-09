<?php

namespace App\GraphQL\Schema;

use Youshido\GraphQL\Schema\AbstractSchema;
use Youshido\GraphQL\Config\Schema\SchemaConfig;
use Youshido\GraphQL\Type\ListType\ListType;
use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQL\Type\NonNullType;
use App\GraphQL\Type\TickType;
use App\Entity\Tick\Tick;
use App\Entity\Tick\TickFacade;

class TickSchema extends AbstractSchema
{

    /** @var TickFacade */
    public $tickFacade;

    /**
     * TickSchema constructor.
     * @param TickFacade $tickFacade
     */
    public function __construct(
        TickFacade $tickFacade
    ) {
        parent::__construct();
        $this->tickFacade = $tickFacade;
    }

    /**
     * @param SchemaConfig $config
     */
    public function build(SchemaConfig $config) {
        $config->getQuery()->addFields([
            'tick' => [
                'type' => new TickType(),
                'args' => [
                    'id' => new NonNullType(new IdType())
                ],
                'resolve' => function ($source, $args) {
                    return $this->tickFacade->getTickFromId($args['id']);
                }
            ],
            'allTicks' => [
                'type' => new ListType(new TickType()),
                'resolve' => function () {
                    return $this->tickFacade->getAllTicks();
                }
            ],
        ]);

        $config->getMutation()->addFields([
            'addTick' => [
                'type' => new IntType(),
                'args' => [
                    'title' => new NonNullType(new StringType()),
                    'name' => new NonNullType(new StringType()),
                    'data' => new NonNullType(new IntType()),
                    'backgroundColor' => new NonNullType(new StringType()),
                    'backgroundColorOpacity' => new NonNullType(new StringType()),
                    'borderColor' => new NonNullType(new StringType()),
                    'borderColorOpacity' => new NonNullType(new StringType()),
                    'borderWidth' => new NonNullType(new IntType()),
                ],
                'resolve' => function ($source, $args) {
                    $tick = $this->tickFacade->createFromParams($args);

                    if ($tick instanceof Tick) {
                        $this->tickFacade->save($tick);                        
                        return $tick->getId();
                    }

                    return 0;
                },
            ]
        ]);
    }
}
