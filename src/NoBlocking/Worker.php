<?php

namespace ioMode\NoBlocking;

use function GuzzleHttp\Psr7\str;
use ioMode\CoreBase;

class Noblocking extends CoreBase
{
    public function __construct($host, $port, $type = 'tcp')
    {
        parent::__construct($host, $port, $type);
        stream_set_blocking($this->server, 0);
    }

    protected function accept()
    {

        // TODO: Implement accept() method.
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