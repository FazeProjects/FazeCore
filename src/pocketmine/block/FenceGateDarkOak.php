<?php



namespace pocketmine\block;


class FenceGateDarkOak extends FenceGate {

	protected $id = self::FENCE_GATE_DARK_OAK;

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Dark Oak Fence Gate";
	}
}