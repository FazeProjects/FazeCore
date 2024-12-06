<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>


class SetCommandsEnabledPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::SET_COMMANDS_ENABLED_PACKET;

	public $enabled;

	/**
	 *
	 */
	public function decode(){

	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		$this->putBool($this->enabled);
	}

	/**
	 * @return string Current packet name
	 */
	public function getName(){
		return "SetCommandsEnabledPacket";
	}

}