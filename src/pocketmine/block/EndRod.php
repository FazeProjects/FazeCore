<?php



namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Vector3;
use pocketmine\Player;

class EndRod extends Flowable {

	protected $id = self::END_ROD;

	/**
	 * EndRod constructor.
	 *
	 * @param int $meta
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return int
	 */
	public function getLightLevel(){
		return 14;
	}

	protected function recalculateBoundingBox(){
		$m = $this->meta & ~0x01;
		$width = 0.375;

		switch($m){
			case 0x00: //up/down
				return new AxisAlignedBB(
					$this->x + $width,
					$this->y,
					$this->z + $width,
					$this->x + 1 - $width,
					$this->y + 1,
					$this->z + 1 - $width
				);
			case 0x02: //north/south
				return new AxisAlignedBB(
					$this->x,
					$this->y + $width,
					$this->z + $width,
					$this->x + 1,
					$this->y + 1 - $width,
					$this->z + 1 - $width
				);
			case 0x04: //east/west
				return new AxisAlignedBB(
					$this->x + $width,
					$this->y + $width,
					$this->z,
					$this->x + 1 - $width,
					$this->y + 1 - $width,
					$this->z + 1
				);
		}

		return null;
	}

	/**
	 * @return string
	 */
	public function getName(){
		return "End Rod";
	}

	/**
	 * @return int
	 */
	public function getResistance(){
		return 0;
	}

	/**
	 * @return int
	 */
	public function getHardness(){
		return 0;
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
			1 => 1,
			2 => 3,
			3 => 2,
			4 => 5,
			5 => 4,
		];
		$this->meta = ($target->getId() === self::END_ROD && $faces[$face] == $target->getDamage()) ? Vector3::getOppositeSide($faces[$face]) : $faces[$face];
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
