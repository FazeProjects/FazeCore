<?php



namespace pocketmine\item;


class Coal extends Item {
	const NORMAL = 0;
	const CHARCOAL = 1;

	/**
	 * Coal constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::COAL, $meta, $count, "Coal");
		if($this->meta === 1){
			$this->name = "Charcoal";
		}
	}

}