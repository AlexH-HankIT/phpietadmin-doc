<?php
require_once 'parse_file.php';

// test data
$filename = '/etc/iet/ietd.conf';
//$iqn = 'iqn.2014-12.com.example.iscsi:aregaergatbgfdgbdbfg';
//$iqn = 'iqn.2014-12.com.example.iscsi:test1';
//$iqn = 'iqn.2014-12.com.example.iscsi:345345';
//$iqn = 'iqn.2014-12.com.example.iscsi:3453453445345';
//$iqn = 'iqn.2014-12.com.example.iscsi:thsrthstrh';
//$iqn = 'iqn.2014-12.com.example.iscsi:jkfhziklfzkugz500001';
$iqn = 'iqn.2014-12.com.example.iscsi:thsrthstrh';

$return = parse_file($filename, 'delete_all_options_from_iqn', array(), false, true);

echo "EOF\n\n";
var_dump($return);

/**
*/
function delete_all_options_from_iqn(array $file) {
	global $iqn;

	$iqn = 'Target ' . $iqn;

	// Get indexes of all target definitions
	foreach ($file as $key => $line) {
		// delete all whitespaces and check if the first six letters spell target
		if (substr(preg_replace('/\s+/', '', $line), 0, 6) === 'Target') {
			$keys[] = $key;
		}
	}

	if (is_array($keys)) {
		// Get index of the iqn from which the option should be deleted
		$iqn_index = array_search($iqn, $file);

		if ($iqn_index !== false) {
			// get key of this iqn
			$this_target_definition_key_index = array_search($iqn_index, $keys);

			if ($this_target_definition_key_index !== false) {
				if ($this_target_definition_key_index === count($keys) - 1) {
					// target is last one

					// get count of $file (-1 to match array indexes)
					$file_count = count($file) - 1;
					$options_count = $file_count - $keys[$this_target_definition_key_index];

					if ($options_count !== 0) {
						$this_target_definition_first_option = $keys[$this_target_definition_key_index] + 1;

						// since this is the last target, we can delete everything from here
						// save the values which will be deleted in array
						$return['deleted'] = array_slice($file, $this_target_definition_first_option, NULL, true);

						// delete options
						array_splice($file, $this_target_definition_first_option);
					} else {
						return 0;
					}
				} else {
					// target is not last one
					$this_target_definition_options_end = $keys[$this_target_definition_key_index + 1];
					$this_target_definition_options_start = $iqn_index + 1;
					$this_target_definition_count = $this_target_definition_options_end - $this_target_definition_options_start;

					if ($this_target_definition_count > 0) {
						// since this is the last target, we can delete everything from here
						// save the values which will be deleted in array
						$return['deleted'] = array_slice($file, $this_target_definition_options_start, $this_target_definition_count, true);
						array_splice($file, $this_target_definition_options_start, $this_target_definition_count);
					} else {
						return 0;
					}
				}
			}
			$return['file'] = $file;
			return $return;
		} else {
			return 3;
		}
	} else {
		return 3;
	}
}
