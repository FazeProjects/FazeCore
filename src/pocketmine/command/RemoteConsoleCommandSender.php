<?php



namespace pocketmine\command;


use pocketmine\event\TextContainer;

class RemoteConsoleCommandSender extends ConsoleCommandSender {

	/** @var string */
	private $messages = "";

	/**
	 * @param string $message
	 */
	public function sendMessage($message){
		if($message instanceof TextContainer){
			$message = $this->getServer()->getLanguage()->translate($message);
		}else{
			$message = $this->getServer()->getLanguage()->translateString($message);
		}

		$this->messages .= trim($message, "\r\n") . "\n";
	}

	/**
	 * @return string
	 */
	public function getMessage(){
		return $this->messages;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Rcon";
	}


}