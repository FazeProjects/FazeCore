<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>


class UpdateBlockPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::UPDATE_BLOCK_PACKET;

	const FLAG_NONE = 0b0000;
	const FLAG_NEIGHBORS = 0b0001;
	const FLAG_NETWORK = 0b0010;
	const FLAG_NOGRAPHIC = 0b0100;
	const FLAG_PRIORITY = 0b1000;

	const FLAG_ALL = (self::FLAG_NEIGHBORS | self::FLAG_NETWORK);
	const FLAG_ALL_PRIORITY = (self::FLAG_ALL | self::FLAG_PRIORITY);

	public $x;
	public $z;
	public $y;
	public $blockId;
	public $blockData;
	public $flags;

	/**
	 *
	 */
	public function decode(){
		$this->getBlockCoords($this->x, $this->y, $this->z);
		$this->blockId = $this->getUnsignedVarInt();

		$mask = $this->getUnsignedVarInt();
		$this->blockData = $mask & 0x0F;
		$this->flags = $mask >> 4;
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		$this->putBlockCoords($this->x, $this->y, $this->z);
		$this->putUnsignedVarInt($this->blockId);
		$this->putUnsignedVarInt(($this->flags << 4) | $this->blockData);
	}

	/**
	 * @return string Current packet name
	 */
	public function getName(){
		return "UpdateBlockPacket";
	}

}