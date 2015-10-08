<?php
/**
 * Documentation for the add_setting() function
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

$target->add_setting('ImmediateData', 'No');

print_r($target->get_action_result());