<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>

class SetTitlePacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::SET_TITLE_PACKET;

	const TYPE_CLEAR = 0;
	const TYPE_RESET = 1;
	const TYPE_TITLE = 2;
	const TYPE_SUB_TITLE = 3;
	const TYPE_ACTION_BAR = 4;
	const TYPE_TIMES = 5;

	public $type;
	public $title;
	public $fadeInDuration;
	public $duration;
	public $fadeOutDuration;

	/**
	 *
	 */
	public function decode(){
		$this->type = $this->getVarInt();
		$this->title = $this->getString();
		$this->fadeInDuration = $this->getVarInt();
		$this->duration = $this->getVarInt();
		$this->fadeOutDuration = $this->getVarInt();
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		$this->putVarInt($this->type);
		$this->putString($this->title);
		$this->putVarInt($this->fadeInDuration);
		$this->putVarInt($this->duration);
		$this->putVarInt($this->fadeOutDuration);
	}

	/**
	 * @return string Current packet name
	 */
	public function getName(){
		return "SetTitlePacket";
	}

}
