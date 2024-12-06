<?php



namespace pocketmine\level\particle;

use pocketmine\math\Vector3;

class ExplodeParticle extends GenericParticle {
	/**
	 * ExplodeParticle constructor.
	 *
	 * @param Vector3 $pos
	 */
	public function __construct(Vector3 $pos){
		parent::__construct($pos, Particle::TYPE_EXPLODE);
	}
}
