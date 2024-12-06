<?php



namespace pocketmine\metadata;

use pocketmine\IPlayer;

class PlayerMetadataStore extends MetadataStore {

	/**
	 * @param Metadatable $player
	 * @param string      $metadataKey
	 *
	 * @return string
	 */
	public function disambiguate(Metadatable $player, $metadataKey){
		if(!($player instanceof IPlayer)){
            throw new \InvalidArgumentException("The argument must be an IPlayer instance.");
		}

		return strtolower($player->getName()) . ":" . $metadataKey;
	}
}
