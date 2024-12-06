<?php



namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\level\Level;
use pocketmine\Player;
use pocketmine\math\Vector3;

class SnowLayer extends Flowable{

	protected $id = self::SNOW_LAYER;

	/**
	 * SnowLayer constructor.
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
		return "Snow Layer";
	}

	/**
	 * @return bool
	 */
	public function canBeReplaced(){
		return $this->meta < 7; //8 snow layers
	}

	/**
	 * @return float
	 */
	public function getHardness(){
		return 0.1;
	}

	/**
	 * @return int
	 */
	public function getToolType(){
		return Tool::TYPE_SHOVEL;
	}

	private function canBeSupportedBy(Block $b) : bool{
		return $b->isSolid() or ($b->getId() === $this->getId() and $b->getDamage() === 7);
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
		if($block->getId() === $this->getId() and $block->getDamage() < 7){
			$this->setDamage($block->getDamage() + 1);
		}

		if($this->canBeSupportedBy($block->getSide(Vector3::SIDE_DOWN))){
			$this->getLevel()->setBlock($block, $this, true);

			return true;
		}

		return false;
	}

	/**
	 * @param int $type
	 *
	 * @return bool|int
	 */
	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
			if(!$this->canBeSupportedBy($this->getSide(Vector3::SIDE_DOWN))){
				$this->getLevel()->setBlock($this, new Air(), false, false);

				return Level::BLOCK_UPDATE_NORMAL;
			}
		}

		return false;
	}

	/**
	 * @param Item $item
	 *
	 * @return array
	 */
	public function getDrops(Item $item) : array{
		if($item->isShovel() !== false){
			return [
				[Item::SNOWBALL, 0, 1],
			];
		}

		return [];
	}
}