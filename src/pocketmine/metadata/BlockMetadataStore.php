<?php



namespace pocketmine\metadata;

use pocketmine\block\Block;
use pocketmine\level\Level;
use pocketmine\plugin\Plugin;

class BlockMetadataStore extends MetadataStore {
    /** @var Level */
    private $owningLevel;

    /**
     * BlockMetadataStore constructor.
     *
     * @param Level $owningLevel
     */
    public function __construct(Level $owningLevel){
        $this->owningLevel = $owningLevel;
    }

    /**
     * @param Metadatable $block
     * @param string      $metadataKey
     *
     * @return string
     */
    public function disambiguate(Metadatable $block, $metadataKey){
        if(!($block instanceof Block)){
            throw new \InvalidArgumentException("Argument must be an instance of Block");
        }

        return $block->x . ":" . $block->y . ":" . $block->z . ":" . $metadataKey;
    }

    public function getMetadata(Metadatable $subject, string $metadataKey){
        if(!($subject instanceof Block)){
            throw new \InvalidArgumentException("Object must be a block");
        }
        if($subject->getLevel() === $this->owningLevel){
            return parent::getMetadata($subject, $metadataKey);
        }else{
            throw new \InvalidStateException("Block does not belong to the world " . $this->owningLevel->getName());
        }
    }

    public function hasMetadata(Metadatable $subject, string $metadataKey) : bool{
        if(!($subject instanceof Block)){
            throw new \InvalidArgumentException("Object must be a block");
        }
        if($subject->getLevel() === $this->owningLevel){
            return parent::hasMetadata($subject, $metadataKey);
        }else{
            throw new \InvalidStateException("Block does not belong to the world " . $this->owningLevel->getName());
        }
    }

    public function removeMetadata(Metadatable $subject, string $metadataKey, Plugin $owningPlugin){
        if(!($subject instanceof Block)){
            throw new \InvalidArgumentException("Object must be a block");
        }
        if($subject->getLevel() === $this->owningLevel){
            parent::removeMetadata($subject, $metadataKey, $owningPlugin);
        }else{
            throw new \InvalidStateException("Block does not belong to the world " . $this->owningLevel->getName());
        }
    }

    public function setMetadata(Metadatable $subject, string $metadataKey, MetadataValue $newMetadataValue){
        if(!($subject instanceof Block)){
            throw new \InvalidArgumentException("Object must be a block");
        }
        if($subject->getLevel() === $this->owningLevel){
            parent::setMetadata($subject, $metadataKey, $newMetadataValue);
        }else{
            throw new \InvalidStateException("Block does not belong to the world " . $this->owningLevel->getName());
        }
    }
}
