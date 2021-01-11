<?php


function sig_handler ($sig)
{
    var_dump($sig);
    switch ($sig) {
        case SIGIO:
            echo "信号处理\n";
    }
}


pcntl_signal(SIGIO, 'sig_handler');

posix_kill(posix_getpid(), SIGIO);

pcntl_signal_dispatch();


while (true){}