<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>

class CommandStepPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::COMMAND_STEP_PACKET;

	public $command;
	public $overload;
	public $uvarint1;
	public $currentStep;
	public $done;
	public $clientId;
	public $inputJson;
	public $outputJson;

	/**
	 *
	 */
	public function decode(){
		$this->command = $this->getString();
		$this->overload = $this->getString();
		$this->uvarint1 = $this->getUnsignedVarInt();
		$this->currentStep = $this->getUnsignedVarInt();
		$this->done = $this->getBool();
		$this->clientId = $this->getUnsignedVarLong();
		$this->inputJson = json_decode($this->getString());
		$this->outputJson = $this->getString();

		$this->getRemaining();
	}

	/**
	 *
	 */
	public function encode(){

	}

}
