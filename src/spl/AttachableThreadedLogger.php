<?php

abstract class AttachableThreadedLogger extends \ThreadedLogger{

	/** @var \ThreadedLoggerAttachment */
	protected $attachments = null;

	public function __construct(){
		$this->attachments = new \Volatile();
	}

	/**
	 * @param ThreadedLoggerAttachment $attachment
	 */
	public function addAttachment(\ThreadedLoggerAttachment $attachment){
		$this->attachments[] = $attachment;
	}

	/**
	 * @param ThreadedLoggerAttachment $attachment
	 */
	public function removeAttachment(\ThreadedLoggerAttachment $attachment){
		foreach($this->attachments as $i => $a){
			if($attachment === $a){
				unset($this->attachments[$i]);
			}
		}
	}

	public function removeAttachments(){
		foreach($this->attachments as $i => $a){
			unset($this->attachments[$i]);
		}
	}

	/**
	 * @return \ThreadedLoggerAttachment[]
	 */
	public function getAttachments(){
		return (array) $this->attachments;
	}
}