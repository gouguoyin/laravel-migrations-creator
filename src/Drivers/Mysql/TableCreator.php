<?php

namespace Gouguoyin\MigrationsCreator\Drivers\Mysql;

use Illuminate\Support\Facades\DB;

class TableCreator
{
    protected $database;
    protected $schema;
    protected $table;
    protected $object;

    public function __construct($database, $schema, $table)
    {
        $this->database = $database;
        $this->schema   = $schema;
        $this->table    = $table;

        $this->object = DB::table('information_schema.tables')
            ->where('table_schema', $this->database)
            ->where('table_name', $this->table)
            ->first();
    }

    /**
     * 判断表是否存在
     * @return bool
     */
    public function hasTable()
    {
        return $this->schema->tablesExist([$this->table]);
    }

    /**
     * 获取表引擎
     * @return mixed
     */
    public function getEngine()
    {
        return $this->object->ENGINE;
    }

    /**
     * 获取表注释
     * @return mixed
     */
    public function getComment()
    {
        return $this->object->TABLE_COMMENT;
    }

    /**
     * 获取表字符集
     * @return mixed
     */
    public function getCollation()
    {
        return $this->object->TABLE_COLLATION;
    }

    /**
     * 获取表自增值
     * @return mixed
     */
    public function getAutoIncrement()
    {
        return $this->object->AUTO_INCREMENT;
    }

    /**
     * 获取所有字段
     * @return array
     */
    public function getFields()
    {
        return $this->_getFieldCreator($this->table)->getFields();
    }

    /**
     * 获取所有索引
     * @return array
     */
    public function getIndexes()
    {
        return $this->_getIndexCreator($this->table)->getIndexes();
    }

    /**
     * 获取所有外键
     * @return array
     */
    public function getForeignKeys()
    {
        return $this->_getForeignKeyCreator($this->table)->getForeignKeys();
    }

    /**
     * 获取表创建语句
     * @return string
     */
    public function getCreateStatements()
    {
        $statements = "Schema::create('{$this->table}', function (Blueprint \$table) {";

        if($statement = $this->_getFieldCreator($this->table)->getCreateStatements()){
            $statements .= $statement . PHP_EOL;
        }

        if($statement = $this->_getIndexCreator($this->table)->getCreateStatements()){
            $statements .= $statement . PHP_EOL;
        }

        if($statement = $this->_getForeignKeyCreator($this->table)->getCreateStatements()){
            $statements .= $statement . PHP_EOL;
        }

        $statements .= str_repeat(" ", 8) . "});" . PHP_EOL;

        if($comment = $this->getComment($this->table)){
            $statements .= PHP_EOL;
            $statements .= str_repeat(" ", 8) . "DB::statement(\"ALTER TABLE `{$this->table}` comment'{$comment}'\");" . PHP_EOL;
        }

        return trim($statements, PHP_EOL);
    }

    /**
     * 获取字段生成器对象
     * @return FieldCreator
     */
    private function _getFieldCreator()
    {
        return new FieldCreator($this->database, $this->table);
    }

    /**
     * 获取索引生成器对象
     * @return IndexCreator
     */
    private function _getIndexCreator()
    {
        return new IndexCreator($this->schema, $this->table);
    }

    /**
     * 获取外键生成器对象
     * @return ForeignKeyCreator
     */
    private function _getForeignKeyCreator()
    {
        return new ForeignKeyCreator($this->schema, $this->table);
    }

}