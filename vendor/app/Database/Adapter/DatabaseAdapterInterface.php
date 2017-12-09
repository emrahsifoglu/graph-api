<?php

namespace App\Vendor\Database\Adapter;

interface DatabaseAdapterInterface
{

    public function connect();

    public function disconnect();

    public function fetch($fetchStyle = null, $cursorOrientation = null, $cursorOffset = null);

    public function fetchAll($fetchStyle = null, $column = 0);

    public function select($table, $bind = [], $boolOperator = "AND", $orderBy = null);

    public function insert($table, $bind = []);

    public function update($table, $bind = [], $where = []);

    public function delete($table, $bind = [], $boolOperator = "AND");

    /**
     * @return \PDOStatement
     */
    public function getStatement();

    public function getDriver();

    public function getLastInsertId($name = null);

    public function getRowCount();

}
