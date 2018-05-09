<?php
/**
 * Created by PhpStorm.
 * User: llmmoe
 * Date: 2017/11/30
 * Time: 21:49
 */

class notice extends Control {
    private $staff;

    function __construct() {
        parent::__construct();

        $this->loadModule("staffs");

        $this->staff = new staff();
    }

    function homepage() {
        $staff_list = array();
        foreach ($_POST as $id => $k) {
            $_staff = $this->staff->find(array("id"=>$id));
            if(!empty($_staff)) array_push($staff_list, $_staff[0]);
        }
        $this->loadView("notice", array("staff_list"=>$staff_list));
    }
}