<?php
/**
 * Created by PhpStorm.
 * User: 明月有色
 * Date: 2017/7/23
 * Time: 下午3:45
 */

namespace Inversion;


class Load
{
    private $config;

    public function __construct($config = [])
    {
        $inversion = new Inversion();
        $inversion->migrationsPath = $config['save_path'];
        $inversion->pdo = $config['pdo'];
        $this->inversion = $inversion;
    }

    public function getBuild()
    {
        return new Build($this->inversion);
    }
}