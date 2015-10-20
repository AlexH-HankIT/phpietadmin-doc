<?php

require_once __DIR__ . '/parse_file.php';

// test data
$filename = '/etc/iet/ietd.conf';
$iqn = 'iqn.2014-12.com.example.iscsi:jkfhziklfzkugz500001';

$return = parse_file($filename, 'delete_global_option_from_file', array('rgaerg'), false, true);

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
* This function deletes a global option from the config file
*
* @param string $option
* @param array $file
* @return array|int
*/
function delete_global_option_from_file($option, array $file) {
	foreach ($file as $key => $line) {
		if (substr(preg_replace('/\s+/', '', $line), 0, 6) === 'Target') {
			// $keys[0] will contain the first target definition
			// Everything before $keys[0] is global
			$keys[] = $key;
			break;
		}
	}

	if (is_array($keys)) {
		$global_section = array_splice($file, 0, $keys[0]);
		$option_index = array_search($option, $global_section);

		if ($option_index !== false) {
			unset($global_section[$option_index]);
			$return['file'] = array_merge($global_section, $file);
			$return['deleted'] = $option_index;
			return $return;
		} else {
			// options not found
			return 3;
		}
	} else {
		// no target definitions
		// everything is global
		$option_index = array_search($option, $file);
		if ($option !== false) {
			unset($option[$option_index]);
			$return['file'] = $file;
			$return['deleted'] = $option_index;
			return $return;
		} else {
			// option not found
			return 3;
		}
	}
}
