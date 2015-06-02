<?php

namespace YesORM;

/** @property \YesORM\Result $result */
class Row extends \NotORM_Row {

    function offsetGet($key) {
        return parent::offsetGet($key);
    }

    public static function create(ORM $db, $where=[]) {
        return self::reflection()
            ->newInstanceArgs([
                $where,
                new Result(
                    self::table(),
                    $db,
                    false,
                    $where
                )
            ]);
    }

    /** Get referenced row
     * @param string
     * @return NotORM_Row or null if the row does not exist
     */
    function __get($name) {
        $this->result->notORM->__updateRowClass($name);
        $column = $this->result->notORM->structure->getReferencedColumn($name, $this->result->table);
        $referenced = &$this->result->referenced[$name];
        if (!isset($referenced)) {
            $keys = array();
            foreach ($this->result->rows as $row) {
                if ($row[$column] !== null) {
                    $keys[$row[$column]] = null;
                }
            }
            if ($keys) {
                $table = $this->result->notORM->structure->getReferencedTable($name, $this->result->table);
                $referenced = new Result($table, $this->result->notORM);
                $referenced->where("$table." . $this->result->notORM->structure->getPrimary($table), array_keys($keys));
            } else {
                $referenced = array();
            }
        }
        if (!isset($referenced[$this[$column]])) { // referenced row may not exist
            return null;
        }
        return $referenced[$this[$column]];
    }

    /** Get referencing rows
     * @param string table name
     * @param array (["condition"[, array("value")]])
     * @return MultiResult
     */
    function __call($name, array $args) {
        $this->result->notORM->__updateRowClass($name);

        $table = $this->result->notORM->structure->getReferencingTable($name, $this->result->table);
        $column = $this->result->notORM->structure->getReferencingColumn($table, $this->result->table);
        $return = new MultiResult($table, $this->result, $column, $this[$this->result->primary]);
        $return->where("$table.$column", array_keys((array) $this->result->rows)); // (array) - is null after insert
        if ($args) {
            call_user_func_array(array($return, 'where'), $args);
        }
        return $return;
    }

    function __toArray() {
        return iterator_to_array($this);
    }

    function __ORM() {
        return $this->result->notORM;
    }

    static function reflection() {
        return new \ReflectionClass(get_called_class());
    }

    static function className() {
        return self::reflection()->getName();
    }

    static function table() {
        $exp = explode('\\', get_called_class());
        return end($exp);
    }

    public function __rowClass(){
        return $this->result->rowClass;
    }

    function save($data=[], $updateResult=true) {
        if(array_key_exists($this->result->primary, $data) && !$data[$this->result->primary]) {
            unset($data[$this->result->primary]);
        }
        foreach ($data as $k=>$v) {
            $this->offsetSet($k, $v);
        }
        if(isset($this[$this->result->primary])) { // update
            $this->update();
        } else { // insert
            $item = $this->__ORM()->{$this->result->table}()->insert($this->__toArray());
            if($item) {
                foreach ($item as $k=>$v) {
                    $this->offsetSet($k, $v);
                }
                if($updateResult) {
                    $item = $this->__ORM()
                        ->{$this->result->table}([$this->result->primary => $item[$this->result->primary]])
                        ->limit(1)->fetch();
                    if($item) {
                        $this->result = $item->__result();
                    }
                }
            }
        }
        return $this;
    }

    function __result() {
        return $this->result;
    }
}