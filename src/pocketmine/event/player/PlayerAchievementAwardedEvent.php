<?php



namespace pocketmine\event\player;

use pocketmine\event\Cancellable;
use pocketmine\Player;

/**
 * Called when the player earns an achievement.
 */
class PlayerAchievementAwardedEvent extends PlayerEvent implements Cancellable {
	public static $handlerList = null;

	/** @var string */
	protected $achievement;

	/**
	 * @param Player $player
	 * @param string $achievementId
	 */
	public function __construct(Player $player, $achievementId){
		$this->player = $player;
		$this->achievement = $achievementId;
	}

	/**
	 * @return string
	 */
	public function getAchievement(){
		return $this->achievement;
	}
}