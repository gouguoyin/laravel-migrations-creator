<?php

namespace Gouguoyin\MigrationsCreator\Drivers\Mysql;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FieldCreator
{
    protected $database;
    protected $table;

    public function __construct($database, $table)
    {
        $this->database = $database;
        $this->table    = $table;
    }

    /**
     * 获取所有字段
     * @return array
     */
    public function getFields()
    {
        $columns = DB::table('information_schema.columns')
            ->where('table_schema', $this->database)
            ->where('table_name', $this->table)
            ->get();

        $fields = [];
        foreach ($columns as $column) {
            $fields[] = [
                'name'           => $column->COLUMN_NAME,
                'type'           => $column->DATA_TYPE,
                'argument'       => $this->_getArgument($column),
                'default'        => $this->_getDefault($column),
                'is_null'        => $column->IS_NULLABLE  == 'YES' ? "true": "false",
                'unsigned'       => Str::contains($column->COLUMN_TYPE, 'unsigned') ? "true": "false",
                'auto_increment' => $column->EXTRA == 'auto_increment' ? "true": "false",
                'comment'        => $column->COLUMN_COMMENT,
                'charset'        => $column->CHARACTER_SET_NAME,
                'collation'      => $column->COLLATION_NAME,
            ];
        }

        return $fields;
    }

    /**
     * 获取所有字段的创建语句
     * @return string
     */
    public function getCreateStatements()
    {
        $statements = PHP_EOL;
        foreach ($this->getFields() as $field) {
            $statements .= str_repeat(" ", 12) . $this->_getCreateStatement($field) . PHP_EOL;
        }
        return rtrim($statements, PHP_EOL);
    }

