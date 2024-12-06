<?php



namespace pocketmine\event\level;

use pocketmine\level\Level;
use pocketmine\level\format\Chunk;

/**
 * Called when loading a chunk
 */
class ChunkLoadEvent extends ChunkEvent {
	public static $handlerList = null;

	private $newChunk;

	/**
	 * ChunkLoadEvent constructor.
	 *
	 * @param Level $level
	 * @param Chunk $chunk
	 * @param bool  $newChunk
	 */
	public function __construct(Level $level, Chunk $chunk, bool $newChunk){
		parent::__construct($level, $chunk);
		$this->newChunk = $newChunk;
	}

	/**
	 * @return bool
	 */
	public function isNewChunk(){
		return $this->newChunk;
	}
}