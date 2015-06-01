<?php

namespace YesORM;

class Result extends \NotORM_Result {



    protected function query($query, $parameters) {
        $doLog = false;
        if ($this->notORM->debug) {
            if (!is_callable($this->notORM->debug)) {
                $debug = "$query;";
                if ($parameters) {
                    $debug .= " -- " . implode(", ", array_map(array($this, 'quote'), $parameters));
                }
                $pattern = '(^' . preg_quote(dirname(__FILE__)) . '(\\.php$|[/\\\\]))'; // can be static
                foreach (debug_backtrace() as $backtrace) {
                    if (isset($backtrace["file"]) && !preg_match($pattern, $backtrace["file"])) { // stop on first file outside NotORM source codes
                        break;
                    }
                }
                error_log("$backtrace[file]:$backtrace[line]:$debug\n", 0);
            } else {
                $doLog = true;
            }
        }
        $this->notORM->__updateRowClass($this->table);
        if($doLog) $stopWatchStart = microtime(true);
        $return = $this->notORM->connection->prepare($query);
        if (!$return || !$return->execute(array_map(array($this, 'formatValue'), $parameters))) {
            $return = false;
        }
        if($doLog) {
            call_user_func($this->notORM->debug, $query, $parameters, microtime(true) - $stopWatchStart);
        }
        return $return;
    }

    function __toArray($allToArray=false) {
        if($allToArray) {
            return array_map('iterator_to_array', iterator_to_array($this));
        }
        return iterator_to_array($this);
    }

    function iterate($callback, $limit=100) {
        if(isset($this->offset)) {
            $offset = $this->offset;
        } else {
            $offset = 0;
        }
        if(!is_callable($callback)) {
            throw new Exception('Variable callback have to be valid callback.');
        }
        if(isset($this->limit)) {
            $maxLimit = $this->limit;
            if($maxLimit < $limit) {
                $limit = $maxLimit;
            }
        }
        $i = 0;
        do {
            $that = clone($this);
            $that->notORM->__updateRowClass($this->table);
            $items = $that->limit($limit, $offset);
            foreach ($items as $item) {
                call_user_func($callback, $item);
                if(isset($maxLimit) && ++$i == $maxLimit) {
                    return;
                }
            }
            $offset += $limit;
        } while($items->count());
    }

}