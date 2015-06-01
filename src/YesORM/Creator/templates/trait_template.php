<?php
/**
 * @var $ns string
 * @var $table \YesORM\Creator\Table
 */

echo <<<PHP
<?php

namespace {$ns}\\traits;

PHP;

echo <<<PHP

trait {$table->name()} {

}

PHP;

