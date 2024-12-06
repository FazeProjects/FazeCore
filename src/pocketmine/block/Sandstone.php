<?php



namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\Tool;

class Sandstone extends Solid {

	const NORMAL = 0;
	const CHISELED = 1;
	const SMOOTH = 2;

	protected $id = self::SANDSTONE;

	/**
	 * Sandstone constructor.
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
		return 0.8;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		static $names = [
			0 => "Sandstone",
			1 => "Chiseled Sandstone",
			2 => "Smooth Sandstone"
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
		if($item->isPickaxe() >= 1){
			return [
				[$this->id, $this->meta & 0x03, 1],
			];
		}else{
			return [];
		}
	}

}