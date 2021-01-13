<?php
require_once __DIR__."/../../vendor/autoload.php";

use ioMode\HttpServer\Worker;

$server = new Worker('192.168.56.102', 9160);

$server->on('request', function ($request, $response, $fd) {
    dd("request event");
    $response->end($fd, 'hello world');
});




$server->start();
