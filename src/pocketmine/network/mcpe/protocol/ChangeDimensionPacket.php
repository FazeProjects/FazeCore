<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>

class ChangeDimensionPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::CHANGE_DIMENSION_PACKET;

	const DIMENSION_NORMAL = 0;
	const DIMENSION_NETHER = 1;
	const DIMENSION_END = 2;

	public $dimension;

	public $x;
	public $y;
	public $z;
	public $respawn = false;

	public function decode(){
		$this->dimension = $this->getVarInt();
		$this->getVector3f($this->x, $this->y, $this->z);
		$this->respawn = $this->getBool();
	}

	public function encode(){
		$this->reset();
		$this->putVarInt($this->dimension);
		$this->putVector3f($this->x, $this->y, $this->z);
		$this->putBool($this->respawn);
	}

}