<?php
/**
 * Created by PhpStorm.
 * User: 明月有色
 * Date: 2017/7/23
 * Time: 下午3:42
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__.'/vendor/autoload.php';


function dd($data)
{
    echo "<xmp>";
    print_r($data);
    echo "</xmp>";
}

try {

    $pdo = new PDO("mysql:host=localhost;dbname=phalcon-pe", "root", "123456", array(PDO::ATTR_AUTOCOMMIT => 0));
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {

    echo "数据库连接失败：" . $e->getMessage();
    exit;
}

$load = new \Inversion\Load([
    'pdo'=>$pdo,
    'save_path'=>__DIR__.'/test/',
]);

$build = $load->getBuild();
$build->run();