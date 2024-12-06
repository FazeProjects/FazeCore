<?php



namespace pocketmine\event\block;

use pocketmine\block\Block;
use pocketmine\event\Cancellable;

class LeavesDecayEvent extends BlockEvent implements Cancellable {
	public static $handlerList = null;

	/**
	 * LeavesDecayEvent constructor.
	 *
	 * @param Block $block
	 */
	public function __construct(Block $block){
		parent::__construct($block);
	}

}