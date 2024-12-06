<?php


 
namespace pocketmine\plugin;

use LogLevel;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class PluginLogger implements \AttachableLogger {

	private $pluginName;

	/** @var \LoggerAttachment[] */
	private $attachments = [];

	/**
	 * @param \LoggerAttachment $attachment
	 */
	public function addAttachment(\LoggerAttachment $attachment){
		$this->attachments[spl_object_hash($attachment)] = $attachment;
	}

	/**
	 * @param \LoggerAttachment $attachment
	 */
	public function removeAttachment(\LoggerAttachment $attachment){
		unset($this->attachments[spl_object_hash($attachment)]);
	}

	public function removeAttachments(){
		$this->attachments = [];
	}

	/**
	 * @return \LoggerAttachment[]
	 */
	public function getAttachments(){
		return $this->attachments;
	}

	/**
	 * @param Plugin $context
	 */
	public function __construct(Plugin $context){
		$this->prefix = $context->getDescription()->getPrefix();
		$this->defaultName = $context->getDescription()->getName();
		$this->pluginName = $this->prefix != null ? "[$this->prefix] " : "[" . $context->getDescription()->getName() . "] ";
	}

	public function setName($name) {
		$this->pluginName = "[" . $name . "] ";
		$this->pluginName = $name != null ? "[" . $name . TextFormat::RESET ."] " : "[" . $this->getDefaultName() . TextFormat::RESET ."] ";
	}

	public function getPrefix(): ?string{
		return $this->prefix;
	}
	
	public function getDefaultName(): string{
		return $this->defaultName;
	}

	/**
	 * @param string $message
	 */
	public function emergency($message){
		$this->log(LogLevel::EMERGENCY, $message);
	}

	/**
	 * @param string $message
	 */
	public function alert($message){
		$this->log(LogLevel::ALERT, $message);
	}

	/**
	 * @param string $message
	 */
	public function critical($message){
		$this->log(LogLevel::CRITICAL, $message);
	}

	/**
	 * @param string $message
	 */
	public function error($message){
		$this->log(LogLevel::ERROR, $message);
	}

	/**
	 * @param string $message
	 */
	public function warning($message){
		$this->log(LogLevel::WARNING, $message);
	}

	/**
	 * @param string $message
	 */
	public function notice($message){
		$this->log(LogLevel::NOTICE, $message);
	}

	/**
	 * @param string $message
	 */
	public function info($message){
		$this->log(LogLevel::INFO, $message);
	}

	/**
	 * @param string $message
	 */
	public function debug($message){
		$this->log(LogLevel::DEBUG, $message);
	}

	/**
	 * @param \Throwable $e
	 * @param null       $trace
	 */
	public function logException(\Throwable $e, $trace = null){
		Server::getInstance()->getLogger()->logException($e, $trace);
	}

	/**
	 * @param mixed  $level
	 * @param string $message
	 */
	public function log($level, $message){
		Server::getInstance()->getLogger()->log($level, $this->pluginName . $message);
		foreach($this->attachments as $attachment){
			$attachment->log($level, $message);
		}
	}
}
