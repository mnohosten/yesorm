<?php

/**
 * @var $trait string|null
 * @var $ns string
 * @var $table \YesORM\Creator\Table
 * @var $columns \YesORM\Creator\Column[]
 * @var $referencingKeys \YesORM\Creator\KeyColumnUsage[]
 * @var $referencedKeys \YesORM\Creator\KeyColumnUsage[]
 */

echo <<<PHP
<?php

namespace {$ns};

use \YesORM\Row;

/**
 * DO NOT EDIT THIS FILE! THIS FILE IS GENERATED BY YesORM Creator.
 *  - Your changes will be lost.
 *  - Use Trait instead - create trait file in path: traits/{$table->name()}.php
 * Represents {$table->name()} row in {$table->schema()} database.\n
PHP;


foreach ($referencingKeys as $key) {
    echo <<<PHP
 * @property {$key->referencedTableName()} \${$key->referencedTableName()}\n
PHP;
}

foreach ($referencedKeys as $key) {
    $ref = $key->tableName();
    echo <<<PHP
 * @method {$key->tableName()}[]|{$key->tableName()} {$key->tableName()}\n
PHP;

}

echo <<<PHP
 * @method {$table->name()}|{$table->name()}[] where
 * @method {$table->name()}|{$table->name()}[] or
 * @method {$table->name()}|{$table->name()}[] and
 * @method {$table->name()}|{$table->name()}[] order
 * @method {$table->name()}|{$table->name()}[] limit
 * @method {$table->name()}|{$table->name()}[] group
 * @method {$table->name()}|{$table->name()}[] union
 * @method {$table->name()}|{$table->name()}[] lock
 * @method {$table->name()} fetch
 * @method iterate(\$callback, \$limit=null)
PHP;

if(!$referencedKeys->count() && !$referencingKeys->count()) {
    echo "\n";
}

echo <<<PHP
 */
class {$table->name()} extends Row
{

PHP;

if($trait) {
    echo <<<PHP
    use {$trait};\n\n
PHP;
}

foreach ($columns as $column) {
    echo <<<PHP
    const {$column->name()} = '{$column->name()}';\n
PHP;

}

echo <<<PHP
}\n
PHP;

