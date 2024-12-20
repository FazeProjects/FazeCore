<?php



namespace pocketmine\level\sound;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;

class MinecraftSound extends Sound {

	protected $soundName = "";
	protected $volume = 1;
	protected $pitch = 1;

	/**
	 * MinecraftSound constructor.
	 *
	 * @param Vector3 $pos
	 * @param string  $soundName
	 * @param float   $colume
	 * @param float   $pitch
	 */
	public function __construct(Vector3 $pos, string $soundName, float $volume = 1, float $pitch = 1){
		parent::__construct($pos->x, $pos->y, $pos->z);
		$this->soundName = $soundName;
		$this->volume = $volume;
		$this->pitch = $pitch;
	}

	public function encode(){
		$pk = new PlaySoundPacket();
		$pk->sound = $this->soundName;
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->volume = $this->volume;
		$pk->float = $this->pitch;

		return $pk;
	}
}