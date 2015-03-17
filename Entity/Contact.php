<?php 
namespace JT\AdsBundle\Entity;

class Contact {
	
	private $name;
	private $mail;
	private $message;
	
	public function getName() {
		return $this->name;
	}
	public function setName($name) {
		$this->name = $name;
		return $this;
	}
	public function getMail() {
		return $this->mail;
	}
	public function setMail($mail) {
		$this->mail = $mail;
		return $this;
	}
	public function getMessage() {
		return $this->message;
	}
	public function setMessage($message) {
		$this->message = $message;
		return $this;
	}
	
	
	
}