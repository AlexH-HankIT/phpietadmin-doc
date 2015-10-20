<?php

require_once 'parse_file.php';

// test data
$filename = '/etc/iet/ietd.conf';
$iqn = 'iqn.2014-12.com.example.iscsi:test1';
//$iqn = 'iqn.2014-12.com.example.iscsi:thsrthstrh';

$return = parse_file($filename, 'delete_option_from_iqn', array('Lun 0 Type=blockio,IOMode=wt,Path=/dev/VG_data01/LV_test1'), false, true);

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
* This function deletes a option from target definitions
*
* @param string $option
* @param array $file
*
*/
function delete_option_from_iqn($option, array $file) {
	global $iqn;

	// Create iqn line
	$iqn = 'Target ' . $iqn;

        // Get indexes of all target definitions
        foreach ($file as $key => $line) {
		if (substr(preg_replace('/\s+/', '', $line), 0, 6) === 'Target') {
                	$keys[] = $key;
                }
        }

	if (is_array($keys)) {
		// Get index of the iqn from which the option should be deleted
	        $key = array_search($iqn, $file);
		if ($key !== false) {
		        // Get the index of the position of the next target definition
        		$temp = array_search($key, $keys);
	                if ($temp !== false) {
	                        // If $keys[$temp+1], there is another target definitions after this one
	                        if (isset($keys[$temp + 1])) {
       		                	$end = $keys[$temp + 1];
                		} else {
		                        // If it's not set, the count of the array will be the last line
                	            end($file);
				    $end = key($file);
                        	}

                        	// Options for $iqn are defined from $key+1 till $end - $key
	                        // Create array with iqn options
        	                $options = array_slice($file, $key + 1, $end - $key - 1, true);

                	        $val = array_search($option, $options);

	                        if ($val !== false) {
				    // delete option and index
	                            unset($file[$val]);
        	                } else {
                	            return 3;
	                        }

				$return['deleted'] = $val;
				$return['file'] = $file;
				return $return;
			} else {
                		return 3;
                	}
		} else {
        		return 3;
        	}
	} else {
		return 3;
	}
}
