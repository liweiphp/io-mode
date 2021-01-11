<?php


require_once __DIR__."/../../vendor/autoload.php";

use ioMode\Blocking;

$server = stream_socket_server('tcp://192.168.56.102:9160', $erron, $error);

$eventBase = new EventBase();
$event = new Event($eventBase, $server, Event::READ | Event::PERSIST, function ($server) use ($eventBase){
    $conn = stream_socket_accept($server);
    dd($conn);
    $event1 = new Event($eventBase, $conn, Event::READ | Event::PERSIST, function ($server) {
        echo fread($server, 65536);
    });
    $event1->add();
});

$event->add();
$eventBase->loop();