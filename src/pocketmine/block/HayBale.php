<?php



namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\Player;

class HayBale extends Solid {

	protected $id = self::HAY_BALE;

	/**
	 * HayBale constructor.
	 *
	 * @param int $meta
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Hay Bale";
	}

	/**
	 * @return float
	 */
	public function getHardness(){
		return 0.5;
	}

	/**
	 * @return int
	 */
	public function getBurnChance() : int{
		return 60;
	}

	/**
	 * @return int
	 */
	public function getBurnAbility() : int{
		return 20;
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
		$faces = [
			0 => 0,
			1 => 0,
			2 => 0b1000,
			3 => 0b1000,
			4 => 0b0100,
			5 => 0b0100,
		];

		$this->meta = ($this->meta & 0x03) | $faces[$face];
		$this->getLevel()->setBlock($block, $this, true, true);

		return true;
	}

	/**
	 * @param Item $item
	 *
	 * @return array
	 */
	public function getDrops(Item $item) : array{
		return [
			[$this->id, 0, 1],
		];
	}

}