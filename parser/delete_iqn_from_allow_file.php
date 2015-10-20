<?php

require_once 'parse_file.php';

// test data
$filename = '/etc/iet/initiators.allow';
$iqn = 'iqn.2014-12.com.example.iscsi:aregaergatbgfdgbdbfg';

$return = parse_file($filename, 'delete_iqn_from_allow_file', array(), false, true);

echo "EOF\n\n";
var_dump($return);

/**
*/
function delete_iqn_from_allow_file(array $file) {
	global $iqn;

	$iqn_index = array_find_iqn($iqn, $file);

	if ($iqn_index !== false) {
		unset($file[$iqn_index]);
		$return['deleted'] = $iqn_index;
		$return['file'] = $file;
		return $return;
	} else {
		return 3;
	}
}
