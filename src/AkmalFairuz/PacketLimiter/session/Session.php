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

    public function check(){
        if($this->packets >= $this->manager->packetLimit) {
            $this->addWarning();
        }

        $this->packets = 0;
    }
}