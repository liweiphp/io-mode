<?php
namespace ioMode\Blocking;

use ioMode\CoreBase;

class Worker extends CoreBase
{
    protected function accept()
    {
        while (true) {
            if ($this->type == 'udp') {
                //udp
                do {
                    $data = stream_socket_recvfrom($this->server, 1024, 0, $peer);
                    dd($data, 'data');
                    echo "\n";
                    var_dump($data);
                    stream_socket_sendto($this->server, date("D M j H:i:s Y\r\n"), 0, $peer);
                } while ($data !== false);
            } else {
                //tcp
                $conn = stream_socket_accept($this->server);
                if ($conn) {
                    call_user_func($this->events['connect'], $this, $conn);
                    $data = fread($conn, 65535);
                    dd("receive event");
                    call_user_func($this->events['receive'], $this, $conn, $data);
    
                    if (get_resource_type($conn)=="Unknown") {
                        dd($conn, 'close event');
                        call_user_func($this->events['close'], $this, $conn);
                    }
                }
            }

//            sleep(5);
        }

    }
}