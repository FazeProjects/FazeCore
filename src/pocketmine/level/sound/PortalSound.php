<?php



namespace pocketmine\level\sound;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelEventPacket;

class PortalSound extends GenericSound {
	/**
	 * FizzSound constructor.
	 *
	 * @param Vector3 $pos
	 * @param int     $pitch
	 */
	public function __construct(Vector3 $pos, $pitch = 0){
		parent::__construct($pos, LevelEventPacket::EVENT_SOUND_PORTAL, $pitch);
	}
}
