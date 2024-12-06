<?php



namespace pocketmine\event\level;

use pocketmine\event\Cancellable;

/**
 * Called when unloading a level
 */
class LevelUnloadEvent extends LevelEvent implements Cancellable {
	public static $handlerList = null;
}