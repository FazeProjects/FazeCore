<?php



/**
 * Классы, связанные с сетью
 */

namespace pocketmine\network;

interface AdvancedSourceInterface extends SourceInterface {

	public function blockAddress($address, $timeout = 300);

	public function setNetwork(Network $network);

	public function sendRawPacket($address, $port, $payload);

}