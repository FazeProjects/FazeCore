<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>


class SetEntityLinkPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::SET_ENTITY_LINK_PACKET;
	
	const TYPE_REMOVE = 0;
	const TYPE_RIDE = 1;
	const TYPE_PASSENGER = 2;

	public $from;
	public $to;
	public $type;

	/**
	 *
	 */
	public function decode(){
        $this->from = $this->getEntityId();
        $this->to = $this->getEntityId();
        $this->type = $this->getByte();
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		$this->putEntityId($this->from);
		$this->putEntityId($this->to);
		$this->putByte($this->type);
	}

	/**
	 * @return string Current packet name
	 */
	public function getName(){
		return "SetEntityLinkPacket";
	}
	
	public static function create(
		int $from,
		int $to,
		int $type
	) : SetEntityLinkPacket {
		$pk = new SetEntityLinkPacket();
		$pk->from = $from;
		$pk->to = $to;
		$pk->type = $type;

		return $pk;
	}
}
