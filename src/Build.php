<?php
/**
 * Created by PhpStorm.
 * User: 明月有色
 * Date: 2017/7/23
 * Time: 下午3:53
 */

namespace Inversion;


class Build
{
    private $inversion;
    private $db;

    public function __construct(Inversion $inversion)
    {
        $this->inversion = $inversion;
        $db = new Db($inversion->pdo);
        $this->db = $db;
    }

    /**
     * 开始生产
     */
    public function run()
    {
        $list = $this->getAllTable();
        $templateString  = file_get_contents(__DIR__.'/../migration.php');
        foreach ($list as $name){
            $className   = 'Create' . $name . 'Table';
            $tableString = str_replace(['TemplateClassName','TemplateTableName'], [$className,$name], $templateString);

            $addComman   = '';
            foreach ($this->getDetail($name) as $value) {
                $is_key = false;
                // 是否为主键
                if( $value['COLUMN_KEY']=='PRI' ){
                    if( $value['EXTRA']=='auto_increment' ){
                        $tableOpt['id'] = $value['COLUMN_NAME'];
                        $is_key = true;
                    }else{
                        if( isset($tableOpt['id']) && $tableOpt['id'] ){
                            $tableOpt['primary_key'][] = $value['COLUMN_NAME'];
                        }else{
                            $tableOpt['primary_key'][] = $value['COLUMN_NAME'];
                            $tableOpt['id'] = false;
                        }
                    }
                }elseif ( $value['COLUMN_KEY']=='UNI' ){
                    // 记录索引-唯一索引
                    $index[] = [
                        'name'=>$value['COLUMN_KEY'],
                        'option'=>['unique'=>true,'name'=>''],
                    ];
                }elseif ( $value['COLUMN_KEY']=='MUL' ){
                    // 记录索引
                    $index[] = [
                        'name'=>$value['COLUMN_KEY'],
                        'option'=>[],
                    ];
                }

                if( $is_key==false ){
                    $addComman .= $this->setColumn($value);
                }
            }

            //file_put_contents(ROOT_PATH.'/database/migrations/'.date('YmdHis').'_create_'.strtolower($name).'_table.php',$tableString,LOCK_EX);

        }

    }

    private function setColumn($value)
    {
        switch ($value['DATA_TYPE']){
            case 'bigint':
                $type = 'biginteger';
                break;
            case 'blob':
                $type = 'blob';
                break;
            case 'char':
                $type = 'uuid';
                break;
            case 'datetime':
                $type = $value['DATA_TYPE'];
                break;
            case 'decimal':
                $type = $value['DATA_TYPE'];
                break;
            case 'double':
                $type = 'float';
                break;
            case 'enum':
                $type = $value['DATA_TYPE'];
                break;
            case 'float':
                $type = $value['DATA_TYPE'];
                break;
            case 'int':
                $type = 'integer';
                break;
            case 'longblob':
                $type = 'text';
                break;
            case 'longtext':
                $type = 'text';
                break;
            case 'mediumtext':
                $type = 'text';
                break;
            case 'set':
                $type = $value['DATA_TYPE'];
                break;
            case 'smallint':
                $type = $value['DATA_TYPE'];
                break;
            case 'text':
                $type = $value['DATA_TYPE'];
                break;
            case 'time':
                $type = $value['DATA_TYPE'];
                break;
            case 'timestamp':
                $type = $value['DATA_TYPE'];
                break;
            case 'tinyint':
                $type = 'boolean';
                break;
            case 'tinytext':
                $type = 'text';
                break;
            case 'varchar':
                $type = 'string';
                break;
            default:
                $type = $value['DATA_TYPE'];
                break;
        }
        dd($type);
        dd($value);
    }

    /**
     * 获取所有数据表
     */
    public function getAllTable()
    {
        $sql  = "show tables";
        $list = $this->db->fetchAll($sql);
        foreach ($list as $item) {
            $table[] = end($item);
        }
        return $table;
    }

    public function getDetail($table)
    {
        $sql         = "select * from information_schema.COLUMNS where TABLE_SCHEMA='" . $this->db->getMyDbName() . "' and TABLE_NAME='{$table}';";
        $table = $this->db->fetchAll($sql);
        return $table;
    }
}