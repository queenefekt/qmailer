<?php defined('SYSPATH') OR die('No direct access allowed.');
	return array(
		// Mode 'smtp' or 'mail'
		'mode' 		=> 'smtp',
		
		// SMTP host; required if smtp mode 
		'host' 		=> 'smtpout.europe.secureserver.net',
		
		// SMTP port (80, 3535, 25, 465 (SSL))
		'port' 		=> 25,
		
		// SMTP account username
		'username' 	=> 'test@queenefekt.pl',
		
		// SMTP account password
		'password' 	=> 'test333',
				
		// Default From Email
		'fromEmail'	=> 'smtpout.europe.secureserver.net',
		
		// Default From Name
		'fromName'	=> 'QE - test@queenefekt.pl',
		
		// Default subject
		'subject'	=> 'Test Message',
		
		// Default message
		'message'	=> 'This message was sent by Qmailer - kohana module using phpmailer',
	);