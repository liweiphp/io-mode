<?php
namespace ioMode\Reactor;
use ioMode\CoreBase;

class Worker extends CoreBase
{
    public function __construct($host, $port, $type = 'tcp')
    {
        parent::__construct($host, $port, $type);
        \stream_set_blocking($this->server, false);
    }

    protected function accept()
    {
        // TODO: Implement accept() method.
        Reactor::getInstance()->add($this->server, Reactor::ALL, $this->createConn());
        Reactor::getInstance()->loop();
    }

    /**
     * 创建链接回调函数
     * @return \Closure
     */
    protected function createConn()
    {
        return function ($socket){
            $conn = stream_socket_accept($socket);
            if ($conn) {
                call_user_func($this->events['connect'], $this, $conn);
            }
            dd(posix_getppid(), "PID");
            (new Connection($this, $conn))->handler();//传值方式不能在handler里面传
        };

    }
}