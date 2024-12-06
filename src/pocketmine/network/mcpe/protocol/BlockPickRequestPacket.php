<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>


class BlockPickRequestPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::BLOCK_PICK_REQUEST_PACKET;

	public $x;
	public $y;
	public $z;
	public $unknown;

	/**
	 *
	 */
	public function decode(){
		$this->getBlockCoords($this->x, $this->y, $this->z);
		$this->unknown = $this->getByte();
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
		return "BlockPickRequestPacket";
	}

}
