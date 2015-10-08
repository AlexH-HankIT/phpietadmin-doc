<?php
/**
 * Documentation for the return_all_used_lun() function
 *
 * 08.10.2015
 * Added
 *
 */

use phpietadmin\app\models\target;
use phpietadmin\app\models\lvm\lv;

// no error handling via logging function get_action_result() included!

// require the class
require_once __DIR__ . '/../registry.php';

// Create object
$target = new target\Target('iqn.2014-12.com.example.iscsi:test1');

// call function
$data = $target->return_all_used_lun();

if ($data !== false) {
    print_r($data);
} else {
    var_dump($data);
}

// success output:
/*
Array
(
    [0] => /dev/VG_data01/test3
    [1] => /dev/VG_data02/LV_test01
    [2] => /dev/VG_data01/test4
)
*/

// error output
/*
bool(false)
*/