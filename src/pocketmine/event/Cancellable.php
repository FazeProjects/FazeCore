<?php



namespace pocketmine\event;


/**
 * Events that can be cancelled should implement the Cancelable interface.
 */
interface Cancellable {
	public function isCancelled();

	/**
	 * @param bool $forceCancel
	 *
	 * @return mixed
	 */
	public function setCancelled($forceCancel = false);
}