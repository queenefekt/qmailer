<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Email message building and sending.
 *
 * @author     Łukasz Marcin Dominikowski QueenEfekt.pl
 * @copyright  (c) 2013 Łukasz Marcin Dominikowski
 */
class Kohana_Qmailer {

	// Phpmailer
	protected $_pm;
	
	// Message content
	public $message;
	
	// From email
	public $fromEmail = "";
	
	// From name
	public $fromName = "";
	
	/**
	 * Constructor of Qmailer
	 *
	 * @param	string		set receiver
	 * @param	string		title of message
	 * @param	string		content of message
	 * @return	self
	 */
	public function __construct($to = FALSE, $title = FALSE, $message = FALSE)
	{
		// Load PHPmailer
		require_once Kohana::find_file('vendor/phpmailer', 'class.phpmailer');

		// New phpmailer
		$this->_pm = new PHPMailer();	
		
		// If set receiver
		if($to)
		{
			// Set email receiver
			$this->to($to);
			
			// Set title of message
			$this->subject($title);
			
			// Set content of message
			$this->message($message ? $message : " ");
			
			// Send message
			$this->send();
			echo $this->errors;
		}
		
		return $this; 			
	}
	
	/**
	 * Add email receiver
	 *
	 * @param	string	email address
	 * @param	string	name; default empty
	 * @return	self
	 */
	public function to($address, $name = '')
	{
		$this->_pm->AddAddress($address, $name = '');
		
		return $this; 		
	}	
	
	/**
	 * Set subject of message
	 *
	 * @param	string	message subject
	 * @return	self
	 */
	public function subject($subject)
	{
		$this->_pm->Subject = $subject;

		return $this;		
	}
	
	/**
	 * Content of message
	 *
	 * @param	string	message
	 * @return	self
	 */
	public function message($message)
	{
		$this->message = $message;		

		return $this;	
	}	
	
	/**
	 * Add email bcc receiver
	 *
	 * @param	string	email address
	 * @param	string	name; default empty
	 * @return	self
	 */
	public function bcc($address, $name = '')
	{
		$this->_pm->AddBCC($address, $name = '');	

		return $this;		
	}
	
	/**
	 * Add reply to address and name
	 *
	 * @param	string	email address
	 * @param	string	name; default empty
	 * @return	self
	 */
	public function replyTo($address, $name = '')
	{
		$this->_pm->AddReplyTo($address, $name = '');

		return $this;			
	}	
	
	/**
	 * Set from address and name
	 *
	 * @param	string	email address
	 * @param	string	name; default empty
	 * @return	self
	 */
	public function from($address, $name = '')
	{
		$this->fromEmail = $address;
		$this->fromName = $name;

		return $this;	
	}	
		
	/**
	 * Add attachment
	 *
	 * @param	string	path to file
	 * @param	string	file name
	 * @return	self
	 */
	public function AddAttachment()
	{
		$this->_pm->AddAttachment($path, $name = '');

		return $this;	
	}
		
	/**
	 * Set language
	 *
	 * @param	string	language code ISO 639-1 2-character language code
	 * @param	string	path to the language file directory
	 * @return	self
	 */
	public function SetLanguage($langcode = 'en', $lang_path = 'language/')
	{
		$this->_pm->SetLanguage($langcode, $lang_path);

		return $this;	
	}
	
	/**
	 * Send message
	 *
	 * @param	int		debug mode 0|1|2
	 * @return	bool	true or false if error
	 */
	public function send($debug = 0)
	{
		// Load config
		$config = Kohana::$config->load('qmailer')->as_array();
		
		// If smtp mode
		if(Arr::get($config, 'mode', FALSE) == "smtp")
		{
			// Set smtp mode
			$this->_pm->IsSMTP();
			
			// Set SMTP debug mode
			$this->_pm->SMTPDebug  = $debug;
			
			// SMTP authentication
			$this->_pm->SMTPAuth   = TRUE;
			
			// Hostname of the email server
			$this->_pm->Host       = Arr::get($config, 'host', FALSE);
			
			// Set the SMTP port number
			$this->_pm->Port       = Arr::get($config, 'port', FALSE);;
						
			// Username to use for SMTP authentication
			$this->_pm->Username   = Arr::get($config, 'username', FALSE);
			
			// Password to use for SMTP authentication
			$this->_pm->Password   = Arr::get($config, 'password', FALSE);
			
		} else 
		{
			// Set default mail mode
			$this->_pm->IsMail();	
		}
		
		// Check data, set default values
		$this->_valid();
		
		// Set charset	
		$this->_pm->CharSet = "UTF-8";

		// Set from		
		$this->_pm->setFrom($this->fromEmail, $this->fromName, TRUE);
		
		// Set HTML message
		$this->_pm->MsgHTML($this->message);
		
		// Set error info
		$this->errors = $this->_pm->ErrorInfo;

		// Send message
		return $this->_pm->Send();
	}	
	
	/**
	 * Check data and set default values
	 *
	 * @return self
	 */
	 private function _valid()
	 {
		// Load config
		$config = Kohana::$config->load('qmailer')->as_array();
		
		// Check from email
		if($this->fromEmail === "")
		{
			$this->fromEmail = Arr::get($config, "fromEmail", Arr::get($config, "username") );
		}
		
		// Check from name
		if($this->fromName === "")
		{
			$this->fromName = Arr::get($config, "fromName");
		}
		
		// Check subject
		if($this->_pm->Subject === "")
		{
			$this->_pm->Subject = Arr::get($config, "subject");
		}
		
		// Check message
		if($this->message === "")
		{
			$this->message = Arr::get($config, "message");
		}
		
		// Check message
		if($this->message === "")
		{
			$this->message = Arr::get($config, "message");
		}		
	 }
}
