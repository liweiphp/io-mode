<?php
/**
 * Created by PhpStorm.
 * User: weili
 * Date: 2021-01-20
 * Time: 16:00
 */

namespace ioMode\MultiBlocking;
use ioMode\Blocking\Worker as CoreBase;

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
            }

            if ($pid<0) {
                dd("error", "multiBlocking fork create process error");
            } elseif ($pid==0){
                $this->accept();
            }
        }

        for ($i=0; $i<$work_num; $i++) {
            $result = pcntl_wait($status);
            dd($result, "result");
            dd($status, "status");
        }
    }

    public function start()
    {
        dd('启动服务');
        $this->check();
        $this->fork();
    }

}
