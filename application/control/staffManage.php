<?php
/**
 * Created by PhpStorm.
 * User: llmmoe
 * Date: 2017/11/5
 * Time: 22:23
 */

class staffManage extends Control {
    private $department;
    private $position;
    private $staffs;
    private $ajax;
    private $excel;

    function __construct() {
//        parent::__construct();
        $this->loadModule("department");
        $this->loadModule("position");
        $this->loadModule("staffs");
        $this->loadLib("ajax");
        $this->loadLib("excel");
        $this->department = new Department();
        $this->position = new Position();
        $this->staffs = new staff();
        $this->ajax = new ajax();
        $this->excel = new excel();
    }

    function add() {
        $data['name'] = "侯皓";
        $data['is_male'] = 1;
        $data['phone'] = "15285062786";
        $data['short_phone'] = "677777";
        $department_id = $this->department->find(array("name" => "食品药品监管站"))[0]["id"];
        $data['position_id'] = $this->position->find(array("name" => "文广站负责人", "department_id" => $department_id))[0]['id'];
        print_r($data);
    }
}