<?php
/**
 * Created by PhpStorm.
 * User: weili
 * Date: 2021-01-18
 * Time: 09:30
 */
declare(strict_types=1);
namespace ioMode\Signal;
use ioMode\CoreBase;

class Worker extends CoreBase
{
    protected function accept()
    {
        // TODO: Implement accept() method.


        while (true) {
            $conn = stream_socket_accept($this->server);
            dd($conn, 'conn');
            call_user_func($this->events['connect'], $this, $conn);
            pcntl_signal(SIGIO, $this->signal_handler($conn));
            posix_kill(posix_getpid(), SIGIO);
            pcntl_signal_dispatch();
            if (!$conn) {
                $this->close($conn);
            }
        }
    }

    protected function signal_handler($conn)
    {
        return function ($signal) use ($conn) {
            switch ($signal) {
                case SIGIO:
                    $this->sendMessage($conn);
                    break;

            }
        };
    }

    protected function sendMessage($conn)
    {
        // 接收服务的信息
        $data = fread($conn, 65535);
        // \strlen($data) === 0;
        // dd(strlen($data), '接收服务的信息');
        if ('' === $data || false === $data) {
            $this->checkConn($data, $conn);
        } else {
            call_user_func($this->events['receive'], $this, $conn, $data);
        }
    }
    // 校验连接
    protected function checkConn($buffer, $conn)
    {
        if (\strlen($buffer) === 0) {
            if (! \get_resource_type($conn) == "Unknown"){
                // 断开连接
                $this->close($conn);
            }
            \call_user_func($this->events['close'], $this, $conn );
            unset($this->sockets[(int) $conn]);
        }
    }


}
