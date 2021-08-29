<?php

declare(strict_types=1);

namespace AkmalFairuz\PacketLimiter;

use pocketmine\scheduler\Task as PMTask;
use pocketmine\Server;

class Task extends PMTask{

    /** @var Main */
    private $plugin;

    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }

    public function onRun(int $currentTick){
        foreach($this->plugin->packetPerSecond as $key => $val) {
            if($val >= $this->plugin->packetLimit) {
                $this->plugin->warning[$key]++;
                if($this->plugin->warning[$key] >= $this->plugin->maxWarning) {
                    $player = Server::getInstance()->getOnlinePlayers()[$key];
                    $this->plugin->getLogger()->warning($player->getName() . " was kicked due to reach packet limit");
                    $player->kick($this->plugin->kickMessage, false);
                    unset($this->plugin->packetPerSecond[$key]);
                    unset($this->plugin->warning[$key]);
                    continue;
                }
            }
            $this->plugin->packetPerSecond[$key] = 0;
        }
    }
}