<?php



namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\level\Level;

class GlowingRedstoneOre extends RedstoneOre implements SolidLight {

	protected $id = self::GLOWING_REDSTONE_ORE;

	protected $itemId = self::REDSTONE_ORE;

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Glowing Redstone Ore";
	}

	/**
	 * @return int
	 */
	public function getLightLevel(){
		return 9;
	}

	/**
	 * @param $type
	 *
	 * @return bool|int
	 */
	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_SCHEDULED or $type === Level::BLOCK_UPDATE_RANDOM){
			$this->getLevel()->setBlock($this, Block::get(Item::REDSTONE_ORE, $this->meta), false, false);

			return Level::BLOCK_UPDATE_WEAK;
		}

		return false;
	}

}