<?php



namespace pocketmine\level\particle;

use pocketmine\math\Vector3;

class InkParticle extends GenericParticle {
	/**
	 * InkParticle constructor.
	 *
	 * @param Vector3 $pos
	 * @param int     $scale
	 */
	public function __construct(Vector3 $pos, $scale = 0){
		parent::__construct($pos, Particle::TYPE_INK, $scale);
	}
}
