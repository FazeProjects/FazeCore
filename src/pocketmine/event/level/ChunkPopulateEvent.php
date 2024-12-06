<?php



namespace pocketmine\event\level;

/**
 * Called when a chunk is filled (after it is received on the main thread)
 */
class ChunkPopulateEvent extends ChunkEvent {
	public static $handlerList = null;
}