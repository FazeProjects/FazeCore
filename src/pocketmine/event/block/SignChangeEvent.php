<?php



namespace pocketmine\event\block;

use pocketmine\block\Block;
use pocketmine\event\Cancellable;
use pocketmine\Player;

/**
 * Called when the player changes the sign.
 */
class SignChangeEvent extends BlockEvent implements Cancellable {
	public static $handlerList = null;

	/** @var \pocketmine\Player */
	private $player;
	/** @var string[] */
	private $lines = [];

	/**
	 * @param Block    $theBlock
	 * @param Player   $thePlayer
	 * @param string[] $theLines
	 */
	public function __construct(Block $theBlock, Player $thePlayer, array $theLines){
		parent::__construct($theBlock);
		$this->player = $thePlayer;
		$this->lines = $theLines;
	}

	/**
	 * @return Player
	 */
	public function getPlayer(){
		return $this->player;
	}

	/**
	 * @return string[]
	 */
	public function getLines(){
		return $this->lines;
	}

	/**
	 * @param int $index 0-3
	 *
	 * @return string
	 */
	public function getLine($index){
		if($index < 0 or $index > 3){
			throw new \InvalidArgumentException("The index must be in the range 0-3!");
		}
		return $this->lines[$index];
	}

	/**
	 * @param string[] $lines
	 */
	public function setLines(array $lines){
		if(count($lines) !== 4){
			throw new \InvalidArgumentException("The array size must be 4!");
		}
		$this->lines = $lines;
	}

	/**
	 * @param int    $index 0-3
	 * @param string $line
	 */
	public function setLine($index, $line){
		if($index < 0 or $index > 3){
			throw new \InvalidArgumentException("The index must be in the range 0-3!");
		}
		$this->lines[$index] = $line;
	}
}