<?php

namespace YesORM\Creator;

/**
 * Class Column
 * @package YesORM\Creator
 * @method tableCatalog
 * @method tableSchema
 * @method tableName
 * @method name
 * @method ordinalPosition
 * @method default
 * @method isNullable
 * @method dataType
 * @method characterMaximumLength
 * @method characterOctetLength
 * @method numericPrecision
 * @method numericScale
 * @method datetimePrecision
 * @method characterSetName
 * @method collationName
 * @method type
 * @method key
 * @method extra
 * @method privileges
 * @method comment
 */
class Column extends AObject {

    protected $methodMap = [
        'tableCatalog' => 'TABLE_CATALOG',
        'tableSchema' => 'TABLE_SCHEMA',
        'tableName' => 'TABLE_NAME',
        'name' => 'COLUMN_NAME',
        'ordinalPosition' => 'ORDINAL_POSITION',
        'default' => 'COLUMN_DEFAULT',
        'isNullable' => 'IS_NULLABLE',
        'dataType' => 'DATA_TYPE',
        'characterMaximumLength' => 'CHARACTER_MAXIMUM_LENGTH',
        'characterOctetLength' => 'CHARACTER_OCTET_LENGTH',
        'numericPrecision' => 'NUMERIC_PRECISION',
        'numericScale' => 'NUMERIC_SCALE',
        'datetimePrecision' => 'DATETIME_PRECISION',
        'characterSetName' => 'CHARACTER_SET_NAME',
        'collationName' => 'COLLATION_NAME',
        'type' => 'COLUMN_TYPE',
        'key' => 'COLUMN_KEY',
        'extra' => 'EXTRA',
        'privileges' => 'PRIVILEGES',
        'comment' => 'COLUMN_COMMENT',
    ];

}
