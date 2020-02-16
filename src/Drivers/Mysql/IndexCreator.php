<?php

namespace Gouguoyin\MigrationsCreator\Drivers\Mysql;

class IndexCreator
{
    protected $schema;
    protected $table;

    public function __construct($schema, $table)
    {
        $this->schema = $schema;
        $this->table  = $table;
    }

    /**
     * 获取所有索引
     * @return mixed
     */
    public function getIndexes()
    {
        return $this->schema->listTableIndexes($this->table);
    }

    /**
     * 获取所有索引的创建语句
     * @return string
     */
    public function getCreateStatements()
    {
        $statements = '';
        foreach ($this->getIndexes() as $index) {
            if($statement = $this->_getCreateStatement($index)){
                $statements .= str_repeat(" ", 12) . $statement . PHP_EOL;
            }
        }
        return $statements;
    }

    /**
     * 获取单个索引创建语句
     * @param object $index
     * @return string|void
     */
    private function _getCreateStatement(object $index)
    {
        $name     = $index->getName();
        $argument = $this->_getArgument($index);
        $primary  = $index->isPrimary();
        $unique   = $index->isUnique();
        $normal   = $index->isSimpleIndex();

        if($name == 'PRIMARY'){
            return;
        }

        $statement = '';
        if($primary){
            $statement .= "primary($argument)";
        }

        if ($unique) {
            $statement .= "unique($argument, '{$name}')";
        }

        if ($normal) {
            $statement .= "index($argument, '{$name}')";
        }

        if(!$statement){
            return;
        }

        $statement = '$table->' . $statement;

        return $statement . ';';
    }

    /**
     * 获取参数字符串
     * @param object $index
     * @return mixed
     */
    private function _getArgument(object $index)
    {
        $columns = $index->getColumns();

        $columns =  array_map(function ($value) {
            return "'{$value}'";
        }, $columns);

        if(count($columns) < 2){
            return implode(',', $columns);
        }

        return '[' . implode(',', $columns) . ']';
    }
}