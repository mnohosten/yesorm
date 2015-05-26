<?php

namespace YesORM\Creator;

abstract class AObject {

    protected $column;
    protected $methodMap = [];

    function __construct(array $column=[]) {
        $this->column = $column;
    }

    function __call($name, $args=[]) {
        if(isset($this->methodMap[$name])) {
            return $this->column[$this->methodMap[$name]];
        }
    }

    function renderMethodsDoc() {
        $str = "";
        foreach ($this->methodMap as $methodName=>$rowName) {
            $str .= " * @method {$methodName}" . PHP_EOL;
        }
        echo $str;
    }

    function renderMethodMap() {
        $map = [];
        foreach ($this->column as $k=>$v) {
            $map[$this->underscoreToCamelCase(mb_strtolower($k))] = $k;
        }
        var_export($map);
    }

    function underscoreToCamelCase( $string, $first_char_caps = false) {
        if( $first_char_caps == true ) {
            $string[0] = strtoupper($string[0]);
        }
        $func = create_function('$c', 'return strtoupper($c[1]);');
        return preg_replace_callback('/_([a-z])/', $func, $string);
    }

    /**
     * @return \ReflectionClass
     */
    static function reflection() {
        return new \ReflectionClass(get_called_class());
    }

    /**
     * @return string
     */
    static function className() {
        return self::reflection()->getName();
    }

}