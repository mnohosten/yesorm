<?php
/**
 * Created by PhpStorm.
 * User: martinkrizan
 * Date: 21.05.15
 * Time: 10:12
 */

namespace YesORM\Creator;


class ArrayClassIterator extends \ArrayIterator {

    private $class;

    function __construct($array = [], $class=null, $flags = 0) {
        if(!isset($class)) {
            throw new Exception("Class name of Iterator items is not defined.");
        }
        $this->class = $class;
        parent::__construct($array, $flags);
    }

    function current() {
        $item = parent::current();
        return new $this->class($item);
    }

}