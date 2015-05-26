<?php
/**
 * Created by PhpStorm.
 * User: martinkrizan
 * Date: 21.05.15
 * Time: 11:26
 */

namespace YesORM\Creator;

/**
 * Class KeyColumnUsage
 * @package YesORM\Creator
 * @method constraintCatalog
 * @method constraintSchema
 * @method constraintName
 * @method tableCatalog
 * @method tableSchema
 * @method tableName
 * @method columnName
 * @method ordinalPosition
 * @method positionInUniqueConstraint
 * @method referencedTableSchema
 * @method referencedTableName
 * @method referencedColumnName
 */
class KeyColumnUsage extends AObject {

    protected $methodMap = [
        'constraintCatalog' => 'CONSTRAINT_CATALOG',
        'constraintSchema' => 'CONSTRAINT_SCHEMA',
        'constraintName' => 'CONSTRAINT_NAME',
        'tableCatalog' => 'TABLE_CATALOG',
        'tableSchema' => 'TABLE_SCHEMA',
        'tableName' => 'TABLE_NAME',
        'columnName' => 'COLUMN_NAME',
        'ordinalPosition' => 'ORDINAL_POSITION',
        'positionInUniqueConstraint' => 'POSITION_IN_UNIQUE_CONSTRAINT',
        'referencedTableSchema' => 'REFERENCED_TABLE_SCHEMA',
        'referencedTableName' => 'REFERENCED_TABLE_NAME',
        'referencedColumnName' => 'REFERENCED_COLUMN_NAME',
    ];

}
