<?php



namespace pocketmine\event\block;

use pocketmine\block\Block;
use pocketmine\event\Cancellable;
use pocketmine\item\Item;
use pocketmine\Player;

class BlockBreakEvent extends BlockEvent implements Cancellable {
	public static $handlerList = null;

	/** @var \pocketmine\Player */
	protected $player;

	/** @var \pocketmine\item\Item */
	protected $item;

	/** @var bool */
	protected $instaBreak = false;
	protected $blockDrops = [];

	/**
	 * BlockBreakEvent constructor.
	 *
	 * @param Player $player
	 * @param Block  $block
	 * @param Item   $item
	 * @param bool   $instaBreak
	 */
	public function __construct(Player $player, Block $block, Item $item, $instaBreak = false){
		$this->block = $block;
		$this->item = $item;
		$this->player = $player;
		$this->instaBreak = (bool) $instaBreak;
		$drops = $player->isSurvival() ? $block->getDrops($item) : [];
		if($drops != null && is_numeric($drops[0]))
			$this->blockDrops[] = Item::get($drops[0], $drops[1], $drops[2]);
		else
			foreach($drops as $i){
				$this->blockDrops[] = Item::get($i[0], $i[1], $i[2]);
			}
	}

	/**
	 * @return Player
	 */
	public function getPlayer(){
		return $this->player;
	}

	/**
	 * @return Item
	 */
	public function getItem(){
		return $this->item;
	}

	/**
	 * @return bool
	 */
	public function getInstaBreak(){
		return $this->instaBreak;
	}

	/**
	 * @return Item[]
	 */
	public function getDrops(){
		return $this->blockDrops;
	}

	/**
	 * @param Item[] $drops
	 */
	public function setDrops(array $drops){
		$this->blockDrops = $drops;
	}

	/**
	 * @param bool $instaBreak
	 */
	public function setInstaBreak($instaBreak){
		$this->instaBreak = (bool) $instaBreak;
	}
}
