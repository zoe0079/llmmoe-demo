<?php
/**
 * Created by PhpStorm.
 * User: llmmoe
 * Date: 2018/3/31
 * Time: 20:33
 */

namespace app\ctrl;

/**
 * Class leave
 * @package app\control
 */
class leave extends \sys\lib\Control {
    private $staff;
    private $leave;

    /**
     * leave constructor.
     * @throws \Exception
     */
    function __construct() {
        parent::__construct();
        $this->setHead('head');
        $this->setFoot('foot');
        $this->loadModule('Staff');
        $this->loadModule('Leave');
        $this->staff = new \app\module\Staff();
        $this->leave = new \app\module\Leave();
    }

    /**
     * @throws \Exception
     */
    function homepage() {
        $leaveList = $this->leave->find();
        $this->autoLoadView('leave', array('leaveList' => $leaveList));
    }

    /**
     * @throws \Exception
     */
    public function add() {
        $this->autoLoadView('leave-add');
    }

    /**
     * @throws \Exception
     */
    public function insert() {
        $data = $this->verify($_POST);
        if(empty($data['error'])) {
            $this->leave->insert($data['result']);
            header('Location: \?mod=incoming');
        } else {
            $this->autoLoadView('leave-add', $data);
        }
    }

    /**
     * 数据验证
     * @param array $data
     * @return array
     * @throws \Exception
     */
    protected function verify(array $data) {
        $error = array();
        $result = array();

        $result['reason'] = $data['reason'];

        if(set($data['start'])) {
            $result['start'] = $data['start'];
        } else {
            $error['start'] = '必须填写';
        }

        if(set($data['end'])) {
            $result['end'] = $data['end'];
        } else {
            $error['end'] = '必须填写';
        }

        if(set($data['name'])) {
            $_p = $this->staff->find(array("name"=>$data['name']));
            if(empty($_p)) {
                $error['name'] = '此人不存在';
            } else {
                $result['positionID'] = $_p[0]['id'];
            }
        } else {
            $error['name'] = '必须填写';
        }

        if(set($data['leader'])) {
            $_l = $this->staff->find(array("name"=>$data['leader']));
            if(empty($_p)) {
                $error['leader'] = '此人不存在';
            } else {
                $result['leaderID'] = $_l[0]['id'];
            }
        } else {
            $error['leader'] = '必须填写';
        }

        if(set($data['type'])) {
            $result['type'] = $data['type'];
        } else {
            $error['type'] = '必须填写';
        }
        return array('error'=>$error, 'result'=>$result, 'value'=>$data);
    }
}