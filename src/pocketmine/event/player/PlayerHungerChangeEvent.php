<?php



namespace pocketmine\event\player;

use pocketmine\event\Cancellable;
use pocketmine\Player;

class PlayerHungerChangeEvent extends PlayerEvent implements Cancellable {
	public static $handlerList = null;

	public $data;

	/**
	 * PlayerHungerChangeEvent constructor.
	 *
	 * @param Player $player
	 * @param        $data
	 */
	public function __construct(Player $player, $data){
		$this->data = $data;
		$this->player = $player;
	}

	/**
	 * @return mixed
	 */
	public function getData(){
		return $this->data;
	}

	/**
	 * @param $data
	 */
	public function setData($data){
		$this->data = $data;
	}

}
