<?php



namespace pocketmine\event\player;

use pocketmine\command\CommandSender;
use pocketmine\event\Cancellable;
use pocketmine\Player;
use pocketmine\Server;
use function spl_object_id;

/**
 * Called when a player writes something in chat.
 */
class PlayerChatEvent extends PlayerEvent implements Cancellable {
	public static $handlerList = null;

	/** @var string */
	protected $message;

	/** @var string */
	protected $format;

	/**
	 * @var Player[]
	 */
	protected $recipients = [];

	/**
	 * PlayerChatEvent constructor.
	 *
	 * @param Player     $player
	 * @param            $message
	 * @param string     $format
	 * @param array|null $recipients
	 */
	public function __construct(Player $player, $message, $format = "chat.type.text", array $recipients = null){
		$this->player = $player;
		$this->message = $message;

		$this->format = $format;

		if($recipients === null){
			foreach(Server::getInstance()->getPluginManager()->getPermissionSubscriptions(Server::BROADCAST_CHANNEL_USERS) as $permissible){
				if($permissible instanceof CommandSender){
					$this->recipients[spl_object_id($permissible)] = $permissible;
				}
			}
		}else{
			$this->recipients = $recipients;
		}
	}

	/**
	 * @return string
	 */
	public function getMessage(){
		return $this->message;
	}

	/**
	 * @param $message
	 */
	public function setMessage($message){
		$this->message = $message;
	}

	/**
     * Changes the player sending the message
	 *
	 * @param Player $player
	 */
	public function setPlayer(Player $player){
		$this->player = $player;
	}

	/**
	 * @return string
	 */
	public function getFormat(){
		return $this->format;
	}

	/**
	 * @param $format
	 */
	public function setFormat($format){
		$this->format = $format;
	}

	/**
	 * @return array|Player[]
	 */
	public function getRecipients(){
		return $this->recipients;
	}

	/**
	 * @param array $recipients
	 */
	public function setRecipients(array $recipients){
		$this->recipients = $recipients;
	}
}