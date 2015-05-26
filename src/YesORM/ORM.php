<?php

namespace YesORM;

class ORM extends \NotORM
{

    /** @var string */
    protected $ns, $libPath;
    protected $creator;

    /** Get table data
     * @param string
     * @param array (["condition"[, array("value")]]) passed to NotORM_Result::where()
     * @return Result
     */
    function __call($table, array $where) {
        $this->__updateRowClass($table);
        $return = new Result($this->structure->getReferencingTable($table, ''), $this);
        if ($where) {
            call_user_func_array(array($return, 'where'), $where);
        }
        return $return;
    }

    function __get($table) {
        return new Result($this->structure->getReferencingTable($table, ''), $this, true);
    }

    function __updateRowClass($table) {
        $this->rowClass = trim(implode('\\', [$this->getNs(), $table]), '\\');
    }

    /**
     * @param $ns
     * @return $this
     */
    public function setNs($ns)
    {
        $this->ns = $ns;
        return $this;
    }

    /**
     * @return string
     */
    public function getNs()
    {
        return $this->ns;
    }

    /**
     * @param $libPath
     * @return $this
     */
    public function setLibPath($libPath)
    {
        $this->libPath = $libPath;
        return $this;
    }

    /**
     * @return string
     */
    public function getLibPath()
    {
        return $this->libPath;
    }

    /**
     * @return \PDO
     */
    public function __connection()
    {
        return $this->connection;
    }

    public function __creator()
    {
        if(!isset($this->creator)) {
            $this->creator = new Creator($this);
        }
        return $this->creator;
    }



}