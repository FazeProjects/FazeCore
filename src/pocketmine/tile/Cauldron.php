<?php

namespace pocketmine\tile;

use pocketmine\level\Level;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\utils\Color;

class Cauldron extends Spawnable {

	/**
	 * Cauldron constructor.
	 *
	 * @param Level       $level
	 * @param CompoundTag $nbt
	 */
	public function __construct(Level $level, CompoundTag $nbt){
		if(!isset($nbt->PotionId) or !($nbt->PotionId instanceof ShortTag)){
			$nbt->PotionId = new ShortTag("PotionId", 0xffff);
		}
		if(!isset($nbt->SplashPotion) or !($nbt->SplashPotion instanceof ByteTag)){
			$nbt->SplashPotion = new ByteTag("SplashPotion", 0);
		}
		if(!isset($nbt->Items) or !($nbt->Items instanceof ListTag)){
			$nbt->Items = new ListTag("Items", []);
		}
		parent::__construct($level, $nbt);
	}

	/**
	 * @return mixed|null
	 */
	public function getPotionId(){
		return $this->namedtag["PotionId"];
	}

	/**
	 * @param $potionId
	 */
	public function setPotionId($potionId){
		$this->namedtag->PotionId = new ShortTag("PotionId", $potionId);
		$this->onChanged();
	}

	/**
	 * @return bool
	 */
	public function hasPotion(){
		return $this->namedtag["PotionId"] !== 0xffff;
	}

	/**
	 * @return bool
	 */
	public function getSplashPotion(){
		return ($this->namedtag["SplashPotion"] == true);
	}

	/**
	 * @param $bool
	 */
	public function setSplashPotion($bool){
		$this->namedtag->SplashPotion = new ByteTag("SplashPotion", ($bool == true) ? 1 : 0);
		$this->onChanged();
	}

	/**
	 * @return null|Color
	 */
	public function getCustomColor(){//
		if($this->isCustomColor()){
			$color = $this->namedtag["CustomColor"];
			$green = ($color >> 8) & 0xff;
			$red = ($color >> 16) & 0xff;
			$blue = ($color) & 0xff;
			return Color::getRGB($red, $green, $blue);
		}
		return null;
	}

	/**
	 * @return int
	 */
	public function getCustomColorRed(){
		return ($this->namedtag["CustomColor"] >> 16) & 0xff;
	}

	/**
	 * @return int
	 */
	public function getCustomColorGreen(){
		return ($this->namedtag["CustomColor"] >> 8) & 0xff;
	}

	/**
	 * @return int
	 */
	public function getCustomColorBlue(){
		return ($this->namedtag["CustomColor"]) & 0xff;
	}

	/**
	 * @return bool
	 */
	public function isCustomColor(){
		return isset($this->namedtag->CustomColor);
	}

	/**
	 * @param     $r
	 * @param int $g
	 * @param int $b
	 */
	public function setCustomColor($r, $g = 0xff, $b = 0xff){
		if($r instanceof Color){
			$color = ($r->getRed() << 16 | $r->getGreen() << 8 | $r->getBlue()) & 0xffffff;
		}else{
			$color = ($r << 16 | $g << 8 | $b) & 0xffffff;
		}
		$this->namedtag->CustomColor = new IntTag("CustomColor", $color);
		$this->onChanged();
	}

	public function clearCustomColor(){
		if(isset($this->namedtag->CustomColor)){
			unset($this->namedtag->CustomColor);
		}
		$this->onChanged();
	}

	/**
	 * @return CompoundTag
	 */
	public function getSpawnCompound(){
		$nbt = new CompoundTag("", [
			new StringTag("id", Tile::CAULDRON),
			new IntTag("x", (Int) $this->x),
			new IntTag("y", (Int) $this->y),
			new IntTag("z", (Int) $this->z),
			new ShortTag("PotionId", $this->namedtag["PotionId"]),
			new ByteTag("SplashPotion", $this->namedtag["SplashPotion"]),
			new ListTag("Items", $this->namedtag["Items"])//unused?
		]);

		if($this->getPotionId() === 0xffff and $this->isCustomColor()){
			$nbt->CustomColor = $this->namedtag->CustomColor;
		}
		return $nbt;
	}
}
