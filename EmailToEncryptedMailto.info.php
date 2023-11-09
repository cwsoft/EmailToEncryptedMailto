<?php

namespace ProcessWire;

/**
 * EmailToEncryptedMailto.info.php
 * Provides ProcessWire with information about this module.
 */
$info = array(
    'title' => 'Email To Encrypted Mailto',
    'summary' => 'Converts text emails and mailto links into encrypted mailto links to hide them from spam bots.',
    'icon' => 'at',
    'version' => '1.1.2',
    'author' => 'cwsoft',
    'href' => 'https://github.com/cwsoft/EmailToEncryptedMailto',
    'requires' => 'PHP>=8.1',
    'autoload' => true,
);
