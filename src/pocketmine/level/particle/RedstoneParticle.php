<?php



namespace pocketmine\level\particle;

use pocketmine\math\Vector3;

class RedstoneParticle extends GenericParticle {
	/**
	 * RedstoneParticle constructor.
	 *
	 * @param Vector3 $pos
	 * @param int     $lifetime
	 */
	public function __construct(Vector3 $pos, $lifetime = 1){
		parent::__construct($pos, Particle::TYPE_REDSTONE, $lifetime);
	}
}
