<?php

require_once 'parse_file.php';

// test data
$filename = '/etc/iet/initiators.allow';
$iqn = 'iqn.2014-12.com.example.iscsi:aregaergatbgfdgbdbfg';

$return = parse_file($filename, 'delete_object_from_iqn', array('ALL'), false, true);

echo "EOF\n\n";
var_dump($return);

/**
* This function deletes a object from a iqn definition (initiator & target allow files)
* Valid seperators between the objects are ',' and ', '
* If a line contains only the iqn and a object, it will be deleted completely
* Multiple spaces in the line which is edited will be replaced by a single one
*
* @param string $object
* @param array $file
* @return int|array
*
*/
function delete_object_from_iqn($object, array $file) {
	global $iqn;

	$iqn_index = array_find_iqn($iqn, $file);

	if ($iqn_index !== false) {
		// replace multiple spaces with a single on
		$file[$iqn_index] = preg_replace('!\s+!', ' ', $file[$iqn_index]);
		$object_length = strlen($object);
		$line_length = strlen($file[$iqn_index]);
		$iqn_length = strlen($iqn);
		$object_start_position = strpos($file[$iqn_index], $object);

		if ($object_start_position !== false) {
			// check if $object is the only one in this line
			// if that's true we also delete the iqn, which means we kill the whole line
			if ($iqn_length + 1 + $object_length === $line_length) {
				unset($file[$iqn_index]);
				$return['file'] = $file;
				$return['deleted'] = $iqn_index;
				return $return;
			}

			// Check if object is the last one
			// If start position + object length is as long as the whole line
			// we have a winner
			if ($object_start_position + $object_length === $line_length) {
				$line_with_deleted_object = str_replace(', ' . $object, '', $file[$iqn_index], $count);

				// Normally ist should only be one
				// but who knows?
				if ($count >= 1) {
					$file[$iqn_index] = $line_with_deleted_object;
					$return['file'] = $file;
					return $return;
				} else {
					// try again here without space
					$line_with_deleted_object = str_replace(',' . $object, '', $file[$iqn_index], $count);

					if ($count >= 1) {
						$file[$iqn_index] = $line_with_deleted_object;
						$return['file'] = $file;
						return $return;
					} else {
						// something went really wrong
						// can't find the object
						return 3;
					}
				}
			// Check if object is first one
			// If the object start position - 1 (for the space between the first object and the iqn) is equal
			// to the iqn length we have a winner
			} else if ($object_start_position - 1 === $iqn_length) {
				$line_with_deleted_object = str_replace($object . ', ', '', $file[$iqn_index], $count);

				// Normally ist should only be one
				// but who knows?
				if ($count >= 1) {
					$file[$iqn_index] = $line_with_deleted_object;
					$return['file'] = $file;
					return $return;
				} else {
					// try again here without space
					$line_with_deleted_object = str_replace($object . ',', '', $file[$iqn_index], $count);

					if ($count >= 1) {
						$file[$iqn_index] = $line_with_deleted_object;
						$return['file'] = $file;
						return $return;
					} else {
						// something went really wrong
						// can't find the object
						return 3;
					}
				}
			// Object is somewhere in the middle
			} else {
				$line_with_deleted_object = str_replace($object . ', ', '', $file[$iqn_index], $count);

				// Normally ist should only be one
				// but who knows?
				if ($count >= 1) {
					$file[$iqn_index] = $line_with_deleted_object;
					$return['file'] = $file;
					return $return;
				} else {
					// try again here without space
					$line_with_deleted_object = str_replace($object . ',', '', $file[$iqn_index], $count);

					if ($count >= 1) {
						$file[$iqn_index] = $line_with_deleted_object;
						$return['file'] = $file;
						return $return;
					} else {
						// something went really wrong
						// can't find the object
						return 3;
					}
				}
			}
		} else {
			return 3;
		}
	}
}
