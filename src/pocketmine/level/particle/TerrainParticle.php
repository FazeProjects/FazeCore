<?php



namespace pocketmine\level\particle;

use pocketmine\block\Block;
use pocketmine\math\Vector3;

class TerrainParticle extends GenericParticle {
	/**
	 * TerrainParticle constructor.
	 *
	 * @param Vector3 $pos
	 * @param Block   $b
	 */
	public function __construct(Vector3 $pos, Block $b){
		parent::__construct($pos, Particle::TYPE_TERRAIN, ($b->getDamage() << 8) | $b->getId());
	}
}
