<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>


class PlayerListPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::PLAYER_LIST_PACKET;

	const TYPE_ADD = 0;
	const TYPE_REMOVE = 1;

	//REMOVE: UUID, ADD: UUID, entity id, name, skinId, skin
	/** @var array[] */
	public $entries = [];
	public $type;

	/**
	 * @return $this
	 */
	public function clean(){
		$this->entries = [];

		return parent::clean();
	}

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
		$this->putByte($this->type);
		$this->putUnsignedVarInt(count($this->entries));
		foreach($this->entries as $d){
			if($this->type === self::TYPE_ADD){
				$this->putUUID($d[0]);
				$this->putEntityId($d[1]);
				$this->putString($d[2]);
				$this->putString($d[3]);
				$this->putString($d[4]);
			}else{
				$this->putUUID($d[0]);
			}
		}
	}

	/**
	 * @return string Current packet name
	 */
	public function getName(){
		return "PlayerListPacket";
	}

}
