<?php



namespace pocketmine\level\particle;

use pocketmine\math\Vector3;

class HeartParticle extends GenericParticle {
	/**
	 * HeartParticle constructor.
	 *
	 * @param Vector3 $pos
	 * @param int     $scale
	 */
	public function __construct(Vector3 $pos, $scale = 0){
		parent::__construct($pos, Particle::TYPE_HEART, $scale);
	}
}
