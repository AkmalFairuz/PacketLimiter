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

    public function onRun(): void {
        if(($time = microtime(true) - $this->lastCheck) >= 1) {
            $this->check($time);
            $this->lastCheck = microtime(true);
        }
    }

    private function check(float $time) {
        SessionManager::getInstance()->check($time);
    }
}