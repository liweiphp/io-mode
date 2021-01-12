<?php
declare(strict_types=1);

namespace ioMode\Reactor;
use \Event;

class Connection
{
    protected $server;
    protected $conn;
    public function __construct(Worker $server, $conn)
    {
        $this->server = $server;
        $this->conn = $conn;
    }

    public function handler()
    {
        Reactor::getInstance()->add($this->conn, Reactor::ALL, $this->sendMessage());
    }

    /**
     * 接收信息回调函数
     * @return \Closure
     */
    public function sendMessage()
    {
        return function ($socket){
            // 接收服务的信息
            $data = fread($socket, 65535);
            if ('' === $data || false === $data) {
                $this->checkConn($data, $socket);
            } else {
                call_user_func($this->server->events['receive'], $this->server, $socket, $data);
            }
        };
    }

    // 校验连接
    protected function checkConn($buffer, $conn)
    {
        if (\strlen($buffer) === 0) {
            if (! \get_resource_type($conn) == "Unknown"){
                // 断开连接
                $this->close($conn);
            }
            \call_user_func($this->server->events['close'], $this->server, $conn);
        }
        Reactor::getInstance()->del($conn);
    }
}