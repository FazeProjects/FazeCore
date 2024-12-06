<?php


 
namespace pocketmine\metadata;

use pocketmine\level\Level;

class LevelMetadataStore extends MetadataStore {

	/**
	 * @param Metadatable $level
	 * @param string      $metadataKey
	 *
	 * @return string
	 */
	public function disambiguate(Metadatable $level, $metadataKey){
		if(!($level instanceof Level)){
            throw new \InvalidArgumentException("The argument must be an instance of the level");
		}

		return strtolower($level->getName()) . ":" . $metadataKey;
	}
}