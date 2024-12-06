<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>


class MobEquipmentPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::MOB_EQUIPMENT_PACKET;

	public $eid;
	public $item;
	public $slot;
	public $selectedSlot;
	public $windowId = 0;

	/**
	 *
	 */
	public function decode(){
		$this->eid = $this->getEntityId(); //EntityRuntimeID
		$this->item = $this->getSlot();
		$this->slot = $this->getByte();
		$this->selectedSlot = $this->getByte();
		$this->windowId = $this->getByte();
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		$this->putEntityId($this->eid); //EntityRuntimeID
		$this->putSlot($this->item);
		$this->putByte($this->slot);
		$this->putByte($this->selectedSlot);
		$this->putByte($this->windowId);
	}

}
