<?php



namespace pocketmine\event\block;

use pocketmine\event\Cancellable;

/**
 * Called when a block tries to update due to a neighbor change
 */
class BlockUpdateEvent extends BlockEvent implements Cancellable {
	public static $handlerList = null;

}