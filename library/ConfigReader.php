<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2017/11/16
 * Time: 17:05
 */

namespace sinri\PageCamera\library;


use sinri\enoch\helper\CommonHelper;

class ConfigReader
{
    public static function readConfig($aspect, $keyChain, $default = null)
    {
        $config_file = __DIR__ . '/../config';
        $config_file = $config_file . '/' . $aspect . '.php';

        if (!file_exists($config_file)) {
            return $default;
        }

        $config = [];
        require $config_file;

        return CommonHelper::safeReadNDArray($config, $keyChain, $default);
    }

}