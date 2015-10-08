<?php
/**
 * Documentation for the get_acl() function
 *
 * 08.10.2015
 * Added
 *
 */

use phpietadmin\app\models\target;

// require the class
require_once __DIR__ . '/../registry.php';

// Create object<
$target = new target\Target('iqn.2014-12.com.example.iscsi:test1');

$data = $target->get_acls();

print_r($data);