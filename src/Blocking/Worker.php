<?php
namespace ioMode\Blocking;

use ioMode\CoreBase;

class Worker extends CoreBase
{
    protected function accept()
    {
        while (true) {
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

//            sleep(5);
        }

    }
}