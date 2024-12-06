<?php



namespace pocketmine\level\particle;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelEventPacket;

class GenericParticle extends Particle {

	protected $id;
	protected $data;

	/**
	 * GenericParticle constructor.
	 *
	 * @param Vector3 $pos
	 * @param int     $id
	 * @param int     $data
	 */
	public function __construct(Vector3 $pos, $id, $data = 0){
		parent::__construct($pos->x, $pos->y, $pos->z);
		$this->id = $id & 0xFFF;
		$this->data = $data;
	}

	/**
	 * @return LevelEventPacket
	 */
	public function encode(){
		$pk = new LevelEventPacket;
		$pk->evid = LevelEventPacket::EVENT_ADD_PARTICLE_MASK | $this->id;
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->data = $this->data;

		return $pk;
	}
}
