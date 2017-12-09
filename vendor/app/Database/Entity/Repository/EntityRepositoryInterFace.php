<?php

namespace App\Vendor\Database\Entity\Repository;

interface EntityRepositoryInterFace
{

    public function findById($id);
    public function findAll($conditions = []);
    public function create(array $entity);
    public function delete($id);

}
