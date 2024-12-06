<?php



namespace pocketmine\block;


class FenceGateAcacia extends FenceGate {

	protected $id = self::FENCE_GATE_ACACIA;

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Acacia Fence Gate";
	}
}