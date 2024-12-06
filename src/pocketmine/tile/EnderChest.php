<?php



namespace pocketmine\tile;

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;

class EnderChest extends Spawnable implements Nameable {

	/**
	 * @return string
	 */
	public function getName() : string{
		return isset($this->namedtag->CustomName) ? $this->namedtag->CustomName->getValue() : "Ender Chest";
	}

	/**
	 * @return bool
	 */
	public function hasName(){
		return isset($this->namedtag->CustomName);
	}

	/**
	 * @param void $str
	 */
	public function setName($str){
		if($str === ""){
			unset($this->namedtag->CustomName);
			return;
		}

		$this->namedtag->CustomName = new StringTag("CustomName", $str);
	}

	/**
	 * @return CompoundTag
	 */
	public function getSpawnCompound(){
		$c = new CompoundTag("", [
			new StringTag("id", Tile::ENDER_CHEST),
			new IntTag("x", (int) $this->x),
			new IntTag("y", (int) $this->y),
			new IntTag("z", (int) $this->z)
		]);

		if($this->hasName()){
			$c->CustomName = $this->namedtag->CustomName;
		}
		return $c;
	}

}