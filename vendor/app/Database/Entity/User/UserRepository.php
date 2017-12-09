<?php

namespace App\Vendor\Database\Entity\User;

use App\Entity\User\User;
use App\Vendor\Database\Entity\EntityInterface;
use App\Vendor\Database\Entity\Repository\EntityRepository;

class UserRepository extends EntityRepository
{

    protected $entityTable = "user";
    protected $entityClass = "App\Entity\User\User";

    /**
     * @param $email
     * @return EntityInterface|User|null
     */
    public function findByEmail($email) {
        $this->adapter->select($this->entityTable, ['email' => $email]);
        if (!$row = $this->adapter->getStatement()->fetchObject($this->entityClass)) {
            return null;
        }

        return $row;
    }

    public function create(array $entity) {
        $storedHash = password_hash(hash('sha512', $entity['plain_password'], true), PASSWORD_BCRYPT);
        unset($entity['plain_password']);
        $entity['password'] = $storedHash;
        $entity['id'] = parent::create($entity);
        return $entity;
    }

    public function update(array $entity, $id) {
        return parent::update($entity, $id);
    }

}

