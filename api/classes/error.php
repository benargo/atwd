<?php namespace uwe\atwd;

if(!defined('BASEDIR')) exit('No direct script access allowed');

class error {

	private $error_code;
	private $description;

	private $log = false;
	private $type = E_USER_WARNING;
	private $message;
	public $file;
	public $line;

	/**
	 * __construct()
	 *
	 * @access public
	 * @param int $error_code
	 * @param $message = NULL
	 * @param $line = NULL
	 * @return void
	 */
	function __construct($error_code, $message = NULL, $line = 'undefined')
	{
		$this->error_code = (int) $error_code;
		$this->setDescription();
		
		// Set error log related vars
		if($message)
		{
			$this->setMessage($message);
		}
		global $_CEMS_SERVER;
		$this->file = $_CEMS_SERVER['SCRIPT_FILENAME'];
		$this->line = $line;
	}

	/**
	 * setDescription()
	 * 
	 * Switch statement to match the error code to a description
	 *
	 * @access private
	 * @return void
	 */
	private function setDescription()
	{
		switch($this->error_code)
		{
			case '404':
				$this->description = 'Not Found';
				break;

			case '500':
			default:
				$this->description = 'Service Error';
				break;

			case '501':
				$this->description = 'URL pattern not recognized';
				break;
		}
	}

	/**
	 * setType()
	 *
	 * @access public
	 * @param int $type
	 * @return void
	 */
	public function setType($error_type)
	{
		switch($error_type)
		{
			// Useful snippit taken from http://php.net/manual/en/errorfunc.constants.php

			 case E_ERROR: // 1 //
	            $this->type = 'E_ERROR';
	        case E_WARNING: // 2 //
	            $this->type = 'E_WARNING';
	        case E_PARSE: // 4 //
	            $this->type = 'E_PARSE';
	        case E_NOTICE: // 8 //
	            $this->type = 'E_NOTICE';
	        case E_CORE_ERROR: // 16 //
	            $this->type = 'E_CORE_ERROR';
	        case E_CORE_WARNING: // 32 //
	            $this->type = 'E_CORE_WARNING';
	        case E_CORE_ERROR: // 64 //
	            $this->type = 'E_COMPILE_ERROR';
	        case E_CORE_WARNING: // 128 //
	            $this->type = 'E_COMPILE_WARNING';
	        case E_USER_ERROR: // 256 //
	            $this->type = 'E_USER_ERROR';
	        case E_USER_WARNING: // 512 //
	            $this->type = 'E_USER_WARNING';
	        case E_USER_NOTICE: // 1024 //
	            $this->type = 'E_USER_NOTICE';
	        case E_STRICT: // 2048 //
	            $this->type = 'E_STRICT';
	        case E_RECOVERABLE_ERROR: // 4096 //
	            $this->type = 'E_RECOVERABLE_ERROR';
	        case E_DEPRECATED: // 8192 //
	            $this->type = 'E_DEPRECATED';
	        case E_USER_DEPRECATED: // 16384 //
	            $this->type = 'E_USER_DEPRECATED'; 
		}
	}

	/**
	 * getMessageAsText()
	 *
	 * @access public
	 * @return text/plain
	 */
	public function getMessageAsText()
	{
		return $this->message;
	}

	/**
	 * getMessageAsHTML()
	 *
	 * @access public
	 * @return text/html
	 */
	public function getMessageAsHTML()
	{
		$message = explode("\n", $this->message);
		$return  = '';
		foreach($message as $line)
		{
			$return .= '<p>'.$line.'</p>';
		}
		return $return;
	}

	/**
	 * setMessage()
	 *
	 * @access public
	 * @param mixed $message
	 * @return void
	 */
	public function setMessage($message)
	{
		if(is_array($message))
		{
			$this->message = implode("\n", $message);
		}
		else
		{
			$this->message = $message;
		}
	}

	/**
	 * log()
	 *
	 * Logs the error in the error log file
	 * 
	 * @access public
	 * @return void
	 */
	public function log()
	{
		if(!$this->log)
		{
			$this->log = true;
			$log_file = fopen(BASEDIR .'logs/error.log', 'a+');
			fwrite($log_file, date('r') .': '. $this->type .'; "'. $this->getMessageAsText() .'" on line '. $this->line .' of '. $this->file ."\n");
			fclose($log_file);
		}
	}

	/**
	 * respond()
	 *
	 * Respond to the error as XML
	 *
	 * @access public
	 * @return text/html
	 */
	public function respond()
	{
		$this->log();

		header('Content-type: text/xml');
		http_response_code($this->error_code);
		$dom = new \DOMDocument;
		$dom->formatOutput = true;

		$node = $dom->createElement('response');
		$node->setAttribute('timestamp', time());
		$response = $dom->appendChild($node);

		$node = $dom->createElement('error');
		$node->setAttribute('code', $this->error_code);
		$node->setAttribute('desc', $this->description);
		$response->appendChild($node);

		return $dom->saveXML();
	}

}