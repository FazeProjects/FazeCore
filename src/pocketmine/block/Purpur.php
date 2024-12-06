<?php



namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\Tool;

class Purpur extends Solid {

	protected $id = self::PURPUR;

	/**
	 * Purpur constructor.
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

	public function getResistance(){
		return 30;
	}

	/**
	 * @return int
	 */
	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		static $names = [
			0 => "Purpur Block",
			2 => "Purpur Pillar",
		];

		return $names[$this->meta & 0x0f] ?? "Purpur Block"; //TODO fix properly;
	}

	/**
	 * @param Item $item
	 *
	 * @return array
	 */
	public function getDrops(Item $item) : array{
		if($item->isPickaxe() >= Tool::TIER_WOODEN){

			return [
				[$this->id, $this->meta & 0x0f, 1],
			];

		}else{

			return [];

		}
	}

}