<?php

require_once 'parse_file.php';

// test data
$filename = '/etc/iet/ietd.conf';
//$iqn = 'iqn.2014-12.com.example.iscsi:aergaegrdebdf';
//$iqn = 'iqn.2014-12.com.example.iscsi:jkfhziklfzkugz500001';
$iqn = 'iqn.2014-12.com.example.iscsi:thsrthstrh';

$return = parse_file($filename, 'get_all_options_from_iqn', array(), true, true);

echo "EOF\n\n";
if (is_array($return)) {
	var_dump($return);
} else if ($return !== false) {
	if ($return === 0) {
                echo "\n#########\nSuccess\n";
        } else {
                echo "\n#########\nFailure\n";
        }
} else {
	echo "\n#########\nFailure\n";
}

/**
* Return all options of a target definition
* The array will contain two indexes. Index one contains the target definition.
* Index two contains another array with all gathered options
*
* @param array $file
* @return bool|array
*
*/
function get_all_options_from_iqn(array $file) {
	global $iqn;

	$iqn = 'Target ' . $iqn;

	// Get indexes of all target definitions
        foreach ($file as $key => $line) {
		if (substr(preg_replace('/\s+/', '', $line), 0, 6) === 'Target') {
                	$keys[] = $key;
                }
	}

	// Get index of the iqn from which the option should be deleted
        $key = array_search($iqn, $file);

        if($key !== false) {
        	// Get the index of the position of the next target definition
                $temp = array_search($key, $keys);

                if($key !== false) {
		// If $keys[$temp+1], there is another target definitions after this one
                	if (isset($keys[$temp + 1])) {
                            $end = $keys[$temp + 1];
                        } else {
                            // If it's not set, the count of the array will be the last line
                            $end = count($file);
                        }

                        // If key and ned have the same value, the target definition is only one line
                        // This means there are no options to delete!
                        if ($key + 1 == $end) {
                            return 0;
                        } else {
                        	// Options for $iqn are defined between $key+1 and $end-1
                            	// If they are the same, the iqn has only one option
                            	if (strcmp($file[$key + 1], $file[$end - 1]) === 0) {
                                	if (isset($file[$key + 1])) {
                                    		// return array so we can always use a loop
                                    		return array(
							0 => explode(' ', trim($iqn, "\n")),
	                                       	 	1 => explode(' ', trim($file[$key + 1], "\n"))
						);
                                	} else {
                                    		return 3;
	                                }
				} else {
        	                        // Create array with iqn options
	                                $options = array_splice($file, $key, $end - $key);
	                                // Position 0 contains the iqn
	                                // If there is only one option, this never gets executed
	                                // Therefore we check for index 2 to be sure
	                                if (!isset($options[2])) {
	                                    return 3;
	                                } else {
	                                    foreach ($options as $key => $value) {
	                                        $return[$key] = explode(' ', trim($value, "\n"));
        	                            }

	                                    return $return;
                                	}
                            	}
                        }
		} else {
	        	return 3;
		}
       }
}
