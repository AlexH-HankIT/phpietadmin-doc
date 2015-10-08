<?php
/**
 * Documentation for the delete_target() function
 *
 * 08.10.2015
 * Added
 *
 */

use phpietadmin\app\models\target;

// require the class
require_once __DIR__ . '/../registry.php';

// Create object
// if iqn doesn't exist, it will be created
$target = new target\Target('iqn.2014-12.com.example.iscsi:test1');

// param1: true/false disconnect initiators ("force" option)
// param2: true/false delete all access lists of the target
// param3: true/false delete all attached luns (if false, they will be detached)

$target->delete_target(true, true, false);

print_r($target->logging->get_action_result());

// Success output:
/*
Array
(
    [message] => The target iqn.2014-12.com.example.iscsi:test1 was successfully deleted!
    [code] => 0
    [code_type] => intern
    [method] => phpietadmin\app\models\target\Target::delete_target
)
*/

// Failure output:
/*
Array
(
    [message] => Could not delete the target iqn.2014-12.com.example.iscsi:test1 from the daemon!
    [code] => 254
    [code_type] => extern
    [status] => Array
        (
            [0] => No such file or directory.
        )

    [method] => phpietadmin\app\models\target\Target::delete_target
)
*/