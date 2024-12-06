<?php


 
namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>

class ResourcePackChunkRequestPacket extends DataPacket {
	const NETWORK_ID = ProtocolInfo::RESOURCE_PACK_CHUNK_REQUEST_PACKET;

	public $packId;
	public $chunkIndex;

	/**
	 *
	 */
	public function decode(){
		$this->packId = $this->getString();
		$this->chunkIndex = $this->getLInt();
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		$this->putString($this->packId);
		$this->putLInt($this->chunkIndex);
	}
}