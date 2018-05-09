<?php
/**
 * Created by PhpStorm.
 * User: llmmoe
 * Date: 2018/2/7
 * Time: 19:57
 */

namespace app\ctrl;
/**
 * Class login
 * @package app\ctrl
 */
class login extends \sys\lib\Control {
    private $pageInfo;
    private $login;

    /**
     * login constructor.
     * @throws \Exception
     */
    function __construct() {
        $this->pageInfo = array(
            'title' => '登陆',
        );
        $this->loadModule('Login');
        $this->login = new \app\module\Login();
    }

    /**
     * @throws \Exception
     */
    public function homepage() {
        $this->loadView('login', array(
            'pageInfo' => $this->pageInfo,
        ));
    }

}