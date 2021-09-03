<?php

declare(strict_types=1);

namespace AkmalFairuz\PacketLimiter\session;

use pocketmine\Player;

class Session{

    /** @var SessionManager */
    private $manager;

    /** @var Player */
    private $player;

    /** @var int */
    private $warnings = 0;

    /** @var int */
    private $packets;

    public function __construct(SessionManager $manager, Player $player) {
        $this->player = $player;
        $this->manager = $manager;
    }

    private function addWarning() {
        if($this->warnings >= $this->manager->maxWarn) {
            $this->player->kick($this->manager->kickMessage, false);
            return;
        }
        ++$this->warnings;
    }

    public function addPacket() {
        ++$this->packets;
    }

    public function check(float $time){
        if($time >= 1.2) {
            $limit = intval($this->manager->packetLimit * $time);
        } else {
            $limit = $this->manager->packetLimit;
        }
        if($this->packets >= $limit) {
            $this->addWarning();
        }

        $this->packets = 0;
    }
}