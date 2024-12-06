<?php


namespace pocketmine\level\sound;

use pocketmine\math\Vector3;

class ThunderSound extends GenericSound {

    /**
     * @param Vector3 $pos
     * Создатель: KlainYT
     */
    public function __construct(Vector3 $pos, $pitch = 0){
        parent::__construct($pos, LevelEventPacket::THUNDER, $pitch); // скоро
    }
}