<?php



namespace pocketmine\event\block;

use pocketmine\block\Block;
use pocketmine\event\Cancellable;
use pocketmine\item\Item;
use pocketmine\Player;

/**
 * Called when a player places a block
 */
class BlockPlaceEvent extends BlockEvent implements Cancellable {
	public static $handlerList = null;

	/** @var \pocketmine\Player */
	protected $player;

	/** @var \pocketmine\item\Item */
	protected $item;


	protected $blockReplace;
	protected $blockAgainst;

	/**
	 * BlockPlaceEvent constructor.
	 *
	 * @param Player $player
	 * @param Block  $blockPlace
	 * @param Block  $blockReplace
	 * @param Block  $blockAgainst
	 * @param Item   $item
	 */
	public function __construct(Player $player, Block $blockPlace, Block $blockReplace, Block $blockAgainst, Item $item){
		$this->block = $blockPlace;
		$this->blockReplace = $blockReplace;
		$this->blockAgainst = $blockAgainst;
		$this->item = $item;
		$this->player = $player;
	}

	/**
	 * @return Player
	 */
	public function getPlayer(){
		return $this->player;
	}

	/**
	 * Gets the item in hand
	 *
	 * @return mixed
	 */
	public function getItem(){
		return $this->item;
	}

	/**
	 * @return Block
	 */
	public function getBlockReplaced(){
		return $this->blockReplace;
	}

	/**
	 * @return Block
	 */
	public function getBlockAgainst(){
		return $this->blockAgainst;
	}
}