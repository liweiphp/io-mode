<?php
/**
 * Created by PhpStorm.
 * User: weili
 * Date: 2021-01-19
 * Time: 17:48
 */

namespace ioMode\SwooleAsync;
use ioMode\CoreBase;
use Swoole\Event;

class Worker extends CoreBase
{
//    public function __construct($host, $port, $type = 'tcp')
//    {
//        parent::__construct($host, $port, $type);
//        stream_set_blocking($this->server, 0);
//    }

    protected function accept()
    {
        // TODO: Implement accept() method.
        Event::add($this->server, $this->createConn());
//        Swoole\Event::wait();

    }

    protected function createConn()
    {
        return function ($socket) {
            $conn = stream_socket_accept($socket);
            dd(get_resource_type($conn), "connect");
            if ($conn) {
                call_user_func($this->events['connect'], $this, $conn);
                Event::add($conn, $this->sendMessage());
            }
        };
    }

    protected function sendMessage()
    {
        return function ($socket){
            // 接收服务的信息
            $data = fread($socket, 65535);
            dd($data, "send data");
            if ('' === $data || false === $data) {
                $this->checkConn($data, $socket);
            } else {
                call_user_func($this->events['receive'], $this, $socket, $data);
            }
        };

    }
    // 校验连接
    protected function checkConn($buffer, $conn)
    {
        dd($buffer, "check data");
        if (\strlen($buffer) === 0) {
            if (get_resource_type($conn) != "Unknown"){
                Event::del($conn);
                // 断开连接
                $this->close($conn);
            }
            \call_user_func($this->events['close'], $this, $conn );
        }
    }
}