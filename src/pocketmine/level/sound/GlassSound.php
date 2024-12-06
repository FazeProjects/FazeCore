<?php



namespace pocketmine\level\sound;

use pocketmine\block\Block;
use pocketmine\math\Vector3;
use pocketmine\level\sound\GenericSound;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;

class GlassSound extends GenericSound {

	public function __construct(Vector3 $pos){
		parent::__construct($pos, LevelSoundEventPacket::SOUND_GLASS, 1);
	}

	/**
	 * @return LevelEventPacket
	 */
	public function encode(){
		$pk = new LevelSoundEventPacket;
		$pk->sound = $this->id;
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;

		return $pk;
	}
}
