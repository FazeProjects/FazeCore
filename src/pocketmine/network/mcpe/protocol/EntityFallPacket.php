<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>


class EntityFallPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::ENTITY_FALL_PACKET;

	public $entityRuntimeId;
	public $fallDistance;
	public $bool1;

	/**
	 *
	 */
	public function decode(){
		$this->entityRuntimeId = $this->getEntityId();
		$this->fallDistance = $this->getLFloat();
		$this->bool1 = $this->getBool();
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		$this->putEntityId($this->entityRuntimeId);
		$this->putLFloat($this->fallDistance);
		$this->putBool($this->bool1);
	}

}
