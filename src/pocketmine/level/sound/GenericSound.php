<?php



namespace pocketmine\level\sound;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelEventPacket;

class GenericSound extends Sound {

	/**
	 * GenericSound constructor.
	 *
	 * @param Vector3 $pos
	 * @param int     $id
	 * @param int     $pitch
	 */
	public function __construct(Vector3 $pos, $id, $pitch = 0){
		parent::__construct($pos->x, $pos->y, $pos->z);
		$this->id = (int) $id;
		$this->pitch = (float) $pitch * 1000;
	}

	protected $pitch = 0;
	protected $id;

	/**
	 * @return float
	 */
	public function getPitch(){
		return $this->pitch / 1000;
	}

	/**
	 * @param $pitch
	 */
	public function setPitch($pitch){
		$this->pitch = (float) $pitch * 1000;
	}


	/**
	 * @return LevelEventPacket
	 */
	public function encode(){
		$pk = new LevelEventPacket;
		$pk->evid = $this->id;
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->data = (int) $this->pitch;

		return $pk;
	}

}
