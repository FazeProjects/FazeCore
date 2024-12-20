<?php



namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\Player;

class Quartz extends Solid {

	const QUARTZ_NORMAL = 0;
	const QUARTZ_CHISELED = 1;
	const QUARTZ_PILLAR = 2;
	const QUARTZ_PILLAR2 = 3;


	protected $id = self::QUARTZ_BLOCK;

	/**
	 * Quartz constructor.
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
			0 => "Quartz Block",
			1 => "Chiseled Quartz Block",
			2 => "Quartz Pillar",
			3 => "Quartz Block",
		];
		return $names[$this->meta & 0x03];
	}

	/**
	 * @param Item        $item
	 * @param Block       $block
	 * @param Block       $target
	 * @param int         $face
	 * @param float       $fx
	 * @param float       $fy
	 * @param float       $fz
	 * @param Player|null $player
	 *
	 * @return bool
	 */
	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		if($this->meta === 1 or $this->meta === 2){
			//Quartz pillar block and chiselled quartz have different orientations
			$faces = [
				0 => 0,
				1 => 0,
				2 => 0b1000,
				3 => 0b1000,
				4 => 0b0100,
				5 => 0b0100,
			];
			$this->meta = ($this->meta & 0x03) | $faces[$face];
		}
		$this->getLevel()->setBlock($block, $this, true, true);
		return true;
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
				[Item::QUARTZ_BLOCK, $this->meta & 0x03, 1],
			];
		}else{
			return [];
		}
	}
}