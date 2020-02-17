<?php

namespace Gouguoyin\MigrationsCreator\Drivers\Mysql;

use Gouguoyin\MigrationsCreator\Drivers\AbstractSchemaCreator;
use Illuminate\Support\Facades\DB;

class SchemaCreator extends AbstractSchemaCreator
{
    public function __construct($connection)
    {
        $connection = DB::connection($connection)->getDoctrineConnection();

        $platform       = $connection->getDatabasePlatform();
        $this->database = $connection->getDatabase();
        $this->schema   = $connection->getSchemaManager();

        $platform->registerDoctrineTypeMapping('json', 'text');
        $platform->registerDoctrineTypeMapping('jsonb', 'text');
        $platform->registerDoctrineTypeMapping('enum', 'string');
        $platform->registerDoctrineTypeMapping('bit', 'boolean');
    }

    /**
     * 判断指定表是否存在
     * @param $table
     * @return bool
     */
    public function hasTable($table)
    {
        return $this->schema->tablesExist([$table]);
    }

    /**
     * 获取指定表引擎
     * @param $table
     * @return mixed
     */
    public function getEngine($table)
    {
        return $this->_getTableCreator($table)->getEngine();
    }

    /**
     * 获取指定表注释
     * @param $table
     * @return mixed
     */
    public function getComment($table)
    {
        return $this->_getTableCreator($table)->getComment();
    }

    /**
     * 获取指定表字符集
     * @param $table
     * @return mixed
     */
    public function getCollation($table)
    {
        return $this->_getTableCreator($table)->getCollation();
    }

    /**
     * 获取指定表自增值
     * @param $table
     * @return mixed
     */
    public function getAutoIncrement($table)
    {
        return $this->_getTableCreator($table)->getAutoIncrement();
    }

    /**
     * 获取所有表
     * @return string[]
     */
    public function getTables()
    {
        return $this->schema->listTableNames();
    }

    /**
     * 获取指定表的所有字段
     * @param $table
     * @return array
     */
    public function getFields($table)
    {
        return $this->_getTableCreator($table)->getFields();
    }

    /**
     * 获取指定表的所有索引
     * @param $table
     * @return array
     */
    public function getIndexes($table)
    {
        return $this->_getTableCreator($table)->getIndexes();
    }

    /**
     * 获取指定表的所有外键
     * @param $table
     * @return array
     */
    public function getForeignKeys($table)
    {
        return $this->_getTableCreator($table)->getForeignKeys();
    }

    /**
     * 获取创建语句
     * @param $table
     * @return string
     */
    public function getCreateStatements($table)
    {
        return $this->_getTableCreator($table)->getCreateStatements();
    }

    public function getCommentStatements($table)
    {
        $statements = PHP_EOL;
        if($comment = $this->getComment($table)){
            $statements .= str_repeat(" ", 8) . "DB::statement(\"ALTER TABLE `{$table}` comment'{$comment}'\");";
        }
        return rtrim($statements, PHP_EOL) . PHP_EOL;
    }

    /**
     * 获取数据表生成器对象
     * @param $table
     * @return TableCreator
     */
    private function _getTableCreator($table)
    {
        return new TableCreator($this->database, $this->schema, $table);
    }

}