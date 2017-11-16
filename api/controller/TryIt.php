<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2017/11/16
 * Time: 15:51
 */

namespace sinri\PageCamera\api\controller;


use sinri\enoch\core\LibRequest;
use sinri\enoch\core\LibResponse;
use sinri\enoch\mvc\SethController;
use sinri\PageCamera\library\Task;

class TryIt extends SethController
{
    public function __construct($initData = null)
    {
        parent::__construct($initData);
    }

    public function takePhotoOfUrl()
    {
        $url = LibRequest::getRequest("url", 'https://www.leqee.com');
        $delay = LibRequest::getRequest("delay", 2000);
        if (!$delay || $delay <= 0 || $delay > 1000 * 30) {
            $delay = 2000;
        }

        $task = new Task();
        $task->setTaskID(uniqid("PageCamera_TryIt_"));
        $task->setDelay($delay);
        $task->setOutputDir('/tmp');
        $task->setUrl($url);

        $file = $task->takePhoto();

        LibResponse::downloadFileAsName($file);
    }
}