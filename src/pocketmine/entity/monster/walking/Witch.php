<?php



namespace pocketmine\entity;

use pocketmine\network\mcpe\protocol\AddEntityPacket;
use pocketmine\Player;

class Witch extends WalkingMonster implements Ageable{
	const NETWORK_ID = 45;

	public $dropExp = [5, 5];

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Witch";
	}

	public function initEntity(){
		$this->setMaxHealth(26);
		parent::initEntity();
	}

	/**
	 * @param Player $player
	 */

	/**
	 * @return array
	 */
	public function getDrops(){
		//TODO
		return [];
	}
}
