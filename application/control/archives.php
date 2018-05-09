<?php

class archives extends Control {
    private $file;

    function __construct() {
        parent::__construct();
        
        $this->setHead("header");
        $this->setFoot("footer");
        
        $this->loadModule("file");
        $this->file = new file();
    }
    
    function homepage() {
        $this->autoLoadView('archivesList');
    }
    
    function upload() {
        $id = $this->file->save();
        if(!empty($id)) {
            echo $id;
        }
    }

}