<?php



namespace pocketmine\event\level;

use pocketmine\event\Cancellable;

/**
 * Called when unloading a chunk
 */
class ChunkUnloadEvent extends ChunkEvent implements Cancellable {
	public static $handlerList = null;
}