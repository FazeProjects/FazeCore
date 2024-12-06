<?php



namespace pocketmine\metadata;

use pocketmine\plugin\Plugin;

interface Metadatable {

    /**
     * Sets the metadata value in the metadata storage of the implementing object.
     *
     * @param string        $metadataKey
     * @param MetadataValue $newMetadataValue
     */
    public function setMetadata(string $metadataKey, MetadataValue $newMetadataValue);

    /**
     * Returns a list of previously set metadata values from the implementing
     * object's metadata storage.
     *
     * @param string $metadataKey
     *
     * @return MetadataValue[]
     */
    public function getMetadata(string $metadataKey);

    /**
     * Checks if the implementing object contains the given
     * metadata value in its metadata storage.
     *
     * @param string $metadataKey
     *
     * @return bool
     */
    public function hasMetadata(string $metadataKey) : bool;

    /**
     * Removes the specified metadata value from the implementing object
     * metadata storage.
     *
     * @param string $metadataKey
     * @param Plugin $owningPlugin
     */
    public function removeMetadata(string $metadataKey, Plugin $owningPlugin);

}
