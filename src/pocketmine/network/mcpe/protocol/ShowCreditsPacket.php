<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>

class ShowCreditsPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::SHOW_CREDITS_PACKET;

	public $eid;
	public $type;

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
		$this->putEntityId($this->eid);
		$this->putVarInt($this->type);
	}

	/**
	 * @return string Current packet name
	 */
	public function getName(){
		return "ShowCreditsPacket";
	}

}