<?php



namespace pocketmine\inventory;

use pocketmine\utils\UUID;

class MultiRecipe {

	private $uuid;

	/**
	 * MultiRecipe constructor.
	 *
	 * @param UUID $uuid
	 */
	public function __construct(UUID $uuid){
		$this->uuid = $uuid;
	}

}