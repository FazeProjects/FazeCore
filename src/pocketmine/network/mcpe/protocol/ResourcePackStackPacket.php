<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>

use pocketmine\resourcepacks\ResourcePack;

class ResourcePackStackPacket extends DataPacket {
	const NETWORK_ID = ProtocolInfo::RESOURCE_PACK_STACK_PACKET;

	public $mustAccept = false;

	/** @var ResourcePack[] */
	public $behaviorPackStack = [];
	/** @var ResourcePack[] */
	public $resourcePackStack = [];

	/**
	 *
	 */
	public function decode(){
		/*$this->mustAccept = $this->getBool();
		$behaviorPackCount = $this->getLShort();
		while($behaviorPackCount-- > 0){
		    $packId = $this->getString();
		    $version = $this->getString();
		    $this->behaviorPackStack[] = new ResourcePackInfoEntry($packId, $version);
		}

		$resourcePackCount = $this->getLShort();
		while($resourcePackCount-- > 0){
		    $packId = $this->getString();
		    $version = $this->getString();
		    $this->resourcePackStack[] = new ResourcePackInfoEntry($packId, $version);
		}*/
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		$this->putBool($this->mustAccept);

		$this->putUnsignedVarInt(count($this->behaviorPackStack));
		foreach($this->behaviorPackStack as $entry){
			$this->putString($entry->getPackId());
			$this->putString($entry->getPackVersion());
		}

		$this->putUnsignedVarInt(count($this->resourcePackStack));
		foreach($this->resourcePackStack as $entry){
			$this->putString($entry->getPackId());
			$this->putString($entry->getPackVersion());
		}
	}
}