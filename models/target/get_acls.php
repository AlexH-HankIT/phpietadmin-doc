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

// Create object
$target = new target\Target('iqn.2014-12.com.example.iscsi:test1');

$data = $target->get_acls();

var_dump($data);

// No acls available
/*
bool(false)
*/

// acls available
/*
array(7) {
  [0]=>
  string(50) "iqn.2014-12.com.example.iscsi:test1"
  [1]=>
  string(3) "ALL"
  [2]=>
  string(12) "127.0.0.1"
  [3]=>
  string(4) "127.0.0.2"
  [4]=>
  string(5) "127.0.0.3"
  [5]=>
  string(5) "127.0.0.4"
  [6]=>
  string(5) "127.0.0.5"
}

*/