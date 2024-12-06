<?php



namespace pocketmine\network;

use pocketmine\network\mcpe\protocol\BatchPacket;
use pocketmine\Player;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

class CompressBatchedTask extends AsyncTask{

	/** @var int */
	public $level = 7;
	/** @var string */
	public $data;

	/**
	 * @param BatchPacket $batch
	 * @param Player[]    $targets
	 */
	public function __construct(BatchPacket $batch, array $targets){
		$this->data = $batch->payload;
		$this->level = $batch->getCompressionLevel();
		$this->storeLocal($targets);
	}

	public function onRun(){
		$batch = new BatchPacket();
		$batch->payload = $this->data;

		$batch->setCompressionLevel($this->level);
		$batch->encode();

		$this->setResult($batch->buffer);
	}

	public function onCompletion(Server $server){
		$pk = new BatchPacket($this->getResult());
		$pk->isEncoded = true;

		/** @var Player[] $targets */
		$targets = $this->fetchLocal();

		$server->broadcastPacketsCallback($pk, $targets);
	}
}