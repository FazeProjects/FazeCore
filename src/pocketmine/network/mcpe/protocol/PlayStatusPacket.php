<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>


class PlayStatusPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::PLAY_STATUS_PACKET;

	const LOGIN_SUCCESS = 0;
	const LOGIN_FAILED_CLIENT = 1;
	const LOGIN_FAILED_SERVER = 2;
	const PLAYER_SPAWN = 3;
	const LOGIN_FAILED_INVALID_TENANT = 4;
	const LOGIN_FAILED_VANILLA_EDU = 5;
	const LOGIN_FAILED_EDU_VANILLA = 6;

    /** @var int */
	public $status;

	/**
	 *
	 */
	public function decode(){
		$this->status = $this->getInt();
	}

	public function canBeSentBeforeLogin() : bool{
		return true;
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		$this->putInt($this->status);
	}

}
