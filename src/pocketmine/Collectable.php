<?php

namespace pocketmine;

abstract class Collectable extends \Threaded{

	/** @var bool */
	private $isGarbage = false;

	public function isGarbage() : bool{
		return $this->isGarbage;
	}

	/**
	 * @return void
	 */
	public function setGarbage(){
		$this->isGarbage = true;
	}
}