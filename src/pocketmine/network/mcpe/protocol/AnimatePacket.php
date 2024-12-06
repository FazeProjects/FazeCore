<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>


class AnimatePacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::ANIMATE_PACKET;

	const ACTION_SWING_ARM = 1;

	const ACTION_STOP_SLEEP = 3;
	const ACTION_CRITICAL_HIT = 4;
	const ACTION_ROW_RIGHT = 128;
	const ACTION_ROW_LEFT = 129;

	public $action;
	public $eid;
	public $float = 0.0; //TODO (Boat rowing time?)

	/**
	 *
	 */
	public function decode(){
		$this->action = $this->getVarInt();
		$this->eid = $this->getEntityId();
		if($this->float & 0x80){
			$this->float = $this->getLFloat();
		}
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		$this->putVarInt($this->action);
		$this->putEntityId($this->eid);
		if($this->float & 0x80){
			$this->putLFloat($this->float);
		}
	}

}
