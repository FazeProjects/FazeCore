<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>

use pocketmine\item\Item;

class MobArmorEquipmentPacket extends DataPacket{

	const NETWORK_ID = ProtocolInfo::MOB_ARMOR_EQUIPMENT_PACKET;

	public $eid;

	/** @var Item[] */
	public $slots = [];

	/**
	 *
	 */
	public function decode(){
		$this->eid = $this->getEntityId();
		$this->slots[0] = $this->getSlot();
		$this->slots[1] = $this->getSlot();
		$this->slots[2] = $this->getSlot();
		$this->slots[3] = $this->getSlot();
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		$this->putEntityId($this->eid);
		$this->putSlot($this->slots[0]);
		$this->putSlot($this->slots[1]);
		$this->putSlot($this->slots[2]);
		$this->putSlot($this->slots[3]);
	}

}
