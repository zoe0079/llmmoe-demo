<?php
/**
 * Created by PhpStorm.
 * User: llmmoe
 * Date: 2018/3/6
 * Time: 14:34
 */

namespace app\ctrl;
use Dompdf\Exception;

/**
 * 来电
 * Class incoming
 * @package app\control
 */
class incoming extends \sys\lib\Control {
    private $breadcrumb;
    private $title;

    private $incoming;
    private $staff;

    /**
     * incoming constructor.
     * @throws \Exception
     */
    function __construct() {
        parent::__construct();

        $this->title = '来电';
        $this->breadcrumb = array(base_url()=>'首页', $this->title);

        $this->loadModule('Incoming');
        $this->loadModule('Staff');
        $this->incoming = new \app\module\Incoming();
        $this->staff = new \app\module\Staff();

        $this->setValue(array(
            'title'      => $this->title,
            'breadcrumb' => $this->breadcrumb,
        ));
        $this->setHead('head');
        $this->setFoot('foot');
    }

    /**
     * 来电列表
     * @throws \Exception
     */
    function homepage() {
        $incomingList = $this->incoming->getList();
        $this->autoLoadView('incoming', array('incomingList' => $incomingList));
    }

    /**
     * 添加
     * @param array $value
     * @throws \Exception
     */
    function add(array $value = array()) {
        $this->autoLoadView('incoming-add', $value);
    }

    /**
     * 添加数据
     * @throws \Exception
     */
    function insert() {
        if(isset($_POST['nation'])) {
            $error = $this->verify($_POST);
            $value = $_POST;
            $receiveID = $this->staff->find(array('name'=>$_POST['receive']));
            $handleID  = $this->staff->find(array('name'=>$_POST['handle']));
            if(!set($handleID)) $error['handleInfo']   = '此人不存在';
            if(!set($receiveID)) $error['receiveInfo'] = '此人不存在';
            if(set($error)) {
                $error = array_merge($error, $_POST);
                $this->add(array('error' => $error));
                exit;
            }
            unset($value['receive']);
            unset($value['handle']);
            $value['receiveID'] = $receiveID[0]['id'];
            $value['handleID']  = $handleID[0]['id'];
            if(isset($_GET['id'])) {
                $value['id'] = $_GET['id'];
                $this->incoming->update($value);
            }else{
                $this->incoming->add($value);
            }
        }
        header('Location: \?mod=incoming');
    }

    /**
     * 删除数据
     * @throws \Exception
     */
    function del() {
        $this->incoming->del($_GET['id']);
        header('Location: \?mod=incoming');
    }

    /**
     * 更新数据
     * @throws \Exception
     */
    public function update() {
        $value = $this->incoming->find(array('id'=>$_GET['id']));
        if(!isset($value[0]) or empty($value)) throw new Exception('The Date What You Find is NOT Exist!');
        $this->add(array('value' => $value[0]));
    }

    /**
     * 数据验证
     * @param array $value
     * @return array
     */
    protected function verify(array $value) {
        $error = array();
        if(!set($value['date']))    $error['dateInfo']    = '必须填写';
        if(!set($value['time']))    $error['timeInfo']    = '必须填写';
        if(!set($value['nation']))  $error['nationInfo']  = '必须填写';
        if(!set($value['case']))    $error['caseInfo']    = '必须填写';
        if(!set($value['receive'])) $error['receiveInfo'] = '必须填写';
        return $error;
    }
}