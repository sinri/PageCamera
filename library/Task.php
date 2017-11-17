<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2017/11/16
 * Time: 13:36
 */

namespace sinri\PageCamera\library;


class Task
{
// node camera.js --fullPage true --viewportWidth 1366 --viewportHeight 768 --delay 5000 --outputFile ./1.png

    /**
     * @var string
     */
    protected $url;
    /**
     * @var bool
     */
    protected $fullPage = true;
    /**
     * @var int
     */
    protected $viewportWidth = 1366;
    /**
     * @var int
     */
    protected $viewportHeight = 768;
    /**
     * @var int
     */
    protected $delay = 5000;
    /**
     * @var string
     */
    protected $outputDir = '.';
    /**
     * @var string
     */
    protected $taskID;
    /**
     * @var array
     */
    protected $timerOption;

    public function __construct()
    {

    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getOutputDir()
    {
        return $this->outputDir;
    }

    /**
     * @param string $outputDir
     */
    public function setOutputDir($outputDir)
    {
        $this->outputDir = $outputDir;
    }

    /**
     * @return array
     */
    public function getTimerOption()
    {
        return $this->timerOption;
    }

    /**
     * @param array $timerOption
     */
    public function setTimerOption($timerOption)
    {
        $this->timerOption = $timerOption;
    }

    /**
     * @param string $command
     * @return string
     * @throws \Exception
     */
    public function takePhoto(&$command = '')
    {
        $output_file = $this->outputDir . '/' . $this->taskID . "_" . date('YmdHis') . ".png";

        $node = ConfigReader::readConfig("env", ["node_executable_path"], "node");

        $command = $node . " " . __DIR__ . '/../camera/camera.js ';
        if ($this->fullPage) {
            $command .= " --fullPage true ";
        }
        $command .= " --viewportWidth " . intval($this->viewportWidth, 10);
        $command .= " --viewportHeight " . intval($this->viewportHeight, 10);
        $command .= " --delay " . intval($this->delay, 10);
        $command .= " --outputFile " . escapeshellarg($output_file);
        $command .= " --url " . escapeshellarg($this->url);

        //echo "Prepared command: " . $command . PHP_EOL;

        $line = exec($command, $output, $return_var);

        // TODO store

        if ($return_var !== 0) {
            throw new \Exception("EXEC " . $command . " returned " . json_encode($return_var) . ' and output as ' . json_encode($output));
        }

        //done
        return $output_file;
    }


    //// getter and setter

    /**
     * @return bool
     */
    public function isFullPage()
    {
        return $this->fullPage;
    }

    /**
     * @param bool $fullPage
     */
    public function setFullPage($fullPage)
    {
        $this->fullPage = $fullPage;
    }

    /**
     * @return int
     */
    public function getViewportWidth()
    {
        return $this->viewportWidth;
    }

    /**
     * @param int $viewportWidth
     */
    public function setViewportWidth($viewportWidth)
    {
        $this->viewportWidth = $viewportWidth;
    }

    /**
     * @return int
     */
    public function getViewportHeight()
    {
        return $this->viewportHeight;
    }

    /**
     * @param int $viewportHeight
     */
    public function setViewportHeight($viewportHeight)
    {
        $this->viewportHeight = $viewportHeight;
    }

    /**
     * @return int
     */
    public function getDelay()
    {
        return $this->delay;
    }

    /**
     * @param int $delay
     */
    public function setDelay($delay)
    {
        $this->delay = $delay;
    }

    /**
     * @return mixed
     */
    public function getTaskID()
    {
        return $this->taskID;
    }

    /**
     * @param mixed $taskID
     */
    public function setTaskID($taskID)
    {
        $this->taskID = $taskID;
    }
}