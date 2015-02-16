<?php

class HEmail {
	public $To; // acceptor of the email
	public $From; // your own/website's main mail = mail sender
	public $Subject; // subject of the email
	public $Message; // the message
	public $Headers; // type of header - see below

	/* Header types */

	const HEADERS_HTML = "html"; // for HTML message
	const HEADERS_TEXT = "text"; // for plain text without formating

	public function __construct() {
		$this->Headers = self::HEADERS_HTML; // default = HTML format
		$this->Subject = null; // null
		$this->Message = null; 
		$this->From = "bundle@example.com";
	}

	// according to selected header type 
	// return header string form mail() function
	private function getHeaders() {
		$headers = "";

		if ($this->Headers == self::HEADERS_HTML) { // HTML

			$headers .= "From: " . strip_tags($this->From) . "\r\n";
			$headers .= "Reply-To: ". strip_tags($this->To) . "\r\n";
			$headers .= "CC: " . $this->From . "\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

		} else if ($this->Headers == self::HEADERS_TEXT) { // TEXT

			$headers .= "From: " . strip_tags($this->From) . "\r\n";

		}

		return $headers;
	}

	// according to selected header type 
	// return message in selected format
	private function getMessage() {
		$message = $this->Message;

		if ($this->Headers == self::HEADERS_HTML) { // if it's HTML type it's necessary to add a basic HTML structure
			$_message = "<html><body>";
			$_message .= $message;
			$_message .= "</body></html>";

			return $_message;
		}

		return $message;
	}

	// send mail
	public function Send() {
		if ($this->Subject == null || $this->Message == null || $this->Headers == null)
			throw new Exception(HLoc::l("You must complete all fields") . ".");

		$return = mail($this->To, $this->Subject, $this->getMessage(), $this->getHeaders());
		print_r(error_get_last());
		return $return;
	}
}
