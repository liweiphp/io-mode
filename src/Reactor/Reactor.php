<?php
namespace ioMode\Reactor;
use \EventBase;
use \Event;

class Reactor
{
    protected static $instance;
    public static $eventBase;
    protected $events = [];
    const READ = \Event::READ | \Event::PERSIST;
    const WRITE = Event::WRITE | \Event::PERSIST;
    const ALL = \Event::WRITE | \Event::READ | \Event::PERSIST;

    protected function __construct()
    {
        self::$eventBase = new EventBase();
    }

    public static function getInstance()
    {
        if (!self::$instance){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function add($socket, $what, $cb, $arg = '')
    {
        switch ($what) {
            case self::READ:
                $event = new Event(self::$eventBase, $socket, self::READ, $cb);
                break;

            case self::WRITE:
                $event = new Event(self::$eventBase, $socket, self::WRITE, $cb);
                break;

            default:
                $event = new Event(self::$eventBase, $socket, self::ALL, $cb);
                break;
        }
        $event->add();
        $this->events[(int) $socket] = $event;
    }

    public function del($socket)
    {
        $this->events[(int) $socket]->free();
        unset($this->events[(int) $socket]);
    }

    public function loop()
    {
        self::$eventBase->loop();
    }

}