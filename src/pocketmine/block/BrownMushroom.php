<?php



namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\Player;

class BrownMushroom extends Flowable {

	protected $id = self::BROWN_MUSHROOM;

	/**
	 * BrownMushroom constructor.
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
		return "Brown Mushroom";
	}

	/**
	 * @return int
	 */
	public function getLightLevel(){
		return 1;
	}

	/**
	 * @param int $type
	 *
	 * @return bool|int
	 */
	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
			if($this->getSide(0)->isTransparent() === true){
				$this->getLevel()->useBreakOn($this);

				return Level::BLOCK_UPDATE_NORMAL;
			}
		}

		return false;
	}

	protected function recalculateBoundingBox(){
		return null;
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
		$down = $this->getSide(0);
		if($down->isTransparent() === false){
			$this->getLevel()->setBlock($block, $this, true, true);

			return true;
		}

		return false;
	}

}