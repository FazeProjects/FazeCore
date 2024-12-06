<?php


 
namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>

class ResourcePackChunkDataPacket extends DataPacket {
	const NETWORK_ID = ProtocolInfo::RESOURCE_PACK_CHUNK_DATA_PACKET;

	public $packId;
	public $chunkIndex;
	public $progress;
	public $data;

	/**
	 *
	 */
	public function decode(){
		$this->packId = $this->getString();
		$this->chunkIndex = $this->getLInt();
		$this->progress = $this->getLLong();
		$this->data = $this->get($this->getLInt());
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		$this->putString($this->packId);
		$this->putLInt($this->chunkIndex);
		$this->putLLong($this->progress);
		$this->putLInt(strlen($this->data));
		$this->put($this->data);
	}
}