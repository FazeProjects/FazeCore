<?php



namespace pocketmine\event\block;

use pocketmine\block\Block;
use pocketmine\event\Cancellable;

class BlockSpreadEvent extends BlockFormEvent implements Cancellable {
	public static $handlerList = null;

	/** @var Block */
	private $source;

	/**
	 * BlockSpreadEvent constructor.
	 *
	 * @param Block $block
	 * @param Block $source
	 * @param Block $newState
	 */
	public function __construct(Block $block, Block $source, Block $newState){
		parent::__construct($block, $newState);
		$this->source = $source;
	}

	/**
	 * @return Block
	 */
	public function getSource(){
		return $this->source;
	}

}