<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2017/11/16
 * Time: 13:22
 */
//error_reporting(E_ALL^E_NOTICE^E_WARNING);
require_once __DIR__ . '/../autoload.php';

date_default_timezone_set("Asia/Shanghai");

$lamech = new \sinri\enoch\mvc\Lamech();

$lamech->getRouter()->setErrorHandler(function ($err_data) {
    header("Content-Type: application/json");
    \sinri\enoch\core\LibResponse::jsonForAjax(\sinri\enoch\core\LibResponse::AJAX_JSON_CODE_FAIL, $err_data);
});

$lamech->getRouter()->loadAllControllersInDirectoryAsCI(
    __DIR__ . '/controller',
    'api/',
    '\sinri\PageCamera\api\controller\\',
    ['\sinri\PageCamera\api\middleware\GateKeeper']
);

$lamech->handleRequestForWeb();