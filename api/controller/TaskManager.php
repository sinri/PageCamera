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

class TaskManager extends SethController
{
    public function __construct($initData = null)
    {
        parent::__construct($initData);
    }

    public function registerTask()
    {
        $url = LibRequest::getRequest("url");
        $task_name = LibRequest::getRequest("task_name", uniqid());

        CommonHelper::assertNotEmpty($url);
        CommonHelper::assertNotEmpty($task_name);
    }

}