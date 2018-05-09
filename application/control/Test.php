<?php
class Test extends Control {
    private $department;
    private $position;
    private $staffs;
    private $ajax;

    /**
     * Test constructor.
     */
    function __construct(){
        parent::__construct();
        $this->loadModule("department");
        $this->loadModule("position");
        $this->loadModule("staffs");
        $this->loadLib("ajax");
        $this->loadLib("excel");
        $this->department = new Department();
        $this->position = new Position();
        $this->staffs = new staff();
        $this->ajax = new ajax();
    }

    /**
     * @param $path
     * @return excel
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    function createExcel($path) {
        return new excel($path);
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    function import_card_id_from_excel() {
        echo "<pre>";
        $excel = $this->createExcel('test');
        $result = $excel->read();
        $s = array();
        foreach ($result as $i) {
            echo $i[1].$i[2].'<br />';
            $staff = $this->staffs->find(array('name' => $i[1]));
            if(empty($staff)) continue;
            $id = $staff[0]['id'];
            switch ($i[2]) {
                case "男":
                    $s['is_male'] = 1;
                    break;
                case "女":
                    $s['is_male'] = 0;
                    break;
                default:
                    $s['is_male'] = 3;
            }
            $s['card_id'] = $i[3];
//            $this->staffs->update($s, array('id' => $id));
        }
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    function import_info_from_excel(){
        echo "<pre>";
        $excel = $this->createExcel('test');
        $result = $excel->read();
        foreach($result as $staff) {
            $s = array();
            echo $s['name'] = $staff[1];
            $st = $this->staffs->find(array('name'=>$s['name']));
            if(empty($st)) {
                echo "haven't this staff\n";
            }else{
                echo "\n";
            }
            switch ($staff[3]) {
                case "男":
                    $s['is_male'] = 1;
                    break;
                case "女":
                    $s['is_male'] = 0;
                    break;
                default:
                    $s['is_male'] = 3;
            }
            echo $staff[7];
            $s['nation'] = $staff[4];
            empty($staff[5]) ? null : $s['birthday'] = str_replace(".", "-", $staff[5]).'-00';
            empty($staff[6]) ? null : $s['date_on_job'] = str_replace(".", "-", $staff[6]).'-00';
            if(empty($staff[7])){
                $s['political_status'] = "群众";
            } else {
                $s['date_in_political'] = str_replace(".", "-" ,$staff[7]).'-00';
                $s['political_status'] = "党员";
            }
            $s['education'] = $staff[8];
            $s['university'] = $staff[9];
            $s['education_in_service'] = $staff[10];
            $s['university_in_service'] = $staff[11];
            $s['status'] = $staff[12];
            print_r($s);
//            $this->staffs->update($s, array("name" => $s['name']));
        }
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    function import_name_from_excel() {
//        header("Content-type:application/vnd.ms-excel;charset=UTF-8");
//        $ajax = $this->ajax->replace(".b01", ".myDiv", "/user.txt");
        echo "<pre>";
        $_department = null;
        $excel = $this->createExcel('phone.xlsx');
        $result = $excel->read();
        print_r($result);
        foreach ($result as $_staff) {
            $_name = $_staff[1];
            $_department = empty($_staff[0]) ? $_department : $_staff[0];
            $_position = empty($_staff[2]) ? null : $_staff[2];
            $_phone = $_staff[3];
            $_short = $_staff[4];
            $staff = $this->staffs->find(array('name' => $_name));
            if(empty($staff)) {
                $department = $this->department->find(array('name' => $_department));
                if(empty($department)) {
                    $this->department->add(array("name"=>$_department));
                    $departmentID = $this->department->find(array('name'=>$_department))[0]['id'];
                } else {
                    $departmentID = $department[0]['id'];
                }
                $arrayP = explode('、', $_position);
                $position = $this->position->find(array('name' => $arrayP[0], 'departmentID' => $departmentID));
                if(empty($position)) {
                    $this->position->add(array('name'=>$arrayP[0], 'departmentID'=>$departmentID));
                    $positionID = $this->position->find(array('name' => $arrayP[0], 'departmentID' => $departmentID))[0]['id'];
                }else{
                    $positionID = $position[0]['id'];
                }
                if(array_key_exists(1, $arrayP) and !empty($arrayP[1])) {
                    $position_1 = $this->position->find(array('name' => $arrayP[1], 'departmentID' => $departmentID));
                    if(empty($position_1)) {
                        $this->position->add(array('name'=>$arrayP[1], 'departmentID'=>$departmentID));
                        $positionID_1 = $this->position->find(array('name' => $arrayP[1], 'departmentID' => $departmentID))[0]['id'];
                    }else{
                        $positionID_1 = $position_1[0]['id'];
                    }
                }
                $this->staffs->add(array(
                    "name"=>$_name,
                    "is_male"=>3,
                    "positionID"=>$positionID,
                    "positionID_1"=>isset($positionID_1)?$positionID_1 : null,
                    "positionID_2"=>isset($positionID_2)?$positionID_2 : null,
                    "phone"=>$_phone,
                    "short_phone"=>$_short,
                ));
            }
        }
//        $this->loadView('home', array("ajax" => $ajax));
    }

    /**
     * @throws Exception
     */
    function homepage() {
        $this->loadView('home');
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    function write() {
        echo "<pre>";
        $excel = $this->createExcel('test');
    	$value = $excel->read();
    	foreach($value as $n => $r) {
    	    $name = $r[1];
    	    $staff = $this->staffs->find(array("name"=>$name));
    	    if(empty($staff)) continue;
    	    $value[$n][2] = $staff[0]['is_male'] > 0 ? "男" : "女";
    	    $value[$n][3] = $staff[0]['nation'];
    	    $value[$n][4] = $staff[0]['education'];
    	    $value[$n][5] = $staff[0]['political_status'];
    	    $value[$n][6] = " " . $staff[0]['card_id'];
    	    $value[$n][7] = date("Y") - explode("-", $staff[0]['birthday'])[0];
    	    $value[$n][8] = $this->staffs->getDepartment($staff[0]['positionID'])['name'];
    	    print_r($staff);
    	}
    	$excel->setCell($value);
    	$excel->save();
    }

}
