<?php
/**
 * Created by PhpStorm.
 * User: llmmoe
 * Date: 2018/4/1
 * Time: 10:21
 */

namespace app\ctrl;


class error extends \sys\lib\Control {
    /**
     * error constructor.
     */
    function __construct() {
        $this->setHead('head');
        $this->setFoot('foot');
    }

    /**
     * @param \Exception $e
     * @throws \Exception
     */
    public function homepage($e){
        $this->loadView('head');
        echo '<h1 class="text-lg-center">'.$e->getMessage().'</h1>';
        echo '<h3 class="text-lg-center"><pre>'.$e.'</pre></h3>';
        $this->loadView('foot');
    }
}