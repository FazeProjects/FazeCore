<?php



namespace pocketmine\block;

use pocketmine\item\Item;

class UnpoweredRepeater extends PoweredRepeater {
	protected $id = self::UNPOWERED_REPEATER_BLOCK;

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Unpowered Repeater";
	}

	/**
	 * @return int
	 */
	public function getStrength(){
		return 0;
	}

	/**
	 * @param Block|null $from
	 *
	 * @return bool
	 */
	public function isActivated(Block $from = null){
		return false;
	}

	/**
	 * @param Item $item
	 *
	 * @return mixed|void
	 */
	public function onBreak(Item $item){
		$this->getLevel()->setBlock($this, new Air(), true);
	}
}