<?php

require_once 'parse_file.php';

// test data
$filename = '/etc/iet/initiators.allow';
$iqn = 'iqn.2001-04.com.example:storage.disk1.sys4.xyz';

$return = parse_file($filename, 'parse_target_acl', array(), true, true);

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
* Get all informations from a iet allow file
*
* If no rules for this iqn are found, this function migth return the 'ALL ALL' rule if available
*
* @param array $file
* @return array|bool
*
*/
function parse_target_acl(array $file) {
	global $iqn;

	foreach ($file as $key => $line) {
               	$acls[$key] = explode(',', $line);
	}

        if (!empty($acls)) {
        	$acls = array_values($acls);

                foreach ($acls as $key => $acl) {
                	$acl[0] = trim(trim($acl[0], ' '), "\n");

                        $values[$key] = explode(' ', $acl[0]);
                        unset($acl[0]);

                        foreach ($acl as $rule) {
                        	array_push($values[$key], trim(trim($rule, ' '), "\n"));
                        }
		}
	} else {
		return 3;
	}

	if (!empty($values)) {
		$key = array_search($iqn, $values);
		return $values[$key];
	} else {
		return 3;
	}
}
