<?php

namespace YesORM;

use YesORM\Creator\ArrayClassIterator;
use YesORM\Creator\Column;
use YesORM\Creator\KeyColumnUsage;
use YesORM\Creator\Table;
use YesORM\Creator\Writer;

class Creator {

    /** @var  \PDO */
    private $pdo;
    private $database;
    private $libPath;
    private $ns;

    function __construct(ORM $orm) {
        $this->pdo = $orm->__connection();
        $this->libPath = $orm->getLibPath();
        $this->ns = $orm->getNs();
        $this->setDatabase();
    }

    /**
     * @return ArrayClassIterator|Table[]
     */
    function getTables() {
        $sql = <<<SQL
select
  c.*
from information_schema.TABLES c
where c.TABLE_SCHEMA = ?
SQL;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$this->getDatabase()]);
        return new ArrayClassIterator(
            (array)$stmt->fetchAll(\PDO::FETCH_ASSOC),
            Table::className()
        );
    }

    /**
     * @param $table
     * @return ArrayClassIterator|Column[]
     */
    function getColumns($table) {
        $sql = <<<SQL
select
  c.*
from information_schema.COLUMNS c
where c.TABLE_SCHEMA = ?
and c.TABLE_NAME = ?
SQL;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$this->getDatabase(), $table]);
        return new ArrayClassIterator(
            (array) $stmt->fetchAll(\PDO::FETCH_ASSOC),
            Column::className()
        );
    }

    /**
     * @param $table
     * @return ArrayClassIterator|KeyColumnUsage[]
     */
    function getReferencingTableKeys($table) {
        $sql = <<<SQL
select c.*
from information_schema.KEY_COLUMN_USAGE c
where c.TABLE_SCHEMA = ?
and c.TABLE_NAME = ?
and c.REFERENCED_COLUMN_NAME is not null
SQL;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$this->getDatabase(), $table]);
        return new ArrayClassIterator(
            (array) $stmt->fetchAll(\PDO::FETCH_ASSOC),
            KeyColumnUsage::className()
        );
    }

    /**
     * @param $table
     * @return ArrayClassIterator|KeyColumnUsage[]
     */
    function getReferencedTableKeys($table) {
        $sql = <<<SQL
select c.*
from information_schema.KEY_COLUMN_USAGE c
where c.TABLE_SCHEMA = ?
and c.REFERENCED_TABLE_NAME = ?
SQL;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$this->getDatabase(), $table]);
        return new ArrayClassIterator(
            (array) $stmt->fetchAll(\PDO::FETCH_ASSOC),
            KeyColumnUsage::className()
        );
    }

    function build() {
        return (new Writer($this))->build();
    }

    private function setDatabase() {
        $this->database = $this->pdo->query('select database()')->fetchColumn();
        if(!$this->database) {
            throw new Exception("There is no selected database.");
        }
    }

    function getDatabase() {
        return $this->database;
    }

    /**
     * @return string
     */
    function getNs() {
        return $this->ns;
    }

    /**
     * @return string
     */
    public function getLibPath()
    {
        return $this->libPath;
    }





}