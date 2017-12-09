<?php

namespace App\Vendor\Database\Adapter\PDO;

use App\Vendor\Database\Adapter\DatabaseAdapterInterface;
use App\Vendor\Database\Exception\RepositoryException;
use PDO;
use PDOException;
use PDOStatement;

class PDOAdapter implements DatabaseAdapterInterface
{

    /** @var array  */
    protected $config;

    /** @var PDO */
    protected $driver;

    /** @var PDOStatement */
    protected $statement;

    /** @var int  */
    protected $fetchMode = PDO::FETCH_ASSOC;

    public function __construct(
        $dsn,
        $username = null,
        $password = null,
        $driverOptions = []
    ) {
        $this->config = compact("dsn", "username", "password", "driverOptions");
    }

    /**
     * @return PDO
     */
    public function getDriver() {
        return $this->driver;
    }

    /**
     * @return PDOStatement
     */
    public function getStatement() {
        if (is_null($this->statement)) {
            throw new PDOException("There is no PDOStatement object for use.");
        }
        return $this->statement;
    }

    public function connect() {
        if ($this->driver) {
            return;
        }

        try {
            $this->driver = new PDO(
                $this->config["dsn"],
                $this->config["username"],
                $this->config["password"],
                $this->config["driverOptions"]
            );

            $this->driver->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->driver->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }
        catch (PDOException $e) {
            throw new RepositoryException($e->getMessage());
        }
    }

    public function disconnect() {
        $this->driver = null;
    }

    public function prepare($sql, $options = []) {
        $this->connect();
        try {
            $this->statement = $this->driver->prepare($sql, $options);
            return $this;
        }
        catch (PDOException $e) {
            throw new RepositoryException($e->getMessage());
        }
    }

    public function execute($parameters = []) {
        try {
            $this->getStatement()->execute($parameters);
            return $this;
        }
        catch (PDOException $e) {
            throw new RepositoryException($e->getMessage());
        }
    }

    public function fetch($fetchStyle = null, $cursorOrientation = null, $cursorOffset = null) {
        $fetchStyle = $fetchStyle ?: $this->fetchMode;
        try {
            return $this->getStatement()->fetch($fetchStyle, $cursorOrientation, $cursorOffset);
        } catch (PDOException $e) {
            throw new RepositoryException($e->getMessage());
        }
    }

    public function fetchAll($fetchStyle = null, $column = 0) {
        $fetchStyle = $fetchStyle ?: $this->fetchMode;

        try {
            return $fetchStyle === PDO::FETCH_COLUMN
                ? $this->getStatement()->fetchAll($fetchStyle, $column)
                : $this->getStatement()->fetchAll($fetchStyle);
        } catch (PDOException $e) {
            throw new RepositoryException($e->getMessage());
        }
    }

    public function select($table, $bind = [], $boolOperator = "AND", $orderBy = null) {
        $where = [];
        if ($bind) {
            foreach ($bind as $col => $value) {
                unset($bind[$col]);
                $bind[":" . $col] = $value;
                $where[] = $col . " = :" . $col;
            }
        }

        $sql = "SELECT * FROM " . $table . (($bind) ? " WHERE " . implode(" " . $boolOperator . " ", $where) : " ") . (($orderBy) ? " ORDER BY " . $orderBy : " ");
        $this->prepare($sql)->execute($bind);

        return $this;
    }

    public function insert($table, $bind = []) {
        $cols = implode(", ", array_keys($bind));
        $values = implode(", :", array_keys($bind));
        foreach ($bind as $col => $value) {
            unset($bind[$col]);
            $bind[":" . $col] = $value;
        }

        $sql = "INSERT INTO " . $table . " (" . $cols . ")  VALUES (:" . $values . ")";

        return (int) $this->prepare($sql)->execute($bind)->getLastInsertId();
    }

    public function update($table, $bind = [], $where = [], $boolOperator = 'AND') {
        $set = [];
        foreach ($bind as $col => $value) {
            unset($bind[$col]);
            $bind[":" . $col] = $value;
            $set[] = $col . " = :" . $col;
        }

        foreach ($where as $col => $value) {
            unset($where[$col]);
            $bind[":" . $col] = $value;
            $where[] = $col . " = :" . $col;
        }

        $sql = "UPDATE " . $table . " SET " . implode(", ", $set) . (($where) ? " WHERE " . implode(" " . $boolOperator . " ", $where) : " ");

        return $this->prepare(trim($sql))->execute($bind)->getRowCount();
    }

    public function delete($table, $bind = [], $boolOperator = 'AND') {
        $where = [];
        foreach ($bind as $col => $value) {
            unset($bind[$col]);
            $bind[":" . $col] = $value;
            $where[] = $col . " = :" . $col;
        }

        $sql = " DELETE FROM " . $table . (($bind) ? " WHERE " . implode(" " . $boolOperator . " ", $where) : " ");

        return $this->prepare(trim($sql))->execute($bind)->getRowCount();
    }

    public function getRowCount() {
        try {
            return $this->getStatement()->rowCount();
        }
        catch (PDOException $e) {
            throw new RepositoryException($e->getMessage());
        }
    }

    public function getLastInsertId($name = null) {
        $this->connect();
        return $this->driver->lastInsertId($name);
    }

    /**
     * @return PDOAdapter
     */
    public function commit() {
        $this->driver->commit();
        return $this;
    }

    /**
     * @return PDOAdapter
     */
    public function rollBack(){
        $this->driver->rollBack();
        return $this;
    }

}
