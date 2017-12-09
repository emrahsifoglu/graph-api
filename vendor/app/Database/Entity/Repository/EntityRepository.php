<?php

namespace App\Vendor\Database\Entity\Repository;

use App\Vendor\Database\Adapter\DatabaseAdapterInterface;
use App\Vendor\Database\Entity\EntityInterface;
use PDO;

abstract class EntityRepository implements EntityRepositoryInterFace
{

    protected $adapter;
    protected $entityTable;
    protected $entityClass;

    /**
     * EntityRepository constructor.
     * @param DatabaseAdapterInterface $adapter
     */
    public function __construct(
        DatabaseAdapterInterface $adapter
    ) {
        $this->adapter = $adapter;
    }

    /**
     * @return mixed
     */
    public function getEntityTable() {
        return $this->entityTable;
    }

    /**
     * @param mixed $entityTable
     */
    public function setEntityTable($entityTable) {
        $this->entityTable = $entityTable;
    }

    /**
     * @return mixed
     */
    public function getEntityClass() {
        return $this->entityClass;
    }

    /**
     * @param mixed $entityClass
     */
    public function setEntityClass($entityClass) {
        $this->entityClass = $entityClass;
    }

    /**
     * @return DatabaseAdapterInterface
     */
    public function getAdapter() {
        return $this->adapter;
    }

    /**
     * @param $id
     * @return EntityInterface|null
     */
    public function findById($id) {
        $this->adapter->select($this->entityTable, ['id' => $id]);
        if (!$row = $this->adapter->getStatement()->fetchObject($this->entityClass)) {
            return null;
        }

        return $row;
    }

    /**
     * @param array $conditions
     * @return array
     */
    public function findAll($conditions = []) {
        $entities = [];
        $this->adapter->select($this->entityTable, $conditions);
        $rows = $this->adapter->getStatement()->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->entityClass);

        if (!$rows) {
            return $entities;
        }

        foreach ($rows as $row) {
            $entities[] = $row;
        }

        return $entities;
    }

    /**
     * @param array $entity
     * @return int
     */
    public function create(array $entity) {
        return $this->adapter->insert($this->entityTable, $entity);
    }

    /**
     * @param $id
     * @return int
     */
    public function delete($id) {
        return $this->adapter->delete($this->entityTable, ['id' => $id]);
    }

    /**
     * @param array $entity
     * @param $id
     * @return int
     */
    public function update(array $entity, $id) {
        return $this->adapter->update($this->entityTable, $entity, ['id' => $id]);
    }

}
