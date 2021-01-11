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
                $this->event['connect']($this, $conn);
                $data = fread($conn, 65535);
                $this->event['receive']($this, $conn, $data);

                if (get_resource_type($conn)=="Unknown") {
                    dd($conn, 'close event');
                    $this->event['close']($this, $conn);
                }
            }

//            sleep(5);
        }

    }
}