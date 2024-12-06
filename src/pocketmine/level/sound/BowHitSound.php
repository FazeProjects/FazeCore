<?php



namespace pocketmine\level\sound;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;

class BowHitSound extends GenericSound {

	public function __construct(Vector3 $pos){
		parent::__construct($pos, LevelSoundEventPacket::SOUND_BOW_HIT, 1);
	}
	
	public function encode(){
		$pk = new LevelSoundEventPacket;
		$pk->sound = $this->id;
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;

		return $pk;
	}
}
