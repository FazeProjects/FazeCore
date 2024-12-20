<?php



namespace pocketmine\level\generator;

use pocketmine\level\format\Chunk;
use pocketmine\level\Level;
use pocketmine\level\SimpleChunkManager;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;


class PopulationTask extends AsyncTask{

	/** @var bool */
	public $state;
	/** @var int */
	public $levelId;
	/** @var string */
	public $chunk;

	/** @var string */
	public $chunk0;
	/** @var string */
	public $chunk1;
	/** @var string */
	public $chunk2;
	/** @var string */
	public $chunk3;

	//center chunk

	/** @var string */
	public $chunk5;
	/** @var string */
	public $chunk6;
	/** @var string */
	public $chunk7;
	/** @var string */
	public $chunk8;

	public function __construct(Level $level, Chunk $chunk){
		$this->state = true;
		$this->levelId = $level->getId();
		$this->chunk = $chunk->fastSerialize();

		foreach($level->getAdjacentChunks($chunk->getX(), $chunk->getZ()) as $i => $c){
			$this->{"chunk$i"} = $c !== null ? $c->fastSerialize() : null;
		}
	}

	public function onRun(){
		$manager = $this->getFromThreadStore("generation.level{$this->levelId}.manager");
		$generator = $this->getFromThreadStore("generation.level{$this->levelId}.generator");
		if(!($manager instanceof SimpleChunkManager) or !($generator instanceof Generator)){
			$this->state = false;
			return;
		}

		/** @var Chunk[] $chunks */
		$chunks = [];

		$chunk = Chunk::fastDeserialize($this->chunk);

		for($i = 0; $i < 9; ++$i){
			if($i === 4){
				continue;
			}
			$xx = -1 + $i % 3;
			$zz = -1 + (int) ($i / 3);
			$ck = $this->{"chunk$i"};
			if($ck === null){
				$chunks[$i] = new Chunk($chunk->getX() + $xx, $chunk->getZ() + $zz);
			}else{
				$chunks[$i] = Chunk::fastDeserialize($ck);
			}
		}

		$manager->setChunk($chunk->getX(), $chunk->getZ(), $chunk);
		if(!$chunk->isGenerated()){
			$generator->generateChunk($chunk->getX(), $chunk->getZ());
			$chunk = $manager->getChunk($chunk->getX(), $chunk->getZ());
			$chunk->setGenerated();
		}

		foreach($chunks as $i => $c){
			$manager->setChunk($c->getX(), $c->getZ(), $c);
			if(!$c->isGenerated()){
				$generator->generateChunk($c->getX(), $c->getZ());
				$chunks[$i] = $manager->getChunk($c->getX(), $c->getZ());
				$chunks[$i]->setGenerated();
			}
		}

		$generator->populateChunk($chunk->getX(), $chunk->getZ());
		$chunk = $manager->getChunk($chunk->getX(), $chunk->getZ());
		$chunk->setPopulated();

		$chunk->recalculateHeightMap();
		$chunk->populateSkyLight();
		$chunk->setLightPopulated();

		$this->chunk = $chunk->fastSerialize();

		foreach($chunks as $i => $c){
			$this->{"chunk$i"} = $c->hasChanged() ? $c->fastSerialize() : null;
		}

		$manager->cleanChunks();
	}

	public function onCompletion(Server $server){
		$level = $server->getLevel($this->levelId);
		if($level !== null){
			if(!$this->state){
				$level->registerGenerator();
			}

			$chunk = Chunk::fastDeserialize($this->chunk);

			for($i = 0; $i < 9; ++$i){
				if($i === 4){
					continue;
				}
				$c = $this->{"chunk$i"};
				if($c !== null){
					$c = Chunk::fastDeserialize($c);
					$level->generateChunkCallback($c->getX(), $c->getZ(), $this->state ? $c : null);
				}
			}

			$level->generateChunkCallback($chunk->getX(), $chunk->getZ(), $this->state ? $chunk : null);
		}
	}
}