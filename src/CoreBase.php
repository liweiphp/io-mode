<?php
namespace ioMode;

abstract class CoreBase
{
    protected $server;
    protected $config;
    public $events;
    protected $type = 'tcp';
    public function __construct($host, $port, $type = 'tcp')
    {
        $this->type = $type;
        $this->server = stream_socket_server($type.'://'.$host.':'.$port, $erron, $error);
    }

    /**
     * 开始服务
     *
     */
    public function start()
    {
        $this->check();
        $this->accept();
    }

    /**
     * 链接方法
     * @return mixed
     */
    abstract protected function accept();

    /**
     * 注册事件
     * @param $event
     * @param $call
     */
    public function on($event, $call)
    {
        $this->events[$event] = $call;
    }

    /**
     * 发送数据
     * @param $fd
     * @param $data
     */
    public function send($fd, $data)
    {
        fwrite($fd, $data);
    }

    /**
     * 检测注册事件
     */
    public function check () {
        if ($this->type == 'tcp') {
            if (empty($this->events['connect']) || ! is_callable($this->events['connect']) ) {
                dd("tcp服务必须要有回调事件: connect");
                exit;
            }

            if (empty($this->events['receive']) || ! is_callable($this->events['receive']) ) {
                dd("tcp服务必须要有回调事件: receive");
                exit;
            }

            if (empty($this->events['close']) || ! is_callable($this->events['close']) ) {
                dd("tcp服务必须要有回调事件: close");
                exit;
            }
        } else if ($this->type == 'http') {
            if (empty($this->events['request']) || ! is_callable($this->events['request']) ) {
                dd("http服务必须要有回调事件: request");
                exit;
            }
        } else if ($this->type == 'http'){

        }
    }

    /**
     * 关闭socket
     * @param $fd
     */
    public function close ($fd) {
        fclose($fd);
    }

}