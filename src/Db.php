<?php
/**
 * Created by PhpStorm.
 * User: 明月有色
 * Date: 2017/7/23
 * Time: 下午6:53
 */

namespace Inversion;


class Db
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function fetchAll($sql)
    {
        return $this->pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getMyDbName()
    {
        $sql   = "select database();";
        $table = $this->pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
        return end($table[0]);
    }

    public function getFieldType()
    {
        $sql   = "SELECT DATA_TYPE FROM information_schema.COLUMNS GROUP BY DATA_TYPE";
        return $this->pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }
}