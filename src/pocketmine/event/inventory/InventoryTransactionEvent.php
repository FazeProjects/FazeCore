<?php



namespace pocketmine\event\inventory;

use pocketmine\event\Cancellable;
use pocketmine\event\Event;
use pocketmine\inventory\TransactionQueue;

/**
 * Called when the inventory transaction queue begins executing.
 */
class InventoryTransactionEvent extends Event implements Cancellable {

	public static $handlerList = null;

	/** @var TransactionQueue */
	private $transactionQueue;

	/**
	 * @param TransactionQueue $transactionQueue
	 */
	public function __construct(TransactionQueue $transactionQueue){
		$this->transactionQueue = $transactionQueue;
	}

	/**
	 * @deprecated
	 * @return TransactionQueue
	 */
	public function getTransaction(){
		return $this->transactionQueue;
	}

	/**
	 * @return TransactionQueue
	 */
	public function getQueue(){
		return $this->transactionQueue;
	}
}