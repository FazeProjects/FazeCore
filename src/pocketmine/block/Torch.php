<?php



namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\level\Level;
use pocketmine\Player;

class Torch extends Flowable {

	protected $id = self::TORCH;

	/**
	 * Torch constructor.
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

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Torch";
	}


	/**
	 * @param int $type
	 *
	 * @return bool|int
	 */
	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
			$below = $this->getSide(0);
			$meta = $this->getDamage();
			static $faces = [
				0 => Vector3::SIDE_DOWN,
			    1 => Vector3::SIDE_WEST,
			    2 => Vector3::SIDE_EAST,
			    3 => Vector3::SIDE_NORTH,
			    4 => Vector3::SIDE_SOUTH,
			    5 => Vector3::SIDE_DOWN
			];
			$face = $faces[$meta] ?? Vector3::SIDE_DOWN;

			if($this->getSide($face)->isTransparent() === true and
				!($face === 0 and ($below->getId() === self::FENCE or
						$below->getId() === self::COBBLE_WALL or
						$below->getId() == Block::REDSTONE_LAMP or
						$below->getId() == Block::LIT_REDSTONE_LAMP)
				)
			){
				$this->getLevel()->useBreakOn($this);

				return Level::BLOCK_UPDATE_NORMAL;
			}
		}

		return false;
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
		$below = $this->getSide(0);

		if($target->isTransparent() === false and $face !== 0){
			$faces = [
				1 => 5,
				2 => 4,
				3 => 3,
				4 => 2,
				5 => 1,
			];
			$this->meta = $faces[$face];
			$this->getLevel()->setBlock($block, $this, true, true);

			return true;
		}elseif(
			$below->isTransparent() === false or $below->getId() === self::FENCE or
			$below->getId() === self::COBBLE_WALL or
			$below->getId() == Block::REDSTONE_LAMP or
			$below->getId() == Block::LIT_REDSTONE_LAMP
		){
			$this->meta = 0;
			$this->getLevel()->setBlock($block, $this, true, true);

			return true;
		}

		return false;
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