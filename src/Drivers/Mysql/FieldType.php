<?php

namespace Gouguoyin\MigrationsCreator\Drivers\Mysql;

class FieldType
{
    /*数字类型*/
    const INTEGER            = 'integer';
    const INT                = 'int';
    const TINYINT            = 'tinyint';
    const SMALLINT           = 'smallint';
    const MEDIUMINT          = 'mediumint';
    const BIGINT             = 'bigint';
    const FLOAT              = 'float';
    const DECIMAL            = 'decimal';
    const NUMERIC            = 'numeric';
    const DOUBLE             = 'double';
    const REAL               = 'real';
    const SERIAL             = 'serial';

    /*字符串类型*/
    const CHAR               = 'char';
    const VARCHAR            = 'varchar';
    const TEXT               = 'text';
    const TINYTEXT           = 'tinytext';
    const MEDIUMTEXT         = 'mediumtext';
    const LONGTEXT           = 'longtext';
    const ENUM               = 'enum';
    const SET                = 'set';

    /*日期时间类型*/
    const DATE               = 'date';
    const TIME               = 'time';
    const YEAR               = 'year';
    const DATETIME           = 'datetime';
    const TIMESTAMP          = 'timestamp';

    /*二进制类型*/
    const BIT                = 'bit';
    const BINARY             = 'binary';
    const VARBINARY          = 'varbinary';
    const BLOB               = 'blob';
    const TINYBLOB           = 'tinyblob';
    const MEDIUMBLOB         = 'mediumblob';
    const LONGBLOB           = 'longblob';

    /*JSON类型*/
    const JSON               = 'json';

    /*特殊类型*/
    const POINT              = 'point';
    const MULTIPOINT         = 'multipoint';
    const LINESTRING         = 'linestring';
    const MULTILINESTRING    = 'multilinestring';
    const POLYGON            = 'polygon';
    const MULTIPOLYGON       = 'multipolygon';
    const GEOMETRY           = 'geometry';
    const GEOMETRYCOLLECTION = 'geometrycollection';

    /**
     * 数字类型常量
     */
    const NUMERAL_TYPES = [
        self::INTEGER,
        self::INT,
        self::TINYINT,
        self::SMALLINT,
        self::MEDIUMINT,
        self::BIGINT,
        self::FLOAT,
        self::DECIMAL,
        self::DOUBLE,
        self::NUMERIC,
        self::REAL,
        self::SERIAL,
    ];

    /**
     * 字符串类型常量
     */
    const STRING_TYPES = [
        self::CHAR,
        self::VARCHAR,
        self::TEXT,
        self::TINYTEXT,
        self::MEDIUMTEXT,
        self::LONGTEXT,
        self::ENUM,
        self::SET,
    ];

    /**
     * 日期时间类型常量
     */
    const DATETIME_TYPES = [
        self::DATE,
        self::TIME,
        self::YEAR,
        self::DATETIME,
        self::TIMESTAMP,
    ];

    /**
     * 二进制类型常量
     */
    const BINARY_TYPES = [
        self::BIT,
        self::BINARY,
        self::VARBINARY,
        self::BLOB,
        self::TINYBLOB,
        self::MEDIUMBLOB,
        self::LONGBLOB,
    ];

    /**
     * 是否是字符串类型
     * @param $type
     * @return bool
     */
    static function isString($type)
    {
        return in_array($type, self::STRING_TYPES);
    }

    /**
     * 是否是日期时间类型
     * @param $type
     * @return bool
     */
    static function isDateTime($type)
    {
        return in_array($type, self::DATETIME_TYPES);
    }

    /**
     * 是否是数字类型
     * @param $type
     * @return bool
     */
    static function isNumeral($type)
    {
        return in_array($type, self::NUMERAL_TYPES);
    }

    /**
     * 是否是二进制类型
     * @param $type
     * @return bool
     */
    static function isBinary($type)
    {
        return in_array($type, self::BINARY_TYPES);
    }

}