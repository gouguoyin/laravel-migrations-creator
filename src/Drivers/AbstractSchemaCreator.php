<?php

namespace Gouguoyin\MigrationsCreator\Drivers;

abstract class AbstractSchemaCreator
{
    protected $database;
    protected $schema;

    /**
     * 判断指定表是否存在
     * @param $table
     * @return mixed
     */
    abstract public function hasTable($table);

    /**
     * 获取所有表
     * @return mixed
     */
    abstract public function getTables();

    /**
     * 获取指定表引擎
     * @param $table
     * @return mixed
     */
    abstract public function getEngine($table);

    /**
     * 获取指定表注释
     * @param $table
     * @return mixed
     */
    abstract public function getComment($table);

    /**
     * 获取指定表字符集
     * @param $table
     * @return mixed
     */
    abstract public function getCollation($table);

    /**
     * 获取指定表自增值
     * @param $table
     * @return mixed
     */
    abstract public function getAutoIncrement($table);

    /**
     * 获取指定表的所有字段
     * @param $table
     * @return mixed
     */
    abstract public function getFields($table);

    /**
     * 获取指定表的所有索引
     * @param $table
     * @return mixed
     */
    abstract public function getIndexes($table);

    /**
     * 获取指定表的所有外键
     * @param $table
     * @return mixed
     */
    abstract public function getForeignKeys($table);

    /**
     * 获取指定表创建语句
     * @param $table
     * @return mixed
     */
    abstract public function getCreateStatements($table);
}