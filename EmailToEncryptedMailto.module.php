<?php namespace ProcessWire;

/**
 * Class to turn emails into encrypted mailto links on the fly.
 * Autoloaded with ProcessWire and ready to use without configuration.
 */
class EmailToEncryptedMailto extends WireData implements Module {
	/**
	 * Array with emails found in the active page html.
	 * @var array
	 */
	private $emails = array();
 
	/**
  	* Regex pattern used to scan html for email addresses.
  	* @var string
  	*/
	private $pattern = '#(?<email>[\._a-z0-9-]+@[\._a-z0-9-]+)#i';

	/**
  	* Add hook after ProcessWire page is rendered to modifiy the active page html.
  	* @return void
  	*/
	public function ready() : void {
    	// Limit to frontend templates only.
		if ($this->page->template == 'admin') return;
		$this->addHookAfter('Page::render', $this, 'process');
  	}

	/**
	 * Scans active page for emails and converts them into encrypted mailto links.
	 * @param \ProcessWire\HookEvent $event
	 * @return void
	 */
	protected function process(HookEvent $event) : void {
		// Only proceed if modules javascript decrypt file exists.
		$html = $this->addModuleFilesIntoHead($event->return);
		if ($html == $event->return) return;

		// Only proceed if actual page contains at least one email.
		$this->fetchEmails($html);
		if (empty($this->emails)) return;

		// Replace all emails with encrypted mailto links.
		foreach ($this->emails as $email) {
			$mailto = $this->createEncryptedMailtoLink($email, $subject=__('Your Request'));
			$html = str_replace($email, $mailto, $html);
		}

		$event->return = $html;
  	}
	
	/**
	 * Helper method to add required module CSS and Javascript files into page head.
	 * @param string $html
	 * @return string
	 */
	private function addModuleFilesIntoHead(string $html) : string {
		// Only proceed if module files exist.
		if (!is_readable(__DIR__ . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'cdc.min.js')) return $html;

		// Inject required module CSS and Javascript files into pages <head> section.
		$jsPath = basename(__DIR__) . '/js/cdc.min.js';
		$jsLink = "<script async src='{$this->config->urls->siteModules}/{$jsPath}'></script>";

		return str_replace('</head>', $jsLink . "\n</head>", $html);
	}

	/**
	 * Helper method to fetch all emails from actual page html.
	 * @param string $text
	 * @return void
	 */
	private function fetchEmails(string $text) : void {
		$this->emails = array();
		if (!empty($text) && strpos($text, '@') > 0 && preg_match_all($this->pattern, $text, $matches)) {
			foreach($matches['email'] as $email) {
				if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$this->emails[] = $email;
				}
			}
		}
		$this->emails = array_unique($this->emails);
	}

	/**
	 * Helper method to turn an email into an encrypted mailto link.
	 * @param string $email
	 * @param string $subject
	 * @return string
	 */
	private function createEncryptedMailtoLink(string $email, string $subject) : string {
		// String with allowed characters.
		$allowedCharacters = 'abcdefghijklmnopqrstuvwxyz@.-_:';
		$numberAllowedCharacters = strlen($allowedCharacters);

		// Check user inputs and create a mailto email link.
		$mailto = strtolower(trim($email));
		if (strpos($mailto, 'mailto:') === false) $mailto = "mailto:$mailto";
		
		// Create random shift position for the Caesar algorithm.
		$shift = rand(1, $numberAllowedCharacters - 1);
		if ($mailto == '' || abs($shift) > $numberAllowedCharacters - 1) return $email;

		// Encrypt mailto part using Caesar shift algorithm.
		$cipher = '';
		for ($i = 0; $i < strlen($mailto); $i++) {
			// Get position of actual character in allowed characters.
			$index = strpos($allowedCharacters, $mailto[$i]);
			// Get position of encrypted character, ensure position is valid.  
			$index = ($index + $shift) % $numberAllowedCharacters;
			if ($index < 0) $index = $index + $numberAllowedCharacters; 
			// Build cipher.
			$cipher .= $allowedCharacters[$index];
		}

		// Replace "@" by "(@)" and "." by "(.)" and wrap brackets in hidden span tags.
		$eMailAtPart = '<span hidden>(</span>@<span hidden>)</span>';
		$eMailDotParts = '<span hidden>(</span>.<span hidden>)</span>';
		$eMailDisplayed = str_replace(array('@', '.'), array($eMailAtPart, $eMailDotParts), $email);

		// Build clickable encrypted Javascript mailto link.
		$mailtoLink = "<a href=\"javascript:cdc('" . $cipher . "','" . $subject . chr(64 + $shift) ."')\">" .$eMailDisplayed . "</a>";
		return $mailtoLink;		
	}	
}