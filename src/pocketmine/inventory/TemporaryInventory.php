<?php



namespace pocketmine\inventory;

use pocketmine\Player;

abstract class TemporaryInventory extends ContainerInventory {
	//TODO

	/**
	 * @return mixed
	 */
	abstract public function getResultSlotIndex();


	/**
	 * @param Player $who
	 */
	public function onClose(Player $who){
		foreach($this->getContents() as $slot => $item){
			if($slot === $this->getResultSlotIndex()){
				//Do not drop the item in the result slot - it is a virtual item and does not actually exist.
				continue;
			}
			$who->dropItem($item);
		}
		$this->clearAll();
	}
}