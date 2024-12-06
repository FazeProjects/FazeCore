<?php



namespace pocketmine\event\player;

use pocketmine\event\Cancellable;
use pocketmine\Player;

/**
 * Called when a player has its gamemode changed
 */
class PlayerGameModeChangeEvent extends PlayerEvent implements Cancellable {
	public static $handlerList = null;

	/** @var int */
	protected $gamemode;

	/**
	 * PlayerGameModeChangeEvent constructor.
	 *
	 * @param Player $player
	 * @param        $newGamemode
	 */
	public function __construct(Player $player, $newGamemode){
		$this->player = $player;
		$this->gamemode = (int) $newGamemode;
	}

	/**
	 * @return int
	 */
	public function getNewGamemode(){
		return $this->gamemode;
	}

}