<?php
require_once __DIR__ . "/../../vendor/autoload.php";

use ioMode\Signal\Worker;

$server = new Worker('192.168.56.102', 9160);

$server->on('receive', function ($server, $fd, $data) {
    echo 'receive:'.$data."\n";
    sleep(2);
    $server->send($fd, 'world1');
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
