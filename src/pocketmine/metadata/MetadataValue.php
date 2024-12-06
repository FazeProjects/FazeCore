<?php



namespace pocketmine\metadata;

use pocketmine\plugin\Plugin;

abstract class MetadataValue {
	/** @var \WeakRef<Plugin> */
	protected $owningPlugin;

	/**
     * MetadataValue constructor.
	 *
	 * @param Plugin $owningPlugin
	 */
	protected function __construct(Plugin $owningPlugin){
		$this->owningPlugin = new \WeakRef($owningPlugin);
	}

	/**
	 * @return Plugin
	 */
	public function getOwningPlugin(){
		return $this->owningPlugin->get();
	}

	/**
     * Retrieves the value of this metadata element.
	 *
	 * @return mixed
	 */
	public abstract function value();

    /**
     * Invalidates this metadata element, causing it to be recalculated on the next
     * access.
     */
	public abstract function invalidate();
}