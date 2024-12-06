<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>

class PlaySoundPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::PLAY_SOUND_PACKET;

	/** @var string */
	public $sound;
	/** @var float */
	public $x;
	/** @var float */
	public $y;
	/** @var float */
	public $z;
	/** @var float */
	public $volume;
	/** @var float */
	public $float;

	/**
	 *
	 */
	public function decode(){
		$this->sound = $this->getString();
		$this->getBlockCoords($this->x, $this->y, $this->z);
		$this->x /= 8;
		$this->y /= 8;
		$this->z /= 8;
		$this->volume = $this->getLFloat();
		$this->float = $this->getLFloat();
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		$this->putString($this->sound);
		$this->putBlockCoords((int) ($this->x * 8), (int) ($this->y * 8), (int) ($this->z * 8));
		$this->putLFloat($this->volume);
		$this->putLFloat($this->float);
	}

	/**
	 * @return string Current packet name
	 */
	public function getName(){
		return "PlaySoundPacket";
	}

}
