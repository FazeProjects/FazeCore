<?php



namespace pocketmine\timings;

use pocketmine\timings\Timings;
use pocketmine\level\Level;

class LevelTimings {

	/** @var TimingsHandler */
	public $setBlock;
	/** @var TimingsHandler */
	public $doBlockLightUpdates;
	/** @var TimingsHandler */
	public $doBlockSkyLightUpdates;
	
	/** @var TimingsHandler */
	public $doChunkUnload;
	/** @var TimingsHandler */
	public $doTickPending;
	/** @var TimingsHandler */
	public $doTickTiles;
	/** @var TimingsHandler */
	public $doChunkGC;
	/** @var TimingsHandler */
	public $entityTick;
	/** @var TimingsHandler */
	public $tileEntityTick;
	/** @var TimingsHandler */
	public $doTick;

	/** @var TimingsHandler */
	public $syncChunkSendTimer;
	/** @var TimingsHandler */
	public $syncChunkSendPrepareTimer;

	/** @var TimingsHandler */
	public $syncChunkLoadTimer;
	/** @var TimingsHandler */
	public $syncChunkLoadDataTimer;
	/** @var TimingsHandler */
	public $syncChunkLoadEntitiesTimer;
	/** @var TimingsHandler */
	public $syncChunkLoadTileEntitiesTimer;
	/** @var TimingsHandler */
	public $syncChunkSaveTimer;

	/**
	 * LevelTimings constructor.
	 *
	 * @param Level $level
	 */
	public function __construct(Level $level){
		$name = $level->getFolderName() . " - ";

		$this->setBlock = new TimingsHandler("** " . $name . "setBlock");
		$this->doBlockLightUpdates = new TimingsHandler("** " . $name . "doBlockLightUpdates");
		$this->doBlockSkyLightUpdates = new TimingsHandler("** " . $name . "doBlockSkyLightUpdates");

		$this->doChunkUnload = new TimingsHandler("** " . $name . "doChunkUnload");
		$this->doTickPending = new TimingsHandler("** " . $name . "doTickPending");
		$this->doTickTiles = new TimingsHandler("** " . $name . "doTickTiles");
		$this->doChunkGC = new TimingsHandler("** " . $name . "doChunkGC");
		$this->entityTick = new TimingsHandler("** " . $name . "entityTick");
		$this->tileEntityTick = new TimingsHandler("** " . $name . "tileEntityTick");

		Timings::init(); //make sure the timers we want are available
		$this->syncChunkSendTimer = new TimingsHandler("** " . $name . "syncChunkSend", Timings::$playerChunkSendTimer);
		$this->syncChunkSendPrepareTimer = new TimingsHandler("** " . $name . "syncChunkSendPrepare", Timings::$playerChunkSendTimer);

		$this->syncChunkLoadTimer = new TimingsHandler("** " . $name . "syncChunkLoad", Timings::$worldLoadTimer);
		$this->syncChunkLoadDataTimer = new TimingsHandler("** " . $name . "syncChunkLoad - Data");
		$this->syncChunkLoadEntitiesTimer = new TimingsHandler("** " . $name . "syncChunkLoad - Entities");
		$this->syncChunkLoadTileEntitiesTimer = new TimingsHandler("** " . $name . "syncChunkLoad - TileEntities");
		$this->syncChunkSaveTimer = new TimingsHandler("** " . $name . "syncChunkSave", Timings::$worldSaveTimer);

		$this->doTick = new TimingsHandler($name . "doTick");
	}

}