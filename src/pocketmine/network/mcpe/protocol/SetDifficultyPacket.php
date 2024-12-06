<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>


class SetDifficultyPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::SET_DIFFICULTY_PACKET;

	public $difficulty;

	/**
	 *
	 */
	public function decode(){
		$this->difficulty = $this->getUnsignedVarInt();
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		$this->putUnsignedVarInt($this->difficulty);
	}

	/**
	 * @return string Current packet name
	 */
	public function getName(){
		return "SetDifficultyPacket";
	}


}