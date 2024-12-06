<?php




namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>

class ResourcePackDataInfoPacket extends DataPacket {
	const NETWORK_ID = ProtocolInfo::RESOURCE_PACK_DATA_INFO_PACKET;

	public $packId;
	public $maxChunkSize;
	public $chunkCount;
	public $compressedPackSize;
	public $sha256;

	/**
	 *
	 */
	public function decode(){
		$this->packId = $this->getString();
		$this->maxChunkSize = $this->getLInt();
		$this->chunkCount = $this->getLInt();
		$this->compressedPackSize = $this->getLLong();
		$this->sha256 = $this->getString();
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		$this->putString($this->packId);
		$this->putLInt($this->maxChunkSize);
		$this->putLInt($this->chunkCount);
		$this->putLLong($this->compressedPackSize);
		$this->putString($this->sha256);
	}
}