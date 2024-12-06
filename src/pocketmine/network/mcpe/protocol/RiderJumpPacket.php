<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>


class RiderJumpPacket extends DataPacket {
	const NETWORK_ID = ProtocolInfo::RIDER_JUMP_PACKET;

	public $unknown;

	/**
	 *
	 */
	public function decode(){
		$this->unknown = $this->getVarInt();
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		$this->putVarInt($this->unknown);
	}
}