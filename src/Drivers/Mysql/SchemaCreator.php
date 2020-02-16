<?php

namespace Gouguoyin\MigrationsCreator\Drivers\Mysql;

use Gouguoyin\MigrationsCreator\Drivers\AbstractSchemaCreator;
use Illuminate\Support\Facades\DB;

class SchemaCreator extends AbstractSchemaCreator
{
    public function __construct($connection)
    {
        $this->connection = DB::connection($connection)->getDoctrineConnection();
        $this->database = $this->connection->getDatabase();
        $this->schema   = $this->connection->getSchemaManager();
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
        return $this->getFieldCreator($table)->getFields();
    }

    /**
     * 获取指定表的所有索引
     * @param $table
     * @return array
     */
    public function getIndexes($table)
    {
        return $this->getIndexCreator($table)->getIndexes();
    }

    /**
     * 获取指定表的所有外键
     * @param $table
     * @return array
     */
    public function getForeignKeys($table)
    {
        return $this->getForeignKeyCreator($table)->getForeignKeys();
    }

    /**
     * 获取创建语句
     * @param $table
     * @return string
     */
    public function getCreateStatements($table)
    {
        $statements = PHP_EOL;
        if($statement1 = $this->getFieldCreator($table)->getCreateStatements()){
            $statements .= $statement1 . PHP_EOL;
        }
        if($statement2 = $this->getIndexCreator($table)->getCreateStatements()){
            $statements .= $statement2 . PHP_EOL;
        }
        if($statement3 = $this->getForeignKeyCreator($table)->getCreateStatements()){
            $statements .= $statement3 . PHP_EOL;
        }
        return rtrim($statements, PHP_EOL) . PHP_EOL;
    }

    /**
     * 获取字段生成器对象
     * @param $table
     * @return FieldCreator
     */
    private function getFieldCreator($table)
    {
        return new FieldCreator($this->database, $table);
    }

    /**
     * 获取索引生成器对象
     * @param $table
     * @return IndexCreator
     */
    private function getIndexCreator($table)
    {
        return new IndexCreator($this->schema, $table);
    }

    /**
     * 获取外键生成器对象
     * @param $table
     * @return ForeignKeyCreator
     */
    private function getForeignKeyCreator($table)
    {
        return new ForeignKeyCreator($this->schema, $table);
    }

}