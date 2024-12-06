<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>

use pocketmine\item\Item;

class DropItemPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::DROP_ITEM_PACKET;

	public $type;
	/** @var Item */
	public $item;

	/**
	 *
	 */
	public function decode(){
		$this->type = $this->getByte();
		$this->item = $this->getSlot();
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		$this->putByte($this->type);
		$this->putSlot($this->item);
	}

	/**
	 * @return string Current packet name
	 */
	public function getName(){
		return "DropItemPacket";
	}

}
