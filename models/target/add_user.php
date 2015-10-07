<?php
/**
 * Documentation for the add_user() function
 *
 * 07.10.2015
 * Added
 *
 */

use phpietadmin\app\models\target;

// require the class
require_once __DIR__ . '/../registry.php';

// Create object
// if iqn doesn't exist, it will be created
$target = new target\Target('iqn.2014-12.com.example.iscsi:test2');

// Add a incoming, non-discovery user to the target
// 1 is the id of the user in the database
$target->add_user(1, false, 'IncomingUser');

print_r($target->logging->get_action_result());

// success output:
/*
Array
(
    [message] => The user test was successfully added!
    [code] => 0
    [code_type] => intern
    [method] => phpietadmin\app\models\target\Target::add_user
)
*/

// error output:
/*
Array
(
    [message] => The user test was already added as IncomingUser!
    [code] => 4
    [code_type] => intern
    [method] => phpietadmin\app\models\target\Target::add_user
)
*/