<?php

declare(strict_types=1);

namespace AkmalFairuz\PacketLimiter\session;

use pocketmine\Player;

class SessionManager{

    /** @var self */
    private static $instance;

    public static function create(int $maxWarn, int $packetLimit, string $kickMessage){
        self::$instance = new self($maxWarn, $packetLimit, $kickMessage);
    }

    public static function getInstance() : self {
        return self::$instance;
    }

    /** @var Session[] */
    private $sessions = []; // TODO: better data structure

    /** @var int */
    public $maxWarn;

    /** @var int */
    public $packetLimit;

    /** @var string */
    public $kickMessage;

    private function __construct(int $maxWarn, int $packetLimit, string $kickMessage) {
        $this->maxWarn = $maxWarn;
        $this->packetLimit = $packetLimit;
        $this->kickMessage = $kickMessage;
    }

    public function add(Player $player) {
        $this->sessions[spl_object_hash($player)] = new Session($this, $player);
    }

    public function remove(Player $player) {
        unset($this->sessions[spl_object_hash($player)]);
    }

    public function get(Player $player) : Session {
        $session = $this->sessions[spl_object_hash($player)] ?? null;
        if($session === null) {
            $this->add($player);
            return self::get($player);
        }
        return $session;
    }

    public function check(){
        foreach($this->sessions as $session) {
            $session->check();
        }
    }
}