<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>


class ContainerOpenPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::CONTAINER_OPEN_PACKET;

	public $windowid;
	public $type;
	public $x;
	public $y;
	public $z;
	public $entityId = -1;

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
		$this->putByte($this->windowid);
		$this->putByte($this->type);
		$this->putBlockCoords($this->x, $this->y, $this->z);
		$this->putEntityId($this->entityId);
	}

}