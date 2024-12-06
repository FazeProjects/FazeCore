<?php


 
namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>


class SetTimePacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::SET_TIME_PACKET;

	public $time;

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
		$this->putVarInt($this->time);
	}

	/**
	 * @return string Current packet name
	 */
	public function getName(){
		return "SetTimePacket";
	}

}