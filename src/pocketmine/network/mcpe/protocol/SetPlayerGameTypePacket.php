<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>


class SetPlayerGameTypePacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::SET_PLAYER_GAME_TYPE_PACKET;

	public $gamemode;

	/**
	 *
	 */
	public function decode(){
		$this->gamemode = $this->getVarInt();
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		$this->putVarInt($this->gamemode);
	}

	/**
	 * @return string Current packet name
	 */
	public function getName(){
		return "SetPlayerGameTypePacket";
	}

}