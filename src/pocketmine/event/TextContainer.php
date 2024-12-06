<?php



namespace pocketmine\event;

class TextContainer {

	/** @var string $text */
	protected $text;

	/**
	 * TextContainer constructor.
	 *
	 * @param $text
	 */
	public function __construct($text){
		$this->text = $text;
	}

	/**
	 * @param $text
	 */
	public function setText($text){
		$this->text = $text;
	}

	/**
	 * @return string
	 */
	public function getText(){
		return $this->text;
	}

	/**
	 * @return string
	 */
	public function __toString(){
		return $this->getText();
	}
}