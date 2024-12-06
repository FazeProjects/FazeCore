<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>

use pocketmine\resourcepacks\ResourcePackInfoEntry;

class ResourcePacksInfoPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::RESOURCE_PACKS_INFO_PACKET;

	public $mustAccept = false; //force client to use selected resource packs
	/** @var ResourcePackInfoEntry */
	public $behaviorPackEntries = [];
	/** @var ResourcePackInfoEntry */
	public $resourcePackEntries = [];

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

		$this->putBool($this->mustAccept);
		$this->putLShort(count($this->behaviorPackEntries));
		foreach($this->behaviorPackEntries as $entry){
			$this->putString($entry->getPackId());
			$this->putString($entry->getPackVersion());
			$this->putLLong($entry->getPackSize());
		}
		$this->putLShort(count($this->resourcePackEntries));
		foreach($this->resourcePackEntries as $entry){
			$this->putString($entry->getPackId());
			$this->putString($entry->getPackVersion());
			$this->putLLong($entry->getPackSize());
		}
	}
}