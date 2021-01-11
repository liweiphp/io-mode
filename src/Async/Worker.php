<?php
/**
 * Created by PhpStorm.
 * User: weili
 * Date: 2021-01-18
 * Time: 21:31
 */
declare(strict_types=1);
namespace ioMode\Async;
use ioMode\CoreBase;
use \EventBase;
use \Event;

class Worker extends CoreBase
{
    public function __construct($host, $port, $type = 'tcp')
    {
        parent::__construct($host, $port, $type);
        \stream_set_blocking($this->server, false);
    }

    /**
     * 异步
     */
    protected function accept()
    {
        // TODO: Implement accept() method.
        $count = [];
        $eventBase = new \EventBase();
        $event = new \Event($eventBase, $this->server, EVENT::READ | EVENT::PERSIST, function ($socket) use (&$eventBase, &$count){
            $conn = stream_socket_accept($socket);
            if ($conn) {
                call_user_func($this->events['connect'], $this, $conn);
            }
            (new Events($eventBase, $conn))->handle($this, $count);

        });
        $event->add();
        $eventBase->loop();
    }

    public function sendMessage($conn)
    {
        // 接收服务的信息
        $data = fread($conn, 65535);
        dd($data, "send data");
        if ('' === $data || false === $data) {
             $this->checkConn($data, $conn);
        } else {
            $this->events['receive']($this, $conn, $data);
        }
    }
    // 校验连接
    protected function checkConn($buffer, $conn)
    {
        dd($buffer, "the data");
        if (\strlen($buffer) === 0) {
            if (! \get_resource_type($conn) == "Unknown"){
                // 断开连接
                $this->close($conn);
            }
            \call_user_func($this->events['close'], $this, $conn );
        }
    }
}