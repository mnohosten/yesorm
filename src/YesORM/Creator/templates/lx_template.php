<?php
/**
 * @var $ns string
 * @var $tables \YesORM\Creator\Table[]
 */

echo <<<PHP
<?php

namespace {$ns};

/**
 * Lexer class for IDE suggestion\n
PHP;

foreach($tables as $table) {
    echo <<<PHP
 * @property {$table->name()} {$table->name()}\n
PHP;
}

echo <<<PHP
 */
class Lexer extends \YesORM\LexicalHelper {}

PHP;

