<?php

namespace ProcessWire;

/**
 * Class to turn text emails and regular mailto links into encrypted mailto links.
 * Autoloaded with ProcessWire and ready to use without configuration.
 */
class EmailToEncryptedMailto extends WireData implements Module {
	/**
	 * Regex pattern to extract text emails from text excluding emails inisde link tags and input fields.
	 * Email addresses found are captured in group <email>.
	 * @var string
	 */
	private $patternEmail = '#<a [^>]+>.*?</a>(*SKIP)(*FAIL)|<input [^>]+>(*SKIP)(*FAIL)|(?<email>[\._a-z0-9-]+@[\._a-z0-9-]+)#i';

	/**
	 * Regex pattern to extract mailto links from text.
	 * Mail address of the mailto link is captured in group <email>, the mailto text part in group <text>.
	 * Optional attributes within mailto link like id/class/data-attr are captured in the groups <apre> and <asuf>.
	 * @var string
	 */
	private $patternMailto = '#<a\s*(?<apre>[^>]+)\s*\bhref=([\'"])mailto:(?<email>.*?)\2(?<asuf>[^>]*?)>(?<text>.*?)</a>#i';

	/**
	 * Register hook after ProcessWire page is rendered to replaceEmails into encrypted mailto links.
	 * @return void
	 */
	public function ready(): void {
		if ($this->page->template != 'admin') {
			$this->addHookAfter('Page::render', $this, 'encryptEmails');
		}
	}

	/**
	 * Scans page for text emails and regular mailto links and converts them into encrypted mailto links.
	 * @param \ProcessWire\HookEvent $event
	 * @return void
	 */
	protected function encryptEmails(HookEvent $event): void {
		// Check if actual page should be skipped from encryption.
		if ($this->skipEmailEncryption($event)) return;

		// Only proceed if javascript decrypt file could be loaded into head.
		$html = $this->addModuleFilesIntoHead($event->return);
		if ($html == $event->return) return;

		// Replace mailto links and text emails of actual page with encrypted mailto link.
		$html = preg_replace_callback($this->patternMailto, array($this, 'replaceMailtoLinks'), $html);
		$html = preg_replace_callback($this->patternEmail, array($this, 'replaceTextEmails'), $html);

		// Return modified page html.
		$event->return = $html;
	}

	/**
	 * Helper function to check if actual page should be skipped from email encryption.
	 * @return boolean
	 */
	private function skipEmailEncryption($event): bool {
		// Extract pageIds to skip from module config.
		$pageIdsToSkip = trim($this->pageIdsToSkip);

		// Don't encrypt emails if page contains no '@' char or user forced to skip encryption via module config.
		if ((strpos($event->return, '@') == -1) || ($pageIdsToSkip == '-1')) return true;

		// Convert string with comma separated page IDs into array of integer values.
		$pageIdsToSkip = strpos($pageIdsToSkip, ',') > -1 ? explode(',', $pageIdsToSkip) : [$pageIdsToSkip];
		$pageIdsToSkip = array_map('intval', $pageIdsToSkip);

		// Check if actual pageId is contained in array of pageIds to skip.
		return in_array($this->page->id, $pageIdsToSkip);
	}

	/**
	 * Helper method to add required module CSS and Javascript files into page head.
	 * @param string $html
	 * @return string
	 */
	private function addModuleFilesIntoHead(string $html): string {
		// Only proceed if module files exist.
		if (!is_readable(__DIR__ . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'cdc.js')) return $html;

		// Inject required module CSS and Javascript files into pages <head> section.
		$jsPath = basename(__DIR__) . '/js/cdc.js';
		$jsLink = "<script async src='{$this->config->urls->siteModules}/{$jsPath}'></script>";

		return str_replace('</head>', $jsLink . "\n</head>", $html);
	}

	/**
	 * Helper method to replace regular mailto links into encrypted mailto links.
	 * @param array $matches
	 * @return string
	 */
	private function replaceMailtoLinks(array $matches): string {
		// Extract matching string and named groups.
		$match  = $matches[0];
		$email = $matches['email'];
		$mailtoText = $matches['text'];
		$aPrefix = $matches['apre'];
		$aSuffix = $matches['asuf'];

		// Extract optional subject from email part.
		$subject = '';
		$position = strpos($email, '?subject=');
		if ($position > -1) {
			$subject = substr($email, $position + strlen('?subject='), strlen($email));
			$email = substr($email, 0, $position);
		}

		// Only replace strings if we have a valid email.
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return $match;

		// Replace mailto link with encrypted mailto link.
		return $this->createEncryptedMailtoLink($email, $subject, $mailtoText, $aPrefix, $aSuffix);
	}

	/**
	 * Helper method to replace text emails into encrypted mailto links.
	 * @param array $matches
	 * @return string
	 */
	private function replaceTextEmails(array $matches): string {
		// Extract matching string and named groups.
		$match = $matches[0];
		$email = $matches['email'];

		// Only replace strings if we have a valid email.
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return $match;

		return $this->createEncryptedMailtoLink($email);
	}

	/**
	 * Helper method to turn an email into an encrypted mailto link.
	 * @param string $email
	 * @param string $subject [optional]
	 * @param string $mailtoText [optional]
	 * @param string $aPrefix [optional], e.g. class="myclass"
	 * @param string $aSuffix [optional], e.g. data-mydata="mydata"
	 * @return string 
	 */
	private function createEncryptedMailtoLink(
		string $email,
		string $subject = '',
		string $mailtoText = '',
		string $aPrefix = '',
		string $aSuffix = ''
	): string {
		// Add default mailto subject if needed.
		if (empty($subject)) $subject = sprintf(__('Your Request'));

		// Replace double quotes in optional prefix and suffix link parts.
		$aPrefix = str_replace('"', "'", $aPrefix);
		$aSuffix = str_replace('"', "'", $aSuffix);

		// Use email as visible mailto part if empty.
		if (empty($mailtoText)) $mailtoText = $email;

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
		$eMailText = str_replace(array('@', '.'), array($eMailAtPart, $eMailDotParts), $mailtoText);
		$shiftChar = chr(64 + $shift);

		// Build encrypted Javascript mailto link preserving optional class/id/data attributes.
		$mailtoLink = "<a $aPrefix href=\"javascript:cdc('$cipher','$subject$shiftChar')\" $aSuffix>$eMailText</a>";
		return $mailtoLink;
	}
}
