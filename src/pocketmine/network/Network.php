<?php

declare(strict_types=1);

namespace pocketmine\network;

use SplFixedArray;
use InvalidStateException;
use UnexpectedValueException;
use pocketmine\{Player, Server};
use pocketmine\utils\BinaryStream;
use pocketmine\network\mcpe\protocol\BatchPacket;
use pocketmine\network\mcpe\protocol\DataPacket;

class Network
{
	public static $BATCH_THRESHOLD = 512;
	
	/** @var PacketPool */
	private $packetPool;

	/** @var Server */
	private $server;

	/** @var SourceInterface[] */
	private $interfaces = [];

	/** @var AdvancedSourceInterface[] */
	private $advancedInterfaces = [];
    
    private $antiflood = 0;
	private $upload = 0;
	private $download = 0;

	private $name;

	public function __construct(Server $server){
		$this->packetPool = new PacketPool();
		$this->server = $server;
	}

	public function addStatistics($upload, $download){
		$this->upload += $upload;
		$this->download += $download;
	}

	public function getUpload(){
		return $this->upload;
	}

	public function getDownload(){
		return $this->download;
	}

	public function resetStatistics(){
		$this->upload = 0;
		$this->download = 0;
	}

	public function getInterfaces(){
		return $this->interfaces;
	}

	public function processInterfaces(){
		foreach($this->interfaces as $interface) $interface->process();
	}

	public function processInterface(SourceInterface $interface) : void{
		$interface->process();
	}

	public function registerInterface(SourceInterface $interface){
		$interface->start();
		$this->interfaces[$hash = spl_object_hash($interface)] = $interface;
		if($interface instanceof AdvancedSourceInterface){
		$this->advancedInterfaces[$hash] = $interface;
		$interface->setNetwork($this);
		}
		$interface->setName($this->name);
	}

	public function unregisterInterface(SourceInterface $interface){
		unset($this->interfaces[$hash = spl_object_hash($interface)],
		$this->advancedInterfaces[$hash]);
	}

	public function setName($name){
		$this->name = (string) $name;
		foreach($this->interfaces as $interface) $interface->setName($this->name);
	}

	public function getName(){
		return $this->name;
	}

	public function updateName(){
		foreach($this->interfaces as $interface) $interface->setName($this->name);
	}
	
	public function processBatch(BatchPacket $packet, Player $player){
		$rawLen = strlen($packet->payload);
		if($rawLen === 0) throw new \InvalidArgumentException('BatchPacket payload is empty or packet decode error');
		elseif($rawLen < 3) throw new \InvalidArgumentException('Not enough bytes, expected zlib header');
		$str = zlib_decode($packet->payload, 1024 * 1024 * 2);
		$len = strlen($str);
		
		if($len === 0) throw new \InvalidStateException('Decoded BatchPacket payload is empty');
		$stream = new BinaryStream($str);
		
		while(!$stream->feof()){
		    $buf = $stream->getString();
            if(($packet = $this->getPacket(ord($buf[0])))){
                if(!$packet->canBeBatched()) throw new \UnexpectedValueException('Received invalid '.get_class($pk).' inside BatchPacket');
                if($packet::NETWORK_ID > 100) ++$this->antiflood;
                if($this->antiflood > 3){
                    $player->close('', 'A large number of packets per second!');
                    $this->server->getNetwork()->blockAddress($player->getAddress());
                    $this->server->getLogger()->warning('The player '.$player->getAddress().' sent a large number of Packets: '.$packet::NETWORK_ID.' per second');
                    return true;
                }
                $packet->setBuffer($buf, 1);
                $packet->decode();
                if(!$packet->feof() and !$packet->mayHaveUnreadBytes()){
                    $remains = substr($packet->buffer, $packet->offset);
                    $this->server->getLogger()->debug('Still ' . strlen($remains) . ' bytes unread in ' . $packet->getName() . ': 0x' . bin2hex($remains));
                }
                $player->handleDataPacket($packet);
            }
		}
	}

	/**
	 * @param $id
	 *
	 * @return DataPacket
	 */
	public function getPacket($id)
	{
		return $this->packetPool->get($id);
	}

	public function sendPacket($address, $port, $payload){
		foreach($this->advancedInterfaces as $interface) $interface->sendRawPacket($address, $port, $payload);
	}

	public function blockAddress($address, $timeout = 300){
		foreach($this->advancedInterfaces as $interface) $interface->blockAddress($address, $timeout);
	}

	public function unblockAddress($address){
		foreach($this->advancedInterfaces as $interface) $interface->unblockAddress($address);
	}

	/**
	 * @param int $id 0-255
	 * @param string $class
	 */
	public function registerPacket(int $id, string $class){
		$this->packetPool->register($id, $class);
	}
}