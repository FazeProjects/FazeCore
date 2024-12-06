<?php



namespace pocketmine\event\player;

use pocketmine\Player;

/**
 * Called when a player leaves the server.
 */
class PlayerQuitEvent extends PlayerEvent {
	public static $handlerList = null;

	/** @var string */
	protected $quitMessage;
	/** @var string */
	protected $quitReason;
	/** @var bool */
	protected $autoSave = true;

	/**
	 * PlayerQuitEvent constructor.
	 *
     * @param bool   $autoSave
	 */
	public function __construct(Player $player, $quitMessage, string $quitReason, $autoSave = true){
		$this->player = $player;
		$this->quitMessage = $quitMessage;
		$this->quitReason = $quitReason;
		$this->autoSave = $autoSave;
	}

	/**
	 * @param $quitMessage
	 */
	public function setQuitMessage($quitMessage){
		$this->quitMessage = $quitMessage;
	}

	/**
	 * @return string
	 */
	public function getQuitMessage(){
		return $this->quitMessage;
	}

	/**
	 * @return string
	 */
	public function getQuitReason() : string{
		return $this->quitReason;
	}

	/**
	 * @return bool
	 */
	public function getAutoSave(){
		return $this->autoSave;
	}

	/**
	 * @param bool $value
	 */
	public function setAutoSave($value = true){
		$this->autoSave = (bool) $value;
	}
}