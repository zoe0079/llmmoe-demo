<?php

namespace app\ctrl;
/**
 * Class InsertFromExcel
 * @package app\ctrl
 */
class InsertFromExcel extends \sys\lib\Control {
    private $excel = null;

    /**
     * InsertFromExcel constructor.
     * @throws \Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    function __construct() {
        $this->loadLib('Excel');
        $this->excel = new \sys\lib\Excel('staff.xls');
    }

    /**
     * @throws \Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    function homepage() {
        $this->loadModule('Staff');
        $staff = new \app\module\Staff();
        $result = $this->excel->read();
        $content = array();
        foreach ($result as $k => $value) {
            $data = array();
            $data['name'] = $value[1];
            $data['is_male'] = ($value[3] == "男") ? 1 : 0;
            $data['nation'] = $value[4];
            $data['birthday'] = $value[5];
            $data['date_on_job'] = $value[6];
            $data['date_in_political'] = '';
            if(!empty($value[7])) {
                $data['date_in_political'] = ($value[7] == '党员') ? '1900-1-1' : $value[7];
            }
            $data['education'] = $value[8];
            $data['university'] = $value[9];
            $data['education_on_job'] = $value[10];
            $data['university_on_job'] = $value[11];
            switch ($value[12]){
                case '行政':
                    $data['rank'] = 1;
                    break;
                case '事业':
                    $data['rank'] = 2;
                    break;
                default:
                    $data['rank'] = 0;
            }
            switch ($value[13]){
                case '借调':
                    $data['state'] = 4;
                    break;
                case '离岗待退':
                    $data['state'] = 2;
                    break;
                case '退休':
                    $data['state'] = 3;
                    break;
                default:
                    $data['state'] = 1;
            }
            array_push($content, $data);
            $name = $data['name'];
            $_s = $staff->find(array('name' => $name));
            if(empty($_s)) {
                echo $name.'<br />';
                $staff->add($data);
            }else{
                unset($data['name']);
                $staff->update($data, array('name' => $name));
            }
        }
//        echo '<pre>';
//        print_r($content);
    }
}