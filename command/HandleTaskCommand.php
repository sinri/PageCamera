<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2017/11/17
 * Time: 17:25
 */

namespace sinri\PageCamera\command;


use sinri\enoch\core\Enos;
use sinri\enoch\core\LibLog;
use sinri\enoch\core\LibMail;
use sinri\PageCamera\library\PageCameraHelper;
use sinri\PageCamera\library\TaskFileWorker;

class HandleTaskCommand extends Enos
{
    protected $taskManager;

    public function __construct()
    {
        parent::__construct();
        $this->taskManager = new TaskFileWorker();
    }

    /**
     * If keep $keyChain as null, return the whole config array;
     * If give $keyChain as array, it should work to return as
     * `$this->helper->safeReadNDArray($config,$keyChain,$default);`.
     * @param null|array $keyChain
     * @param null|mixed $default
     * @return array
     */
    protected function readConfig($keyChain = null, $default = null)
    {
        return null;
    }

    public function actionDefault()
    {
        $mail_config = PageCameraHelper::readConfig('env', ['output', 'mail_sender'], null);
        $mailer = new LibMail($mail_config);
        $list = $this->taskManager->listTasks();
        foreach ($list as $task) {
            //TODO check task time condition, now all true
            $shouldRunNow = true;
            if (!$shouldRunNow) continue;
            $photo_file_path = $task->takePhoto($command);

            //TODO send them to target place, such as OSS, now just email
            $now = date('Y-m-d H:i:s');
            if ($mail_config) {
                $sent = $mailer->prepareSMTP()
                    ->addReceiver('ljni@leqee.com')
                    ->addSubject("PageCamera " . $task->getTitle() . " took photo just know ({$now})")
                    ->addHTMLBody(
                        "<h1>" . $task->getTitle() . "</h1>"
                        . "<p>" . $task->getUrl() . "</p>"
                    )
                    ->addAttachment($photo_file_path)
                    ->finallySend($error);
                if ($sent) {
                    $this->logger->log(LibLog::LOG_INFO, "MAIL SENT!");
                } else {
                    $this->logger->log(LibLog::LOG_ERROR, "MAIL ERROR", $error);
                }
            } else {
                $this->logger->log(LibLog::LOG_WARNING, "Mail not configured");
            }
        }
    }
}