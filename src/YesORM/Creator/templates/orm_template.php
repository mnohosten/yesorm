<?php
/**
 * @var $ns string
 * @var $tables \YesORM\Creator\Table[]
 */

echo <<<PHP
<?php

namespace {$ns};

/**
 * Fake ORM class for IDE suggestion\n
PHP;

foreach($tables as $table) {
    echo <<<PHP
 * @method {$table->name()}[]|{$table->name()} {$table->name()}\n
PHP;
}

echo <<<PHP
 */
class ORM extends \YesORM\ORM {}

PHP;

