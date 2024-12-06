<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>


class ClientToServerHandshakePacket extends DataPacket {
	const NETWORK_ID = ProtocolInfo::CLIENT_TO_SERVER_HANDSHAKE_PACKET;

	/**
	 * @return bool
	 */
	public function canBeSentBeforeLogin() : bool{
		return true;
	}

	/**
	 *
	 */
	public function decode(){
		//No payload
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		//No payload
	}
}
