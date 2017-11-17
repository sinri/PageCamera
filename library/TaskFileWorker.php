<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2017/11/16
 * Time: 13:36
 */

namespace sinri\PageCamera\library;


class TaskFileWorker
{
    /**
     * @param string $task_id
     * @return string
     * @throws \Exception
     */
    protected function taskFilePath($task_id)
    {
        if (preg_match('/^[A-Za-z0-9\._\-]+$/', $task_id)) {
            throw new \Exception("task id contains illegal char");
        }
        $dir = PageCameraHelper::readConfig("env", ["task_store", "path"], __DIR__ . '/../tasks');
        if (!file_exists($dir)) {
            @mkdir($dir, 0777, true);
        }
        return $dir . DIRECTORY_SEPARATOR . $task_id . '.task.json';
    }

    /**
     * @param Task $task
     * @return bool|int
     * @throws \Exception
     */
    public function registerTask($task)
    {
        $file = $this->taskFilePath($task->getTaskID());
        return file_put_contents($file, $task->toJsonString());
    }

    /**
     * @param string $task_id
     * @return bool
     * @throws \Exception
     */
    public function removeTask($task_id)
    {
        $file = $this->taskFilePath($task_id);
        if (file_exists($file)) {
            return unlink($file);
        }
        return true;
    }

    /**
     * @param $task_id
     * @return Task|false|null
     * @throws \Exception
     */
    public function findTask($task_id)
    {
        $file = $this->taskFilePath($task_id);
        if (!file_exists($file)) {
            return false;
        }
        $json = file_get_contents($file);
        try {
            $task = Task::fromJsonString($json);
            return $task;
        } catch (\Exception $exception) {
            return null;
        }
    }

    /**
     * @return Task[]
     */
    public function listTasks()
    {
        $dir = PageCameraHelper::readConfig("env", ["task_store", "path"], __DIR__ . '/../tasks');
        $list = glob($dir . DIRECTORY_SEPARATOR . '*.task.json');
        $tasks = [];
        foreach ($list as $item) {
            $json = file_get_contents($item);
            $task = Task::fromJsonString($json);
            $tasks[$task->getTaskID()] = $task;
        }
        return $tasks;
    }
}