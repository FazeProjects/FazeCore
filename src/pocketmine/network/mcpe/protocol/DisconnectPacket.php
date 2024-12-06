<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>


class DisconnectPacket extends DataPacket {
	const NETWORK_ID = ProtocolInfo::DISCONNECT_PACKET;

    /** @var bool */
	public $hideDisconnectionScreen = false;
	/** @var string */
	public $message = "";

	public function canBeSentBeforeLogin() : bool{
		return true;
	}

	/**
	 *
	 */
	public function decode(){
		$this->hideDisconnectionScreen = $this->getBool();
		if(!$this->hideDisconnectionScreen){
			$this->message = $this->getString();
		}
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		$this->putBool($this->hideDisconnectionScreen);
		if(!$this->hideDisconnectionScreen){
			$this->putString($this->message);
		}
	}

}
