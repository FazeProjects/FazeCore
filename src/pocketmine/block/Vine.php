<?php



namespace pocketmine\block;

use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\level\Level;
use pocketmine\math\AxisAlignedBB;
use pocketmine\Player;
use pocketmine\math\Vector3;

class Vine extends Flowable{
	const FLAG_SOUTH = 0x01;
    const FLAG_WEST = 0x02;
	const FLAG_NORTH = 0x04;
	const FLAG_EAST = 0x08;

	protected $id = self::VINE;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Vines";
	}

	public function getHardness(){
		return 0.2;
	}

	public function canPassThrough(){
		return true;
	}

	public function hasEntityCollision(){
		return true;
	}

	public function canClimb() : bool{
		return true;
	}

	public function canBeReplaced(){
		return true;
	}

	public function onEntityCollide(Entity $entity){
		$entity->resetFallDistance();
	}

	protected function recalculateBoundingBox(){

		$minX = 1;
		$minY = 1;
		$minZ = 1;
		$maxX = 0;
		$maxY = 0;
		$maxZ = 0;

		$flag = $this->meta > 0;

		if(($this->meta & self::FLAG_WEST) > 0){
			$maxX = max($maxX, 0.0625);
			$minX = 0;
			$minY = 0;
			$maxY = 1;
			$minZ = 0;
			$maxZ = 1;
			$flag = true;
		}

		if(($this->meta & self::FLAG_EAST) > 0){
			$minX = min($minX, 0.9375);
			$maxX = 1;
			$minY = 0;
			$maxY = 1;
			$minZ = 0;
			$maxZ = 1;
			$flag = true;
		}

		if(($this->meta & self::FLAG_SOUTH) > 0){
			$minZ = min($minZ, 0.9375);
			$maxZ = 1;
			$minX = 0;
			$maxX = 1;
			$minY = 0;
			$maxY = 1;
			$flag = true;
		}

		//TODO: Missing NORTH check

		if(!$flag and $this->getSide(Vector3::SIDE_UP)->isSolid()){
			$minY = min($minY, 0.9375);
			$maxY = 1;
			$minX = 0;
			$maxX = 1;
			$minZ = 0;
			$maxZ = 1;
		}

		return new AxisAlignedBB(
			$this->x + $minX,
			$this->y + $minY,
			$this->z + $minZ,
			$this->x + $maxX,
			$this->y + $maxY,
			$this->z + $maxZ
		);
	}

	public function place(Item $item, Block $blockReplace, Block $blockClicked, $face, $fx, $fy, $fz, Player $player = null){
		if(!$blockClicked->isSolid() or $face === Vector3::SIDE_UP or $face === Vector3::SIDE_DOWN){
			return false;
		}

		$faces = [
			Vector3::SIDE_NORTH => self::FLAG_SOUTH,
			Vector3::SIDE_SOUTH => self::FLAG_NORTH,
			Vector3::SIDE_WEST => self::FLAG_EAST,
			Vector3::SIDE_EAST => self::FLAG_WEST
		];

		$this->meta = $faces[$face] ?? 0;
		if($blockReplace->getId() === $this->getId()){
			$this->meta |= $blockReplace->meta;
		}

		$this->getLevel()->setBlock($blockReplace, $this, true, true);
		return true;
	}

	/**
	 * @param int $type
	 *
	 * @return bool
	 */
	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
			$sides = [
				self::FLAG_SOUTH => Vector3::SIDE_SOUTH,
				self::FLAG_WEST => Vector3::SIDE_WEST,
				self::FLAG_NORTH => Vector3::SIDE_NORTH,
				self::FLAG_EAST => Vector3::SIDE_EAST
			];

			$meta = $this->meta;

			foreach($sides as $flag => $side){
				if(($meta & $flag) === 0){
					continue;
				}

				if(!$this->getSide($side)->isSolid()){
					$meta &= ~$flag;
				}
			}

			if($meta !== $this->meta){
				if($meta === 0){
					$this->level->useBreakOn($this);
				}else{
					$this->meta = $meta;
					$this->level->setBlock($this, $this);
				}

				return Level::BLOCK_UPDATE_NORMAL;
			}
		}elseif($type === Level::BLOCK_UPDATE_RANDOM){
			//TODO: vine growth
		}

		return false;
	}

	/**
	 * @param Item $item
	 *
	 * @return array
	 */
	public function getDrops(Item $item) : array{
		if($item->isShears()){
			return [
				[$this->id, 0, 1],
			];
		}else{
			return [];
		}
	}

	/**
	 * @return int
	 */
	public function getToolType(){
		return Tool::TYPE_SHEARS;
	}
}