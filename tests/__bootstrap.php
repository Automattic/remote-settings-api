<?php

require_once(dirname(__DIR__) . '/vendor/autoload.php');
require_once(dirname(__DIR__) . '/src/Setting.php');

$files = [
	dirname(__DIR__).'/tests/SettingsTest.php',
];

foreach ($files as $file) {
	if (file_exists($file)) {
		require_once $file;
	}
}
