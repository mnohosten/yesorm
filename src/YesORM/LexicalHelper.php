<?php
/**
 * Created by PhpStorm.
 * User: martinkrizan
 * Date: 30.05.15
 * Time: 14:13
 */

namespace YesORM;


class LexicalHelper extends \ArrayIterator {

    const SUFFIX_TABLE = '__';
    const SUFFIX_JOIN = '___';

    const SUFFIX_W_TABLE = '.';
    const SUFFIX_W_JOIN = ':';


    public $container = [];

    function __get($name) {
        $this->container[] = $name . self::SUFFIX_TABLE;
        return $this;
    }

    function __call($name, $args=[]) {
        $this->container[] = $name . self::SUFFIX_JOIN;
        return $this;
    }

    function offsetGet($name) {
        $this->container[] = $name;
        return $this;
    }

    function __toString() {
        return $this->_w();
    }

    function container() {
        return $this->container;
    }

    function _s() {
        return implode('', $this->container);
    }

    function _w() {
        $where = [];
        foreach ($this->container as $i=>$v) {
            $where[$i] = str_replace(self::SUFFIX_JOIN, self::SUFFIX_W_JOIN, $v);
            $where[$i] = str_replace(self::SUFFIX_TABLE, self::SUFFIX_W_TABLE, $where[$i]);
        }
        return implode('', $where);
    }

}