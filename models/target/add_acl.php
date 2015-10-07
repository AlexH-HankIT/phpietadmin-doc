<?php
/**
 * Documentation for the add_acl() function
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
$target = new target\Target('iqn.2014-12.com.example.iscsi:test1');

// id of acl object in database
$target->add_acl(2, 'initiators');

print_r($target->logging->get_action_result());

// example failure
/*
Array
(
	[message] => The object 127.0.0.1 is already added!
    [code] => 4
    [code_type] => intern
    [method] => phpietadmin\app\models\target\Target::add_acl
)
*/

// example success
/*
Array
(
	[message] => The object was successfully added!
	[code] => 0
    [code_type] => intern
    [method] => phpietadmin\app\models\target\Target::add_acl
)
*/