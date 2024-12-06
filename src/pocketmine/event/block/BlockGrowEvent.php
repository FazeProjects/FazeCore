<?php



namespace pocketmine\event\block;

use pocketmine\block\Block;
use pocketmine\event\Cancellable;

class BlockGrowEvent extends BlockEvent implements Cancellable {
	public static $handlerList = null;

	/** @var Block */
	private $newState;

	/**
	 * BlockGrowEvent constructor.
	 *
	 * @param Block $block
	 * @param Block $newState
	 */
	public function __construct(Block $block, Block $newState){
		parent::__construct($block);
		$this->newState = $newState;
	}

	/**
	 * @return Block
	 */
	public function getNewState(){
		return $this->newState;
	}

}