<?php
namespace app\ctrl;
/**
 * 主页
 * Class Home
 * @package app\ctrl
 */
class home extends \sys\lib\Control
{
    private $title = '甘溪镇党政办';
    private $breadcrumb = array('首页');

    public function __construct() {
        parent::__construct();
        $this->setHead('head', array('title' => $this->title));
        $this->setFoot('foot');
    }

    /**
     * 主页
     * @throws \Exception
     */
    function homepage() {
        $this->autoLoadView('home', array(
            'title' => '完成情况',
            'breadcrumb' => $this->breadcrumb
        ));
    }

}

