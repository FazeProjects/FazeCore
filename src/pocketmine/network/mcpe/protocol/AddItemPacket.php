<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>


class AddItemPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::ADD_ITEM_PACKET;

	public $item;

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
		$this->putSlot($this->item);
	}

	/**
	 * @return AddItemPacket|string
	 */
	public function getName(){
		return "AddItemPacket";
	}

}