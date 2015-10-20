<?php

require_once __DIR__ . '/parse_file.php';

// test data
$filename = '/etc/iet/ietd.conf';

$return = parse_file($filename, 'add_global_option_from_file', array('rgaerg'), false, true);

echo "EOF\n\n";
var_dump($return);
if ($return !== false) {
	if ($return === 0) {
		echo "\n#########\nSuccess\n";
	} else {
		echo "\n#########\nFailure\n";
	}
} else {
	echo "\n#########\nFailure\n";
}

/**
* This function adds a global option to the config file
* Global options are inserted before any target definitions
* If the option is already added a error is returned
*
* @param string $option
* @param array $file
* @return array|bool
*
*/
function add_global_option_from_file($option, array $file) {
	$option_index = array_search($option, $file);

	if ($option_index === false) {
		array_unshift($file, $option);
		$return['file'] = $file;
		return $return;
	} else {
		// option is already added
		return 4;
	}
}
