<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2017/11/16
 * Time: 13:24
 */

namespace sinri\PageCamera\api\controller;


use sinri\enoch\core\LibRequest;
use sinri\enoch\helper\CommonHelper;
use sinri\enoch\mvc\SethController;
use sinri\PageCamera\library\Task;
use sinri\PageCamera\library\TaskFileWorker;

class TaskManager extends SethController
{
    protected $taskManager;

    public function __construct($initData = null)
    {
        parent::__construct($initData);

        $this->taskManager = new TaskFileWorker();
    }

    public function registerTask()
    {
        $url = LibRequest::getRequest("url", null, '/^https?\:\/\/.+$/');
        $title = LibRequest::getRequest("title");
        $delay = LibRequest::getRequest("delay", 2000);
        $viewportWidth = LibRequest::getRequest("width", 1366);


        $url = trim($url);
        CommonHelper::assertNotEmpty($url, "网址不能为空，且必须为http或https协议的网址。");
        $title = trim($title);
        CommonHelper::assertNotEmpty($title, "必须给出任务命名");

        $delay = intval($delay, 10);
        if (!$delay || $delay <= 0 || $delay > 1000 * 30) {
            $delay = 2000;
        }
        $viewportWidth = intval($viewportWidth, 10);
        if ($viewportWidth <= 0 || $viewportWidth > 10000) {
            $viewportWidth = 1366;
        }

        $task = new Task();
        $task->setUrl($url);
        $task->setTitle($title);
        $task->setViewportWidth($viewportWidth);
        $task->setDelay($delay);

        $task->setTaskID(md5($title));

        $done = $this->taskManager->registerTask($task);

        if ($done) {
            $this->_sayOK(['written' => $done, "task_id" => $task->getTaskID()]);
        } else {
            $this->_sayFail("虚无啊");
        }
    }

    public function taskList()
    {
        $list = $this->taskManager->listTasks();
        $json = [];
        foreach ($list as $item) {
            $json[] = json_encode($item->toJsonString());
        }
        return $this->_sayOK(["list" => $json]);
    }

    public function removeTask()
    {
        $task_id = LibRequest::getRequest("task_id");
        CommonHelper::assertNotEmpty($task_id);
        $done = $this->taskManager->removeTask($task_id);
        if ($done) {
            $this->_sayOK("已删除");
        } else {
            $this->_sayFail("虚无啊");
        }
    }

}