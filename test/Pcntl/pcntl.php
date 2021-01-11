<?php
/**
 * Created by PhpStorm.
 * User: weili
 * Date: 2021-01-20
 * Time: 08:01
 */


$pid = pcntl_fork();

echo "输出pid\n";
//echo posix_getpid()."===\n";
echo posix_getppid()."+++\n";
echo "输出pid结束\n";