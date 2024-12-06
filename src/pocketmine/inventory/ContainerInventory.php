<?php



namespace pocketmine\inventory;

use pocketmine\entity\Entity;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\ContainerClosePacket;
use pocketmine\network\mcpe\protocol\ContainerOpenPacket;
use pocketmine\Player;

abstract class ContainerInventory extends BaseInventory{
	/**
	 * @param Player $who
	 */
	public function onOpen(Player $who){
		parent::onOpen($who);
		$pk = new ContainerOpenPacket();
		$pk->windowid = $who->getWindowId($this);
		$pk->type = $this->getType()->getNetworkType();
		$holder = $this->getHolder();

		$pk->x = $pk->y = $pk->z = 0;
		$pk->entityId = -1;

		if($holder instanceof Entity){
			$pk->entityId = $holder->getId();
		}else{
			$pk->x = $holder->getFloorX();
			$pk->y = $holder->getFloorY();
			$pk->z = $holder->getFloorZ();
		}

		$who->dataPacket($pk);

		$this->sendContents($who);
	}

	/**
	 * @param Player $who
	 */
	public function onClose(Player $who){
		$pk = new ContainerClosePacket();
		$pk->windowid = $who->getWindowId($this);
		$who->dataPacket($pk);
		parent::onClose($who);
	}
}