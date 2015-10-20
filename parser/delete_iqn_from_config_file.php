<?php

require_once 'parse_file.php';

// test data
$filename = '/etc/iet/ietd.conf';
//$iqn = 'iqn.2014-12.com.example.iscsi:test';
//$iqn = 'iqn.2014-12.com.example.iscsi:aergaegrdebdf';
$iqn = 'iqn.2014-12.com.example.iscsi:aregaergatbgfdgbdbfg';

$return = parse_file($filename, 'delete_iqn_from_config_file', array(), false, true);

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
* This function deletes a target definition from the config ifle
* If the target has options, a error is returned
*
* @param array $file
* @return int|array
*/
function delete_iqn_from_config_file(array $file) {
	global $iqn;

	$iqn = 'Target ' . $iqn;

	$key = array_search($iqn, $file);

        if($key !== false) {
	        // Delete the position only, if the next line contains 'Target', otherwise the iqn has options defined
        	if (isset($file[$key + 1])) {
			if (substr(preg_replace('/\s+/', '', $file[$key + 1]), 0, 6) === 'Target') {
				unset($file[$key]);
				$return['deleted'] = $key;
				$return['file'] = $file;
				return $return;
			} else {
				return 4;
			}
            	} else {
			return 3;
		}
	} else {
        	return 3;
	}
}
