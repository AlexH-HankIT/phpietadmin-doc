<?php
/**
 * Documentation for the add_target() function
 *
 * 08.10.2015
 * Added
 *
 */

use phpietadmin\app\models\target;

// require the class
require_once __DIR__ . '/../registry.php';

// Create object
$target = new target\Target('iqn.2014-12.com.example.iscsi:test1');

// create target
$target->add();

// retrieve log
print_r($target->logging->get_action_result());

// Success output:
/*
Array
(
    [message] => The target iqn.2014-12.com.example.iscsi:test1 was successfully added
    [code] => 0
    [code_type] => intern
    [method] => phpietadmin\app\models\target\Target::add
)
*/

// Failure output:
/*
Array
(
    [message] => The target iqn.2014-12.com.example.iscsi:test1 is already in use!
    [code] =>
    [code_type] =>
    [method] => phpietadmin\app\models\target\Target::add
)
*/