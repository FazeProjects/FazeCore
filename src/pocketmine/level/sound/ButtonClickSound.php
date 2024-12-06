<?php



namespace pocketmine\level\sound;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelEventPacket;

class ButtonClickSound extends GenericSound {
	/**
	 * ButtonClickSound constructor.
	 *
	 * @param Vector3 $pos
	 */
	public function __construct(Vector3 $pos){
		parent::__construct($pos, LevelEventPacket::EVENT_REDSTONE_TRIGGER);
	}
}