<?php



namespace pocketmine\level\sound;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelEventPacket;

class GraySplashSound extends GenericSound {
	/**
	 * GraySplashSound constructor.
	 *
	 * @param Vector3 $pos
	 * @param int     $pitch
	 */
	public function __construct(Vector3 $pos, $pitch = 0){
		parent::__construct($pos, LevelEventPacket::EVENT_CAULDRON_TAKE_WATER, $pitch);
	}
}