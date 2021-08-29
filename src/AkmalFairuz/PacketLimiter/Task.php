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
        foreach(Server::getInstance()->getOnlinePlayers() as $player) {
            $key = spl_object_hash($player);
            $val = $this->plugin->packetPerSecond[$key];
            if($val >= $this->plugin->packetLimit) {
                if(!isset($this->plugin->warning[$key])) {
                    $this->plugin->warning[$key] = 0;
                }
                $this->plugin->warning[$key]++;
                if($this->plugin->warning[$key] >= $this->plugin->maxWarning) {
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