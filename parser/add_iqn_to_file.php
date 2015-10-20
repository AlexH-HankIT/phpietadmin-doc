<?php

require_once 'parse_file.php';

// test data
$filename = '/etc/iet/ietd.conf';
//$iqn = 'iqn.2014-12.com.example.iscsi:testaergaergaerg';
$iqn = 'iqn.2014-12.com.example.iscsi:thsrthstrh2';

$return = parse_file($filename, 'add_iqn_to_file', array(), false, true);

echo "EOF\n\n";
var_dump($return);

/**
* This functions adds a target definition to the config file
* Duplications are handled
*
* @param array $file
* @return array|int
*/
function add_iqn_to_file(array $file) {
	global $iqn;

	$iqn = 'Target ' . $iqn;

	// Check if $iqn is already added
	$key = array_search($iqn, $file);

	if ($key === false) {
		$file[] = $iqn;
		$return['file'] = $file;
		return $return;
	} else {
		return 4;
	}
}
