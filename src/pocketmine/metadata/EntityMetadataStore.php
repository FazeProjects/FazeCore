<?php



namespace pocketmine\metadata;

use pocketmine\entity\Entity;

class EntityMetadataStore extends MetadataStore {

	/**
	 * @param Metadatable $entity
	 * @param string      $metadataKey
	 *
	 * @return string
	 */
	public function disambiguate(Metadatable $entity, $metadataKey){
		if(!($entity instanceof Entity)){
            throw new \InvalidArgumentException("The argument must be an Entity instance.");
		}

		return $entity->getId() . ":" . $metadataKey;
	}
}