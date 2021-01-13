<?php
/**
 * Created by PhpStorm.
 * User: weili
 * Date: 2021-01-22
 * Time: 16:20
 */

namespace ioMode\HttpServer;


class Response
{

    public function end($fd, $content)
    {
        $result = "HTTP/1.1 200 OK\r\n";
        $result .= "Content-Type: text/html;charset=UTF-8\r\n";
        $result .= "Connection: keep-alive\r\n";
        $result .= "Server: php socket server\r\n";
        $result .= "Content-length: ".strlen($content)."\r\n\r\n";
        $result .= $content;
        fwrite($fd, $result);
    }

}