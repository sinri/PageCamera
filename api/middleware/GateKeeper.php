<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2017/11/16
 * Time: 13:27
 */

namespace sinri\PageCamera\api\middleware;


use sinri\enoch\mvc\MiddlewareInterface;

class GateKeeper extends MiddlewareInterface
{
    public function shouldAcceptRequest($path, $method, $params, &$preparedData = null)
    {
        return true;
    }

}