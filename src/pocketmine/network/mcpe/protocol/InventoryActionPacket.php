<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>

class InventoryActionPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::INVENTORY_ACTION_PACKET;

	const ACTION_GIVE_ITEM = 0;
	const ACTION_ENCHANT_ITEM = 2;

	public $actionId;
	public $item;
	public $enchantmentId = 0;
	public $enchantmentLevel = 0;

	/**
	 *
	 */
	public function decode(){
		$this->actionId = $this->getUnsignedVarInt();
		$this->item = $this->getSlot();
		$this->enchantmentId = $this->getVarInt();
		$this->enchantmentLevel = $this->getVarInt();
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		$this->putUnsignedVarInt($this->actionId);
		$this->putSlot($this->item);
		$this->putVarInt($this->enchantmentId);
		$this->putVarInt($this->enchantmentLevel);
	}

}