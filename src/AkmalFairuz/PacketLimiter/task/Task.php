<?php

declare(strict_types=1);

namespace AkmalFairuz\PacketLimiter\task;

use AkmalFairuz\PacketLimiter\session\SessionManager;
use pocketmine\scheduler\Task as PMTask;

class Task extends PMTask{

    /** @var int */
    private $lastCheck;

    public function __construct() {
        $this->lastCheck = microtime(true);
    }

    public function onRun(int $currentTick){
        if(microtime(true) - $this->lastCheck >= 1) {
            $this->check();
            $this->lastCheck = microtime(true);
        }
    }

    private function check() {
        SessionManager::getInstance()->check();
    }
}