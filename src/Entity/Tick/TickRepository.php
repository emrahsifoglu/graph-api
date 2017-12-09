<?php

namespace App\Entity\Tick;

use App\Vendor\Database\Entity\Repository\EntityRepository;

class TickRepository extends EntityRepository
{

    protected $entityTable = "tick";
    protected $entityClass = "App\Entity\Tick\Tick";

}
