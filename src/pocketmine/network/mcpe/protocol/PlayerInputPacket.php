<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>


class PlayerInputPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::PLAYER_INPUT_PACKET;

	public $motionX;
	public $motionY;
	public $unknownBool1;
	public $unknownBool2;

	/**
	 *
	 */
	public function decode(){
		$this->motionX = $this->getLFloat();
		$this->motionY = $this->getLFloat();
		$this->unknownBool1 = $this->getBool();
		$this->unknownBool2 = $this->getBool();
	}

	/**
	 *
	 */
	public function encode(){

	}

	/**
	 * @return string Current packet name
	 */
	public function getName(){
		return "PlayerInputPacket";
	}

}
