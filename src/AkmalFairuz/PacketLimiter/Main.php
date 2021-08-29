<?php

declare(strict_types=1);

namespace AkmalFairuz\PacketLimiter;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\BatchPacket;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener{

    /** @var int[] */
    public $packetPerSecond = [];

    /** @var int[] */
    public $warning = [];

    /** @var int */
    public $packetLimit;

    /** @var int */
    public $maxWarning;

    /** @var string */
    public $kickMessage;

    public function onEnable(){
        $this->maxWarning = $this->getConfig()->get("maximum_warning", 5);
        $this->packetLimit = $this->getConfig()->get("packet_per_second", 250);
        $this->kickMessage = $this->getConfig()->get("kick_message", "You sending too many packets!");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getScheduler()->scheduleRepeatingTask(new Task($this), 20);
    }

    /**
     * @param DataPacketReceiveEvent $event
     * @priority MONITOR
     */
    public function onPacketReceive(DataPacketReceiveEvent $event) {
        $packet = $event->getPacket();
        if($packet instanceof BatchPacket) {
            return;
        }
        $player = $event->getPlayer();
        if(!isset($this->packetPerSecond[spl_object_hash($player)])) {
            $this->packetPerSecond[spl_object_hash($player)] = 0;
        }
        $this->packetPerSecond[spl_object_hash($player)]++;
    }

    /**
     * @param PlayerQuitEvent $event
     * @priority MONITOR
     */
    public function onPlayerQuit(PlayerQuitEvent $event) {
        unset($this->packetPerSecond[spl_object_hash($event->getPlayer())]);
    }
}