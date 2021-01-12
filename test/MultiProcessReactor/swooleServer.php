<?php
require_once __DIR__ . "/../../vendor/autoload.php";

use ioMode\SwooleAsync\MultiProcess\Worker;

$server = new Worker('192.168.56.102', 9160);

$server->on('receive', function ($server, $fd, $data) {
    dd($data, "recevie data");
    dd(posix_getpid(), "server PID");
    $server->send($fd, "i am server\n");
//    $server->close($fd);

});

$server->on('connect', function ($server, $fd){
    echo "connect \n";
//    $server->close($fd);
});

$server->on('close', function ($server) {
    echo "close \n";
});


$server->start();
