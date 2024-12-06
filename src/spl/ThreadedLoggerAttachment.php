<?php

abstract class ThreadedLoggerAttachment extends \Volatile implements \LoggerAttachment{

	/** @var \ThreadedLoggerAttachment */
	protected $attachment = null;

	/**
	 * @param mixed  $level
	 * @param string $message
	 */
	public final function call($level, $message){
		$this->log($level, $message);
		if($this->attachment instanceof \ThreadedLoggerAttachment){
			$this->attachment->call($level, $message);
		}
	}

	/**
	 * @deprecated
	 * @param ThreadedLoggerAttachment $attachment
	 */
	public function addAttachment(\ThreadedLoggerAttachment $attachment){
		if($this->attachment instanceof \ThreadedLoggerAttachment){
			$this->attachment->addAttachment($attachment);
		}else{
			$this->attachment = $attachment;
		}
	}

	/**
	 * @deprecated
	 * @param ThreadedLoggerAttachment $attachment
	 */
	public function removeAttachment(\ThreadedLoggerAttachment $attachment){
		if($this->attachment instanceof \ThreadedLoggerAttachment){
			if($this->attachment === $attachment){
				$this->attachment = null;
				foreach($attachment->getAttachments() as $attachment){
					$this->addAttachment($attachment);
				}
			}
		}
	}

	/**
	 * @deprecated
	 */
	public function removeAttachments(){
		if($this->attachment instanceof \ThreadedLoggerAttachment){
			$this->attachment->removeAttachments();
			$this->attachment = null;
		}
	}

	/**
	 * @deprecated
	 * @return \ThreadedLoggerAttachment[]
	 */
	public function getAttachments(){
		$attachments = [];
		if($this->attachment instanceof \ThreadedLoggerAttachment){
			$attachments[] = $this->attachment;
			$attachments += $this->attachment->getAttachments();
		}

		return $attachments;
	}
}