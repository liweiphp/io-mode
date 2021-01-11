<?php
require_once __DIR__ . "/../../vendor/autoload.php";

use ioMode\Async\Worker;

$server = new Worker('192.168.56.102', 9160);

$server->on('receive', function ($server, $fd, $data) {
    echo 'receive:'.$data."\n";
    $server->send($fd, "i am server \n");
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
