<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>


class BlockEventPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::BLOCK_EVENT_PACKET;

	public $x;
	public $y;
	public $z;
	public $case1;
	public $case2;

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
		$this->putBlockCoords($this->x, $this->y, $this->z);
		$this->putVarInt($this->case1);
		$this->putVarInt($this->case2);
	}

	/**
	 * @return string Current packet name
	 */
	public function getName(){
		return "BlockEventPacket";
	}

}