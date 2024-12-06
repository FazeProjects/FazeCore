<?php



namespace pocketmine\level\sound;

use pocketmine\math\Vector3;

class SpellSound extends Sound {
	//TODO: fix this

	private $id;
	private $color;

	/**
	 * SpellSound constructor.
	 *
	 * @param Vector3 $pos
	 * @param int     $r
	 * @param int     $g
	 * @param int     $b
	 */
	public function __construct(Vector3 $pos, $r = 0, $g = 0, $b = 0){
		/*parent::__construct($pos->x, $pos->y, $pos->z);
		$this->id = (int) LevelEventPacket::EVENT_SOUND_SPELL;
		$this->color = ($r << 16 | $g << 8 | $b) & 0xffffff;*/
	}

	/**
	 * @return null
	 */
	public function encode(){
		return null;
		/*$pk = new LevelEventPacket;
		$pk->evid = $this->id;
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->data = $this->color;

		return $pk;*/
	}
}