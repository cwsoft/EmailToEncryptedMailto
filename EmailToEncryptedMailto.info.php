<?php namespace ProcessWire;

/**
 * EmailToEncryptedMailto.info.php
 * Provides ProcessWire with information about this module.
 */
$info = array(
    'title' => 'Email To Encrypted Mailto',
    'summary' => 'Fetches all text emails from a page and converts them into encrypted mailto links.',
    'version' => 3,
    'author' => 'cwsoft',
    'href' => 'https://github.com/cwsoft/pwEmailToEncryptedMailto',
    'requires' => 'PHP>=8.1',
    'autoload' => true,
);