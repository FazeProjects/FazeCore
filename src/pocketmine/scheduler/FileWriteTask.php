<?php



namespace pocketmine\scheduler;

use function file_put_contents;

/**
 * @obsolete
 */
class FileWriteTask extends AsyncTask{

	/** @var string */
	private $path;
	/** @var mixed */
	private $contents;
	/** @var int */
	private $flags;

	/**
	 * @param mixed  $contents
	 */
	public function __construct(string $path, $contents, int $flags = 0){
		$this->path = $path;
		$this->contents = $contents;
		$this->flags = $flags;
	}

	public function onRun(){
		file_put_contents($this->path, $this->contents, $this->flags);
	}
}