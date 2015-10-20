<?php

require_once 'parse_file.php';

$filename = '/etc/iet/ietd.conf';
//$iqn = 'iqn.2014-12.com.example.iscsi:test';
$iqn = 'iqn.2014-12.com.example.iscsi:thsrthstrh';

$return = parse_file($filename, 'add_option_to_iqn', array('mega option3'), false, true);

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
* This function adds a option to a target definition
* $file needs to be the last parameter!
* No duplication checks here, because the same option can be configured for multiple targets
*
* @param string $option
* @param array $file
* @return int|bool
*/
function add_option_to_iqn($option, array $file) {
	global $iqn;

	$iqn = 'Target ' . $iqn;

	// Search for the line containing the iqn
       	$key = array_search($iqn, $file);

	// If key is false, the iqn doesn't exist
        if($key !== false) {
		// Add the option to the array, one line after the match
		// The other indexes will be correct automatically
		$file[$key] .= "\n" . $option;
		$return['file'] = $file;
		return $return;
        } else {
		return 3;
        }
}
