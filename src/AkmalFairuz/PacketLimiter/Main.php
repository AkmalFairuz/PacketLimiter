<?php

declare(strict_types=1);

namespace AkmalFairuz\PacketLimiter;

use AkmalFairuz\PacketLimiter\session\SessionManager;
use AkmalFairuz\PacketLimiter\task\Task;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener{

    public function onEnable(): void {
        $cfg = $this->getConfig();
        $maxWarn = $cfg->get("maximum_warning", 5);
        $packetLimit = $cfg->get("packet_per_second", 250);
        $kickMessage = $cfg->get("kick_message", "You sending too many packets!");
        SessionManager::create($maxWarn, $packetLimit, $kickMessage);
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getScheduler()->scheduleRepeatingTask(new Task(), 1);
    }

    /**
     * @param DataPacketReceiveEvent $event
     * @priority MONITOR
     */
    public function onPacketReceive(DataPacketReceiveEvent $event) {
        $player = $event->getOrigin()->getPlayer();
        if($player instanceof Player) {
            SessionManager::getInstance()->get($player)->addPacket();
        }
    }

    /**
     * @param PlayerQuitEvent $event
     * @priority MONITOR
     */
    public function onPlayerQuit(PlayerQuitEvent $event) {
        SessionManager::getInstance()->remove($event->getPlayer());
    }
}