<?php



namespace pocketmine\block;


class FenceGateBirch extends FenceGate {

	protected $id = self::FENCE_GATE_BIRCH;

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Birch Fence Gate";
	}
}