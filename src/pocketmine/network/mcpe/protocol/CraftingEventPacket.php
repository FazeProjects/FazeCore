<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>


class CraftingEventPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::CRAFTING_EVENT_PACKET;

	public $windowId;
	public $type;
	public $id;
	public $input = [];
	public $output = [];

	/**
	 * @return $this
	 */
	public function clean(){
		$this->input = [];
		$this->output = [];

		return parent::clean();
	}

	/**
	 *
	 */
	public function decode(){
		$this->windowId = $this->getByte();
		$this->type = $this->getVarInt();
		$this->id = $this->getUUID();

		$size = $this->getUnsignedVarInt();
		for($i = 0; $i < $size && $i < 128 && !$this->feof(); ++$i){
			$this->input[] = $this->getSlot();
		}

		$size = $this->getUnsignedVarInt();
		for($i = 0; $i < $size && $i < 128 && !$this->feof(); ++$i){
			$this->output[] = $this->getSlot();
		}
	}

	/**
	 *
	 */
	public function encode(){

	}

	/**
	 * @return string Current packet name
	 */
	public function getName(){
		return "CraftingEventPacket";
	}

}
