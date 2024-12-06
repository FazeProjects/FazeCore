<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>

class CommandBlockUpdatePacket extends DataPacket {
	const NETWORK_ID = ProtocolInfo::COMMAND_BLOCK_UPDATE_PACKET;

	public $isBlock;

	public $x;
	public $y;
	public $z;
	public $commandBlockMode;
	public $isRedstoneMode;
	public $isConditional;

	public $minecartEid;

	public $command;
	public $lastOutput;
	public $name;

	public $shouldTrackOutput;

	/**
	 *
	 */
	public function decode(){
		$this->isBlock = $this->getBool();

		if($this->isBlock){
			$this->getBlockCoords($this->x, $this->y, $this->z);
			$this->commandBlockMode = $this->getUnsignedVarInt();
			$this->isRedstoneMode = $this->getBool();
			$this->isConditional = $this->getBool();
		}else{
			//Minecart with command block
			$this->minecartEid = $this->getEntityId();
		}

		$this->command = $this->getString();
		$this->lastOutput = $this->getString();
		$this->name = $this->getString();

		$this->shouldTrackOutput = $this->getBool();
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		$this->putBool($this->isBlock);

		if($this->isBlock){
			$this->putBlockCoords($this->x, $this->y, $this->z);
			$this->putUnsignedVarInt($this->commandBlockMode);
			$this->putBool($this->isRedstoneMode);
			$this->putBool($this->isConditional);
		}else{
			$this->putEntityId($this->minecartEid);
		}

		$this->putString($this->command);
		$this->putString($this->lastOutput);
		$this->putString($this->name);

		$this->putBool($this->shouldTrackOutput);
	}

}