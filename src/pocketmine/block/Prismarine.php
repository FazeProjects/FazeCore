<?php



namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\Tool;

class Prismarine extends Solid {

	const NORMAL = 0;
	const DARK = 1;
	const BRICKS = 2;

	protected $id = self::PRISMARINE;

	/**
	 * Prismarine constructor.
	 *
	 * @param int $meta
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return float
	 */
	public function getHardness(){
		return 1.5;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		static $names = [
			self::NORMAL => "Prismarine",
			self::DARK => "Dark Prismarine",
			self::BRICKS => "Prismarine Bricks",
		];
		return $names[$this->meta & 0x03] ?? "Unknown";
	}

	/**
	 * @return int
	 */
	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}

	/**
	 * @param Item $item
	 *
	 * @return array
	 */
	public function getDrops(Item $item) : array{
		if($item->isPickaxe() >= Tool::TIER_WOODEN){
			return [
				[$this->id, $this->meta & 0x03, 1],
			];
		}else{
			return [];
		}
	}
}