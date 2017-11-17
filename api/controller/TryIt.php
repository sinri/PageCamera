<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2017/11/16
 * Time: 15:51
 */

namespace sinri\PageCamera\api\controller;


use sinri\enoch\core\LibLog;
use sinri\enoch\core\LibRequest;
use sinri\enoch\core\LibResponse;
use sinri\enoch\helper\CommonHelper;
use sinri\enoch\mvc\SethController;
use sinri\PageCamera\library\PageCameraHelper;
use sinri\PageCamera\library\Task;

class TryIt extends SethController
{
    public function __construct($initData = null)
    {
        parent::__construct($initData);
    }

    public function takePhotoOfUrl()
    {
        $url = LibRequest::getRequest("url", 'https://www.leqee.com', '/^https?\:\/\/.+$/', $incorrectUrl);
        CommonHelper::assertNotEmpty(!$incorrectUrl);
        $delay = LibRequest::getRequest("delay", 2000);
        if (!$delay || $delay <= 0 || $delay > 1000 * 30) {
            $delay = 2000;
        }
        $viewportWidth = LibRequest::getRequest("width", 1366);

        PageCameraHelper::log("API", LibLog::LOG_DEBUG, "Request " . $this->request_uuid, [
            "url" => $url,
            "delay" => $delay,
            "width" => $viewportWidth,
        ]);

        $task = new Task();
        $task->setTaskID(uniqid("PageCamera_TryIt_"));
        $task->setDelay($delay);
        $task->setOutputDir('/tmp');
        $task->setUrl($url);
        $task->setViewportWidth($viewportWidth);

        $file = $task->takePhoto($command);

        PageCameraHelper::log("API", LibLog::LOG_DEBUG, "Executed " . $this->request_uuid, [
            "file" => $file,
            "command" => $command,
        ]);

        $done = LibResponse::downloadFileAsName($file, null, $error);
        if (!$done) {
            PageCameraHelper::log("API", LibLog::LOG_DEBUG, "Error to download " . $this->request_uuid, $error);
            throw $error;
        }
    }
}