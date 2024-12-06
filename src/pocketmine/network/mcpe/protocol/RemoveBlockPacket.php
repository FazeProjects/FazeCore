<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>


class RemoveBlockPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::REMOVE_BLOCK_PACKET;

	public $x;
	public $y;
	public $z;

	/**
	 *
	 */
	public function decode(){
		$this->getBlockCoords($this->x, $this->y, $this->z);
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
		return "RemoveBlockPacket";
	}

}
