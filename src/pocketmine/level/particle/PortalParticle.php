<?php



namespace pocketmine\level\particle;

use pocketmine\math\Vector3;

class PortalParticle extends GenericParticle {
	/**
	 * PortalParticle constructor.
	 *
	 * @param Vector3 $pos
	 */
	public function __construct(Vector3 $pos){
		parent::__construct($pos, Particle::TYPE_PORTAL);
	}
}
