<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>


class BlockEntityDataPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::BLOCK_ENTITY_DATA_PACKET;

	public $x;
	public $y;
	public $z;
	public $namedtag;

	/**
	 *
	 */
	public function decode(){
		$this->getBlockCoords($this->x, $this->y, $this->z);
		$this->namedtag = $this->getRemaining();
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		$this->putBlockCoords($this->x, $this->y, $this->z);
		$this->put($this->namedtag);
	}

	/**
	 * @return string Current packet name
	 */
	public function getName(){
		return "BlockEntityDataPacket";
	}

}
