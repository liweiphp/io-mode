<?php
namespace ioMode\Multiplex;

use ioMode\CoreBase;

class Worker extends CoreBase
{
    protected $sockets;
    public function __construct($host, $port, $type = 'tcp')
    {
        parent::__construct($host, $port, $type);
        stream_set_blocking($this->server, 0);
        $this->sockets[(int) $this->server] = $this->server;

    }

    /**
     * 多路复用
     */
    protected function accept()
    {
        // TODO: Implement accept() method.
        while (true) {
            $reads = $this->sockets;
            stream_select($reads,$write, $except, 60);
            foreach ($reads as $key=>$socket) {
                dd($reads, '$reads');
                dd($socket, '$socket');
                dd($key, '$key');
                dd($this->sockets, '$this->sockets');
                if ($socket == $this->server) { //server 链接 获取client socket
                    $conn = $this->createConn();
                    if ($conn) {
                        dd($conn, '$conn');
                        $this->sockets[(int) $conn] = $conn;
                    }
                } else {
                    $this->sendMessage($socket);
                }
            }
        }
    }

    /**
     * 创建新对链接
     * @return bool|resource
     */

    protected function createConn()
    {
        $conn = stream_socket_accept($this->server);
        if ($conn) {
            $this->event['connect']($this, $conn);
        }
        return $conn;
    }

    protected function sendMessage($socket)
    {
        $data = fread($socket, 65535);
        dd($data, '$data');
        if ($data) {
            $this->event['receive']($this, $socket, $data);
        }
        $this->checkConn($data, $socket);
    }

    protected function checkConn($buffer, $socket)
    {
        //如果链接关闭 则从socket中销毁资源
        if (strlen($buffer) === 0) {
            if (get_resource_type($socket) != "Unknown") {
                $this->close($socket);
            }
            dd($socket, 'close event');
            call_user_func($this->event['close'], $this, $socket);
            unset($this->sockets[(int) $socket]);
        }
    }
}