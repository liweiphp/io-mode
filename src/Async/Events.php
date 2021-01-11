<?php
/**
 * Event class
 * User: weili
 * Date: 2021-01-18
 * Time: 21:37
 */
declare(strict_types=1);

namespace ioMode\Async;
use \Event;


class Events
{
    protected $conn;
    protected $eventBase;
    protected const READ = \Event::READ | \Event::PERSIST;
    protected const WRITE = Event::WRITE | \Event::PERSIST;
    protected const ALL = \Event::WRITE | \Event::READ | \Event::PERSIST;

    public function __construct($eventBase, $conn)
    {
        $this->eventBase = $eventBase;
        $this->conn = $conn;
    }

    public function handle(Worker $worker, &$count)
    {
        $event = new \Event($this->eventBase, $this->conn, self::ALL, function ($socket) use ($worker, &$count){
            $worker->sendMessage($socket);
            $count[(int) $socket][self::ALL]->free();
        });
        $event->add();
        $count[(int) $this->conn][self::ALL] = $event;

    }

}
