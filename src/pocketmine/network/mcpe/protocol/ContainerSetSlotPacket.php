<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>

use pocketmine\item\Item;

class ContainerSetSlotPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::CONTAINER_SET_SLOT_PACKET;

	public $windowid;
	public $slot;
	/** @var Item */
	public $item;
	public $hotbarSlot = 0;
	public $selectSlot = 0;

	/**
	 *
	 */
	public function decode(){
		$this->windowid = $this->getByte();
		$this->slot = $this->getVarInt();
		$this->hotbarSlot = $this->getVarInt();
		$this->item = $this->getSlot();
		$this->selectSlot = $this->getByte();
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		$this->putByte($this->windowid);
		$this->putVarInt($this->slot);
		$this->putVarInt($this->hotbarSlot);
		$this->putSlot($this->item);
		$this->putByte($this->selectSlot);
	}

	/**
	 * @return string Current packet name
	 */
	public function getName(){
		return "ContainerSetSlotPacket";
	}

}
