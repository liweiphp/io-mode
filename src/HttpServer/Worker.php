<?php
/**
 * Created by PhpStorm.
 * User: weili
 * Date: 2021-01-22
 * Time: 16:16
 */

namespace ioMode\HttpServer;
use ioMode\CoreBase;

class Worker extends CoreBase
{
    public function __construct($host, $port, $type = 'http')
    {
        parent::__construct($host, $port, $type);
    }

    protected function accept()
    {
        // TODO: Implement accept() method.
        while (true) {
            // 监听是否存在连接
            $conn = stream_socket_accept($this->server);
            if (!empty($conn)) {
                // 接收服务的信息
                $data = fread($conn, 65535);
                call_user_func($this->events['request'], (new Request())->decodeHttp($data), new Response(), $conn);
            }


        }

    }
}