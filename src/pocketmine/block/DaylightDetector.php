<?php



namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use pocketmine\tile\DLDetector;
use pocketmine\tile\Tile;

class DaylightDetector extends RedstoneSource {
	protected $id = self::DAYLIGHT_SENSOR;

	//protected $hasStartedUpdate = false;

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Daylight Sensor";
	}

	/**
	 * @return \pocketmine\math\AxisAlignedBB
	 */
	public function getBoundingBox(){
		if($this->boundingBox === null){
			$this->boundingBox = $this->recalculateBoundingBox();
		}
		return $this->boundingBox;
	}

	/**
	 * @return bool
	 */
	public function canBeFlowedInto(){
		return false;
	}

	/**
	 * @return DLDetector
	 */
	protected function getTile(){
		$t = $this->getLevel()->getTile($this);
		if($t instanceof DLDetector){
			return $t;
		}else{
			$nbt = new CompoundTag("", [
				new StringTag("id", Tile::DAY_LIGHT_DETECTOR),
				new IntTag("x", $this->x),
				new IntTag("y", $this->y),
				new IntTag("z", $this->z)
			]);
			return Tile::createTile(Tile::DAY_LIGHT_DETECTOR, $this->getLevel(), $nbt);
		}
	}

	/**
	 * @param Item        $item
	 * @param Player|null $player
	 *
	 * @return bool
	 */
	public function onActivate(Item $item, Player $player = null){
		$this->getLevel()->setBlock($this, new DaylightDetectorInverted(), true, true);
		$this->getTile()->onUpdate();
		return true;
	}

	/**
	 * @param Block|null $from
	 *
	 * @return bool
	 */
	public function isActivated(Block $from = null){
		return $this->getTile()->isActivated();
	}

	/**
	 * @param Item $item
	 *
	 * @return mixed|void
	 */
	public function onBreak(Item $item){
		$this->getLevel()->setBlock($this, new Air());
		if($this->isActivated()) $this->deactivate();
	}

	/**
	 * @return float
	 */
	public function getHardness(){
		return 0.2;
	}

	/**
	 * @return int
	 */
	public function getResistance(){
		return 1;
	}

	/**
	 * @param Item $item
	 *
	 * @return array
	 */
	public function getDrops(Item $item) : array{
		return [
			[self::DAYLIGHT_SENSOR, 0, 1]
		];
	}
}