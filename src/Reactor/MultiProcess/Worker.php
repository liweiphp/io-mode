<?php
/**
 * Created by PhpStorm.
 * User: weili
 * Date: 2021-01-20
 * Time: 14:40
 */


namespace ioMode\Reactor\MultiProcess;
use ioMode\Reactor\Worker as CoreBase;

class Worker extends CoreBase
{
    public $config = [
        'work_num' => 4
    ];

    public function fork()
    {
        $pid = -10;
        $work_num = $this->config['work_num'];
        for ($i=0; $i<$work_num; $i++) {
            if ($pid==-10 || $pid>0) {
                $pid = pcntl_fork();
                $this->accept();
            }
        }
    }

    public function start()
    {
        dd('启动服务');
        $this->check();
        $this->fork();
    }

}