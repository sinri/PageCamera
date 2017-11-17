<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2017/11/16
 * Time: 17:05
 */

namespace sinri\PageCamera\library;


use sinri\enoch\core\LibLog;
use sinri\enoch\helper\CommonHelper;

class PageCameraHelper
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

    /**
     * @var LibLog
     */
    protected static $logger = null;

    /**
     * @param string $prefix
     * @param string $level
     * @param string $message
     * @param mixed $object
     */
    public static function log($prefix, $level, $message, $object = '')
    {
        if (empty(self::$logger)) {
            $logDir = self::readConfig("env", ['log', 'path'], '/var/log/PageCamera');
            if (!file_exists($logDir)) {
                @mkdir($logDir, 0777, true);
            }
            self::$logger = new LibLog($logDir, $prefix);
        }
        self::$logger->log($level, $message, $object);
    }
}