    /**
     * 获取单个字段创建语句
     * @param array $field
     * @return string
     */
    private function _getCreateStatement(array $field)
    {
        $name           = $field['name'];
        $type           = $field['type'];
        $argument       = $field['argument'];
        $unsigned       = $field['unsigned'];
        $default        = $field['default'];
        $is_null        = $field['is_null'];
        $comment        = $field['comment'];
        $auto_increment = $field['auto_increment'];
        $charset        = '';
        $collation      = '';

        $statement = '';
        switch ($type) {
            case FieldType::INT:
                $statement = "integer('{$name}', $auto_increment, $unsigned)";
                break;
            case FieldType::INTEGER:
                $statement = "integer('{$name}', $auto_increment, $unsigned)";
                break;
            case FieldType::TINYINT:
                $statement = "tinyInteger('{$name}', $auto_increment, $unsigned)";
                break;
            case FieldType::SMALLINT:
                $statement = "smallInteger('{$name}', $auto_increment, $unsigned)";
                break;
            case FieldType::MEDIUMINT:
                $statement = "mediumInteger('{$name}', $auto_increment, $unsigned)";
                break;
            case FieldType::BIGINT:
                $statement = "bigInteger('{$name}', $auto_increment, $unsigned)";
                break;
            case FieldType::FLOAT:
                $statement = "float('{$name}',{$argument})";
                break;
            case FieldType::DECIMAL:
                if($unsigned == 'true'){
                    $statement = "unsignedDecimal('{$name}',{$argument})";
                }else{
                    $statement = "decimal('{$name}',{$argument})";
                }
                break;
            case FieldType::DOUBLE:
                $statement = "double('{$name}',{$argument})";
                break;
            case FieldType::CHAR:
                $statement = "char('{$name}',{$argument})";
                break;
            case FieldType::VARCHAR:
                $statement = "string('{$name}',{$argument})";
                $charset   = $field['charset'];
                $collation = $field['collation'];
                break;
            case FieldType::TEXT:
                $statement = "text('{$name}')";
                $charset   = $field['charset'];
                $collation = $field['collation'];
                break;
            case FieldType::TINYTEXT:
                $statement = "text('{$name}')";
                $charset   = $field['charset'];
                $collation = $field['collation'];
                break;
            case FieldType::MEDIUMTEXT:
                $statement  = "mediumText('{$name}')";
                $charset   = $field['charset'];
                $collation = $field['collation'];
                break;
            case FieldType::LONGTEXT:
                $statement  = "longText('{$name}')";
                $charset   = $field['charset'];
                $collation = $field['collation'];
                break;
            case FieldType::BINARY:
                $statement = "binary('{$name}')";
                break;
            case FieldType::BLOB:
                $statement = "binary('{$name}')";
                break;
            case FieldType::TINYBLOB:
                $statement = "binary('{$name}')";
                break;
            case FieldType::MEDIUMBLOB:
                $statement = "binary('{$name}')";
                break;
            case FieldType::LONGBLOB:
                $statement = "binary('{$name}')";
                break;
            case FieldType::ENUM:
                $statement = "enum('{$name}',[$argument])";
                break;
            case FieldType::SET:
                $statement = "set('{$name}',[$argument])";
                break;
            case FieldType::DATE:
                $statement = "date('{$name}')";
                break;
            case FieldType::YEAR:
                $statement = "year('{$name}')";
                break;
            case FieldType::TIME:
                $statement = "time('{$name}')";
                break;
            case FieldType::DATETIME:
                $statement = "dateTime('{$name}')";
                break;
            case FieldType::TIMESTAMP:
                $statement = "timestamp('{$name}')";
                break;
            case FieldType::JSON:
                $statement = "json('{$name}')";
                break;
            case FieldType::POINT:
                $statement = "point('{$name}')";
                break;
            case FieldType::MULTIPOINT:
                $statement = "multiPoint('{$name}')";
                break;
            case FieldType::LINESTRING:
                $statement = "lineString('{$name}')";
                break;
            case FieldType::MULTILINESTRING:
                $statement = "multiLineString('{$name}')";
                break;
            case FieldType::POLYGON:
                $statement = "polygon('{$name}')";
                break;
            case FieldType::MULTIPOLYGON:
                $statement = "multiPolygon('{$name}')";
                break;
            case FieldType::GEOMETRY:
                $statement = "geometry('{$name}')";
                break;
            case FieldType::GEOMETRYCOLLECTION:
                $statement = "geometryCollection('{$name}')";
                break;
        }

        if(!$statement){
            return;
        }

        $statement = '$table->' . $statement;

        if($is_null == 'true'){
            $statement .= '->nullable()';
        }

        if(!is_null($default)){
            $statement .= "->default({$default})";
        }

        if($comment){
            $statement .= "->comment('{$comment}')";
        }

        if($charset){
            $statement .= "->charset('{$charset}')";
        }

        if($collation){
            $statement .= "->collation('{$collation}')";
        }

        return $statement . ';';
    }

    /**
     * 获取参数字符串
     * @param $column
     * @return mixed
     */
    private function _getArgument($column)
    {
        return trim(str_replace([$column->DATA_TYPE, '(', ')', 'unsigned', 'zerofill'], '',$column->COLUMN_TYPE));
    }

    /**
     * 获取默认值
     * @param $column
     * @return string
     */
    private function _getDefault($column)
    {
        $default = $column->COLUMN_DEFAULT;

        if(!is_null($default) && FieldType::isDateTime($column->DATA_TYPE)){
            $default = "'{$default}'";
        }

        if($column->COLUMN_DEFAULT == 'CURRENT_TIMESTAMP'){
            $default = "DB::raw('CURRENT_TIMESTAMP')";
        }

        if($column->EXTRA == 'on update CURRENT_TIMESTAMP'){
            $default = "DB::raw('ON UPDATE CURRENT_TIMESTAMP')";
        }

        if($column->COLUMN_DEFAULT == 'CURRENT_TIMESTAMP' && $column->EXTRA == 'on update CURRENT_TIMESTAMP'){
            $default = "DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')";
        }

        if(FieldType::isString($column->DATA_TYPE)){
            $default = "'{$default}'";
        }

        return $default;
    }

}