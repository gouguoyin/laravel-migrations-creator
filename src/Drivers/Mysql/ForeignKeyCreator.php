<?php

namespace Gouguoyin\MigrationsCreator\Drivers\Mysql;

class ForeignKeyCreator
{
    protected $schema;
    protected $table;

    public function __construct($schema, $table)
    {
        $this->schema = $schema;
        $this->table  = $table;
    }

    /**
     * 获取所有外键
     * @return mixed
     */
    public function getForeignKeys()
    {
        return $this->schema->listTableForeignKeys($this->table);
    }

    /**
     * 获取所有外键的创建语句
     * @return string
     */
    public function getCreateStatements()
    {
        $statements = PHP_EOL;
        foreach ($this->getForeignKeys() as $key) {
            if($statement = $this->_getCreateStatement($key)){
                $statements .= str_repeat(" ", 12) . $statement . PHP_EOL;
            }
        }
        return rtrim($statements, PHP_EOL);
    }

    /**
     * 获取单个索引创建语句
     * @param object $key
     * @return string
     */
    private function _getCreateStatement(object $key)
    {
        $foreign_name    = $key->getName();
        $foreign_table   = $key->getForeignTableName();
        $foreign_columns = $this->_getColumns($key);
        $on_delete       = $key->onDelete();
        $on_update       = $key->onUpdate();

        $statement = "->foreign($foreign_columns,'{$foreign_name}')";

        if ($foreign_table) {
            $statement .= "->on('{$foreign_table}')";
        }

        if ($on_delete) {
            $statement .= "->onDelete('{$on_delete}')";
        }

        if ($on_update) {
            $statement .= "->onUpdate('{$on_update}')";
        }

        $statement = '$table' . $statement;

        return $statement . ';';
    }

    /**
     * 获取函数参数字符串
     * @param object $key
     * @return mixed
     */
    private function _getColumns(object $key)
    {
        $columns =  array_map(function ($value) {
            return "'{$value}'";
        }, $key->getForeignColumns());

        if(count($columns) < 2){
            return implode(',', $columns);
        }

        return '[' . implode(',', $columns) . ']';
    }
}