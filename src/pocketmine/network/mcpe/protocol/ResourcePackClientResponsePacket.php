<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>


class ResourcePackClientResponsePacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::RESOURCE_PACK_CLIENT_RESPONSE_PACKET;

	const STATUS_REFUSED = 1;
	const STATUS_SEND_PACKS = 2;
	const STATUS_HAVE_ALL_PACKS = 3;
	const STATUS_COMPLETED = 4;

	public $status;
	public $packIds = [];

	/**
	 *
	 */
	public function decode(){
		$this->status = $this->getByte();
		$entryCount = $this->getLShort();
		$this->packIds = [];
		while($entryCount-- > 0 && !$this->feof()){
			$this->packIds[] = $this->getString();
		}
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		$this->putByte($this->status);
		$this->putLShort(count($this->packIds));
		foreach($this->packIds as $id){
			$this->putString($id);
		}
	}
}