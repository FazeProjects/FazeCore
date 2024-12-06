<?php



namespace pocketmine\level\generator;

use pocketmine\block\Block;
use pocketmine\level\generator\biome\Biome;
use pocketmine\level\Level;
use pocketmine\level\SimpleChunkManager;
use pocketmine\scheduler\AsyncTask;
use pocketmine\utils\Random;
use function serialize;
use function unserialize;

class GeneratorRegisterTask extends AsyncTask{

	/**
	 * @var string
	 * @phpstan-var class-string<Generator>
	 */
	public $generatorClass;
	/** @var string */
	public $settings;
	/** @var int */
	public $seed;
	/** @var int */
	public $levelId;
	/** @var int */
	public $waterHeight;
	/** @var int */
	public $worldHeight = Level::Y_MAX;

	/**
	 * @param mixed[] $generatorSettings
	 * @phpstan-param class-string<Generator> $generatorClass
	 * @phpstan-param array<string, mixed> $generatorSettings
	 */
	public function __construct(Level $level, string $generatorClass, array $generatorSettings = []){
		$this->generatorClass = $generatorClass;
		$this->waterHeight = $level->getWaterHeight();
		$this->settings = serialize($generatorSettings);
		$this->seed = $level->getSeed();
		$this->levelId = $level->getId();
		$this->worldHeight = $level->getWorldHeight();
	}

	public function onRun(){
		Block::init();
		Biome::init();
		$manager = new SimpleChunkManager($this->seed, $this->waterHeight, $this->worldHeight);
		$this->saveToThreadStore("generation.level{$this->levelId}.manager", $manager);

		/**
		 * @var Generator $generator
		 * @see Generator::__construct()
		 */
		$generator = new $this->generatorClass(unserialize($this->settings));
		$generator->init($manager, new Random($manager->getSeed()));
		$this->saveToThreadStore("generation.level{$this->levelId}.generator", $generator);
	}
}