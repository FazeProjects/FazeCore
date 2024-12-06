<?php



namespace pocketmine\level\particle;

use pocketmine\block\Block;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelEventPacket;

class DestroyBlockParticle extends Particle {

	protected $data;

	/**
	 * DestroyBlockParticle constructor.
	 *
	 * @param Vector3 $pos
	 * @param Block   $b
	 */
	public function __construct(Vector3 $pos, Block $b){
		parent::__construct($pos->x, $pos->y, $pos->z);
		$this->data = $b->getId() | ($b->getDamage() << 8);
	}

	/**
	 * @return LevelEventPacket
	 */
	public function encode(){
		$pk = new LevelEventPacket;
		$pk->evid = LevelEventPacket::EVENT_PARTICLE_DESTROY;
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->data = $this->data;

		return $pk;
	}
}
