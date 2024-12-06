<?php


 
namespace pocketmine\metadata;

use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginException;

abstract class MetadataStore {
	/** @var \SplObjectStorage[] */
	private $metadataMap;

	/**
	 * Adds a metadata value to an object.
	 *
	 * @param Metadatable   $subject
	 * @param string        $metadataKey
	 * @param MetadataValue $newMetadataValue
	 */
	public function setMetadata(Metadatable $subject, string $metadataKey, MetadataValue $newMetadataValue){
		$owningPlugin = $newMetadataValue->getOwningPlugin();
		if($owningPlugin === null){
			throw new PluginException("Plugin cannot be null");
		}

		$key = $this->disambiguate($subject, $metadataKey);
		if(!isset($this->metadataMap[$key])){
			$entry = new \SplObjectStorage();
			$this->metadataMap[$key] = $entry;
		}else{
			$entry = $this->metadataMap[$key];
		}
		$entry[$owningPlugin] = $newMetadataValue;
	}

	/**
     * Returns all metadata values attached to an object. If multiple
     * have metadata attached, each value will be included.
	 *
	 * @param Metadatable $subject
	 * @param string      $metadataKey
	 *
	 * @return MetadataValue[]
	 */
	public function getMetadata(Metadatable $subject, string $metadataKey){
		$key = $this->disambiguate($subject, $metadataKey);
        return $this->metadataMap[$key] ?? [];
	}

	/**
     * Checks if the metadata attribute is set on the object.
	 *
	 * @param Metadatable $subject
	 * @param string      $metadataKey
	 *
	 * @return bool
	 */
	public function hasMetadata(Metadatable $subject, string $metadataKey) : bool{
		return isset($this->metadataMap[$this->disambiguate($subject, $metadataKey)]);
	}

	/**
     * Removes a plugin-owned metadata element from a theme.
	 *
	 * @param Metadatable $subject
	 * @param string      $metadataKey
	 * @param Plugin      $owningPlugin
	 */
	public function removeMetadata(Metadatable $subject, string $metadataKey, Plugin $owningPlugin){
		$key = $this->disambiguate($subject, $metadataKey);
		if(isset($this->metadataMap[$key])){
			unset($this->metadataMap[$key][$owningPlugin]);
			if($this->metadataMap[$key]->count() === 0){
				unset($this->metadataMap[$key]);
			}
		}
	}

	/**
     * Invalidates all metadata in the metadata store that originates from
     * this plugin. This will make every invalid metadata element
     * be recalculated at next access.
	 *
	 * @param Plugin $owningPlugin
	 */
	public function invalidateAll(Plugin $owningPlugin){
		/** @var MetadataValue[] $values */
		foreach($this->metadataMap as $values){
			if(isset($values[$owningPlugin])){
				$values[$owningPlugin]->invalidate();
			}
		}
	}

	/**
     * Creates a unique name for the object receiving the metadata by combining
     * unique subject data using metadataKey.
	 *
	 * @param Metadatable $subject
	 * @param string      $metadataKey
	 *
	 * @return string
	 *
	 * @throws \InvalidArgumentException
	 */
	public abstract function disambiguate(Metadatable $subject, $metadataKey);
}