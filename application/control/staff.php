<?php
/**
 * Created by PhpStorm.
 * User: llmmoe
 * Date:
 * Time:
 */

namespace app\ctrl;
/**
 * Class staff
 * @package app\ctrl
 */
class staff extends \sys\lib\Control {
    private $breadcrumb;
    private $title;

    private $department;
    private $position;
    private $staff;

    /**
     * staff constructor.
     * @throws \Exception
     */
    function __construct() {
        parent::__construct();
        $this->breadcrumb = array(base_url()=>'首页', '人员管理');
        $this->title = '人员管理';

        $this->loadModule('Department');
        $this->loadModule('Position');
        $this->loadModule('Staff');
        $this->department = new \app\module\Department();
        $this->position = new \app\module\Position();
        $this->staff = new \app\module\Staff();

        $this->setHead('head', array('title' => '人员管理'));
        $this->setFoot('foot');
    }

    /**
     * 默认主页
     * @throws \Exception
     */
    function homepage() {
        $state['genre'] = empty($_POST['genre']) ? 'all' : $_POST['genre'];
        $staffs = $this->staff->getListByDepartment();
        $this->autoLoadView('staffList', array(
            'title'      => $this->title,
            'breadcrumb' => $this->breadcrumb,
            'state'      => $state,
            'staffList'  => $staffs
        ));
    }

    /**
     * @throws \Exception
     */
    function info() {
        $id = $_GET['id'];
        if(!isset($id)) throw new \Exception('404');
        $info = $this->staff->find(array(
                        'id' => $id
        ));
        if(empty($info)) throw new \Exception('此人不存在');
        $this->autoLoadView('staffInfo', array(
                        'info' => $info[0]
        ));
    }

    /**
     * 处理更新
     * @throws \Exception
     */
    function update_info() {
        $info = $_POST;
        $this->staff->update($info, array(
            'id' => $_GET['id']
        ));
        header('Location: ' . base_url().'?mod=staff');
    }

}