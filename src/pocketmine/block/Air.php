<?php



namespace pocketmine\block;

use pocketmine\item\Item;


/**
 * Air block
 */
class Air extends Transparent {

	protected $id = self::AIR;

	/**
	 * Air constructor.
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Air";
	}

	/**
	 * @return bool
	 */
	public function canPassThrough(){
		return true;
	}

	/**
	 * @param Item $item
	 *
	 * @return bool
	 */
	public function isBreakable(Item $item){
		return false;
	}

	/**
	 * @return bool
	 */
	public function canBeFlowedInto(){
		return true;
	}

	/**
	 * @return bool
	 */
	public function canBeReplaced(){
		return true;
	}

	/**
	 * @return bool
	 */
	public function canBePlaced(){
		return false;
	}

	/**
	 * @return bool
	 */
	public function isSolid(){
		return false;
	}

	/**
	 * @return null
	 */
	public function getBoundingBox(){
		return null;
	}

	public function getCollisionBoxes() : array{
		return [];
	}

	/**
	 * @return int
	 */
	public function getHardness(){
		return 0;
	}

	/**
	 * @return int
	 */
	public function getResistance(){
		return 0;
	}

}