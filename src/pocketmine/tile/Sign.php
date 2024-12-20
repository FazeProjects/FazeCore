<?php



namespace pocketmine\tile;

use pocketmine\event\block\SignChangeEvent;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Sign extends Spawnable {

	/**
	 * Sign constructor.
	 *
	 * @param Level       $level
	 * @param CompoundTag $nbt
	 */
	public function __construct(Level $level, CompoundTag $nbt){
		if(!isset($nbt->Text1)){
			$nbt->Text1 = new StringTag("Text1", "");
		}
		if(!isset($nbt->Text2) or !($nbt->Text2 instanceof StringTag)){
			$nbt->Text2 = new StringTag("Text2", "");
		}
		if(!isset($nbt->Text3) or !($nbt->Text3 instanceof StringTag)){
			$nbt->Text3 = new StringTag("Text3", "");
		}
		if(!isset($nbt->Text4) or !($nbt->Text4 instanceof StringTag)){
			$nbt->Text4 = new StringTag("Text4", "");
		}

		parent::__construct($level, $nbt);
	}

	public function saveNBT(){
		parent::saveNBT();
		unset($this->namedtag->Creator);
	}

	/**
	 * @param string $line1
	 * @param string $line2
	 * @param string $line3
	 * @param string $line4
	 *
	 * @return bool
	 */
	public function setText($line1 = "", $line2 = "", $line3 = "", $line4 = ""){
		$this->namedtag->Text1 = new StringTag("Text1", $line1);
		$this->namedtag->Text2 = new StringTag("Text2", $line2);
		$this->namedtag->Text3 = new StringTag("Text3", $line3);
		$this->namedtag->Text4 = new StringTag("Text4", $line4);
		$this->onChanged();

		return true;
	}

	/**
	 * @param int    $index 0-3
	 * @param string $line
	 * @param bool   $update
	 */
	public function setLine(int $index, string $line, bool $update = true){
		if($index < 0 or $index > 3){
			throw new \InvalidArgumentException("Index must be in the range 0-3!");
		}
		$this->namedtag["Text" . ($index + 1)] = $line;
		if($update){
			$this->onChanged();
		}
	}

	/**
	 * @param int $index 0-3
	 *
	 * @return string
	 */
	public function getLine(int $index) : string{
		if($index < 0 or $index > 3){
			throw new \InvalidArgumentException("Index must be in the range 0-3!");
		}
		return (string) $this->namedtag["Text" . ($index + 1)];
	}

	/**
	 * @return array
	 */
	public function getText(){
		return [
			$this->namedtag["Text1"],
			$this->namedtag["Text2"],
			$this->namedtag["Text3"],
			$this->namedtag["Text4"]
		];
	}

	/**
	 * @return CompoundTag
	 */
	public function getSpawnCompound(){
		return new CompoundTag("", [
			new StringTag("id", Tile::SIGN),
			$this->namedtag->Text1,
			$this->namedtag->Text2,
			$this->namedtag->Text3,
			$this->namedtag->Text4,
			new IntTag("x", (int) $this->x),
			new IntTag("y", (int) $this->y),
			new IntTag("z", (int) $this->z)
		]);
	}

	/**
	 * @param CompoundTag $nbt
	 * @param Player      $player
	 *
	 * @return bool
	 */
	public function updateCompoundTag(CompoundTag $nbt, Player $player) : bool{
		if($nbt["id"] !== Tile::SIGN){
			return false;
		}

		$ev = new SignChangeEvent($this->getBlock(), $player, [
			TextFormat::clean($nbt["Text1"], ($removeFormat = $player->getRemoveFormat())),
			TextFormat::clean($nbt["Text2"], $removeFormat),
			TextFormat::clean($nbt["Text3"], $removeFormat),
			TextFormat::clean($nbt["Text4"], $removeFormat)
		]);

		if(!isset($this->namedtag->Creator) or $this->namedtag["Creator"] !== $player->getRawUniqueId()){
			$ev->setCancelled();
		}

		$this->level->getServer()->getPluginManager()->callEvent($ev);

		if(!$ev->isCancelled()){
			$this->setText(...$ev->getLines());
			return true;
		}else{
			return false;
		}
	}

}
