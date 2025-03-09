<?php

require_once __DIR__ . '/../libraries/Psr4AutoloaderClass.php';

$loader = new Psr4AutoloaderClass();
$loader->register();

$loader->addNamespace('CT467\Labs', __DIR__ . '/classes');

try {
    $pdo = (new \CT467\Labs\PDOFactory())->create([
        'dbhost' => 'localhost',
        'dbname' => 'qlnhathuoc',
        'dbuser' => 'root',
        'dbpass' => ''
    ]);

    // echo "Kết nối thành công!";
} catch (Exception $ex) {
    echo 'Không thể kết nối đến MySQL, kiểm tra lại username/password.<br>';
    exit("<pre>${ex}</pre>");
}