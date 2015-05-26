<?php

namespace YesORM\Creator;

/**
 * Class Table
 * @package YesORM\Creator
 * @method catalog
 * @method schema
 * @method name
 * @method type
 * @method engine
 * @method version
 * @method rowFormat
 * @method tableRows
 * @method avgRowLength
 * @method dataLength
 * @method maxDataLength
 * @method indexLength
 * @method dataFree
 * @method autoIncrement
 * @method createTime
 * @method updateTime
 * @method checkTime
 * @method tableCollation
 * @method checksum
 * @method createOptions
 * @method comment
 */
class Table extends AObject {

    protected $methodMap = [
        'catalog' => 'TABLE_CATALOG',
        'schema' => 'TABLE_SCHEMA',
        'name' => 'TABLE_NAME',
        'type' => 'TABLE_TYPE',
        'engine' => 'ENGINE',
        'version' => 'VERSION',
        'rowFormat' => 'ROW_FORMAT',
        'tableRows' => 'TABLE_ROWS',
        'avgRowLength' => 'AVG_ROW_LENGTH',
        'dataLength' => 'DATA_LENGTH',
        'maxDataLength' => 'MAX_DATA_LENGTH',
        'indexLength' => 'INDEX_LENGTH',
        'dataFree' => 'DATA_FREE',
        'autoIncrement' => 'AUTO_INCREMENT',
        'createTime' => 'CREATE_TIME',
        'updateTime' => 'UPDATE_TIME',
        'checkTime' => 'CHECK_TIME',
        'tableCollation' => 'TABLE_COLLATION',
        'checksum' => 'CHECKSUM',
        'createOptions' => 'CREATE_OPTIONS',
        'comment' => 'TABLE_COMMENT',
    ];

}