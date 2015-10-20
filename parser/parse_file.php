<?php
/**
* This function parses a file line by line ignoring comments and empty lines.
* It executes a callback with the file array as parameter which contains only the actually important data.
* You can modify this data in your callback and return it. The function will merge it with the comments
* and empty lines leaving them exactly as before.
*
* Supported comment types: #
*
* @param string $filename
* @param string $callback
* @param array $params
* @param bool $return_after_callback If set to true this function will directly return the data from the callback without further processing
* @param bool $test
* @return array|int
*
* Basically this function can return two types:
* int | which means there was an error or no changes at all (0 no changes/success, everything else is a error)
* array | which contains the index 'file' and the changed elements on index 'deleted' or 'added'
*
*/
function parse_file($filename, callable $callback, array $params, $return_after_callback = false, $test = false) {
	if (is_writeable($filename) === false) {
		return 1;
	}

	$file = file($filename, FILE_IGNORE_NEW_LINES);

	// loop through file
	foreach ($file as $key => $line) {
		if (empty($line) === false) {
			// check for comments
			$offset = stripos($line, '#');
			if ($offset !== false) {
				// extract the whole line if it's commented
				if ($offset === 0) {
					$comments[$key] = $file[$key];
					unset($file[$key]);
				} else {
					// extract only a part of the line
					$comments[$key] = substr($file[$key], $offset);
					$file[$key] = substr($line, 0, $offset);
				}
			}
		} else {
			// save empty lines in comments array
			$comments[$key] = $line;
			unset($file[$key]);
		}
	}

	// get the lines with config and comments
	if (empty($comments) === false) {
		$lines_with_comments_and_config = array_intersect_key($comments, $file);
	}

	// save array keys and create array with ongoing indexes
	$file_ongoing_index = array_values($file);
	$file_keys = array_keys($file);

	// do editing here
	if (empty($params)) {
		$params[0] = $file_ongoing_index;
	} else {
		array_push($params, $file_ongoing_index);
	}

	// call function
	if ($return_after_callback === true) {
		return call_user_func_array($callback, $params);
	} else {
		// call function and preserve indexes
		$callback_data = call_user_func_array($callback, $params);

		if (is_array($callback_data) === false) {
			if (is_int($callback_data)) {
				// if $callback_data is not an array, it contains a error code
				// it could also be 0 which indicates that there weren't any changes...
				return $callback_data;
			}
		}

		$callback_data_count = count($callback_data['file']);
		$file_keys_count = count($file_keys);

		if ($callback_data_count < $file_keys_count) {
			// if there are more keys than values
			// the callback deleted something
			// check the difference and delete the matching indexes from the $file_keys array
			if (isset($callback_data['deleted'])) {
				if (is_array($callback_data['deleted'])) {
					$deleted_count = count($callback_data['deleted']);
					$deleted_keys = array_keys($callback_data['deleted']);

					// delete keys in key array
					foreach ($deleted_keys as $key) {
						unset($file_keys[$key]);
					}
				} else {
					unset($file_keys[$callback_data['deleted']]);
				}
			}
		} else if ($callback_data_count > $file_keys_count) {
			// if there are more keys than values
			// the callback added something
			// so we just add a index to the key array
			// multiple keys are only hanlded in delete functions
			// there is no function which adds multiple lines
			$file_keys[] = max($file_keys) + 1;
		}

		$file = array_combine($file_keys, $callback_data['file']);
	}

	// add the comments to the config line
	if (!empty($comments)) {
		foreach ($lines_with_comments_and_config as $key => $comment) {
			$file[$key] .= $comment;
		}
	}

	// merge arrays
	$data = $file + $comments;

	// clean up
	ksort($data);

	// create string
	$data = implode("\n", $data);

	// add last newline
	$data .= "\n";

	if ($test === false) {
		// Write back
		if (file_put_contents($filename, $data) !== false) {
			return 0;
		} else {
			return 6;
		}
	} else {
		echo htmlspecialchars($data);
		return 0;
	}
}


    /**
     * Recursive function to find a iqn in array
     *
     * @param string $iqn
     * @param array $haystack
     * @return int|bool
     */
    function array_find_iqn($iqn, array $haystack) {
        foreach ($haystack as $key => $value) {
            if (false !== stripos($value, $iqn)) {
                // iqn is in $haystack[$key]
                // but we need to be sure
                // the first object and the iqn are separated by space
                // extract the iqn and compare it
                preg_match('([^\s]+)', $haystack[$key], $matches);

                if ($matches[0] === $iqn) {
                    return $key;
                } else {
                    unset($haystack[$key]);
                    array_find_iqn($iqn, $haystack);
                }
            }
        }
        return false;
    }
