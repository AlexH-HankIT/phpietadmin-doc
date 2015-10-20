<?php

require_once __DIR__ . '/parse_file.php';

// test data
$filename = '/etc/iet/initiators.allow';
$iqn = 'iqn.2014-12.com.example.iscsi:jkfhziklfzkugz500001';

$return = parse_file($filename, 'add_object_to_iqn', array('object'), false, true);

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
* This function adds a entry to the ietd allow files
* $file needs to be the last parameter!
*
* @param string $object
* @param array $file
* @return array
*/
function add_object_to_iqn($object, array $file) {
	global $iqn;

	$iqn_index = array_find_iqn($iqn, $file);

	if ($iqn_index !== false) {
		if (end($file) === "\n") {
			// if last element is a newline, delete it
			array_pop($file);
		}

		$file[$iqn_index] .= ', ' . $object;
	} else {
		$file[] = $iqn . ' ' . $object;
         }

	$return['file'] = $file;
	return $return;
}
