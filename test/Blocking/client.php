<?php

require_once __DIR__."/../../vendor/autoload.php";

echo "client start \n";
$client =  stream_socket_client('tcp://192.168.56.102:9160', $erron, $error, 60);
//stream_set_blocking($client, 0);

fwrite($client, 'hello');
//while (!feof($client)) {
$data = fread($client, 65535)."\n";
dd($data, '一直获取data');

fwrite($client, 'hello2');
sleep(1);
//}

fclose($client);

