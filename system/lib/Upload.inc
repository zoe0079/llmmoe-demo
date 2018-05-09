<?php

/**
 * 上传
 * @User llmmoe
 */
class Upload
{
    private $dir = "";
    private $allowType = array();
    private $notAllowType = array();

    /**
     * 
     * @param string $dir 默认上传目录
     */
    public function __construct($dir = "upload/") {
        $this->dir = $dir;
    }
    
    public function setAllowType($typeArray) {
        $this->allowType = $typeArray;
    }
    
    public function setNotAllowType($typeArray) {
        $this->notAllowType = $typeArray;
    }
    
    /**
     * 保存
     * @param string $dir 临时保存位置，不改变类变量
     * @throws Exception
     * @return string[]
     */
    public function save($dir = "") {
        if(!$this->verifyFileType($_FILES['file']['type'])) {
            throw new Exception("文件类型错误");
        }
        if($_FILES["file"]["error"] > 0) {
            throw new Exception("错误代码: " . $_FILES["file"]["error"]);
        }
        if(empty($dir)) $dir = $this->dir;
        $name = $this->getFileName($_FILES["file"]["name"]);
        $size = round(($_FILES["file"]["size"] / 1024 / 1024), 3);
        $type = $name['type'];
        $name = $name['name'];
        
        if(file_exists($dir . $_FILES["file"]["name"])) {
            throw new Exception($_FILES["file"]["name"] . " 已存在 ");
        } else {
            $name = $name . "_" . time();
            move_uploaded_file($_FILES["file"]["tmp_name"], $dir.$name.".".$type);
        }
        if(file_exists($dir.$name.".".$type)) {
            return array("dir"=>$dir, "type"=>$type, "name"=>$name, "size"=>$size);
        } else {
            throw new Exception("文件保存失败");
        }
    }
    
    /**
     * 验证文件类型
     * @param string $fileType
     * @return boolean
     */
    public function verifyFileType($fileType) {
        if(!empty($this->allowType)) {
            foreach($this->allowType as $allowType) {
                if($fileType == $allowType) return true;
            }
        } elseif(!empty($this->notAllowType)) {
            foreach($this->notAllowType as $notAllowType) {
                if($fileType == $notAllowType) return false;
            }
        } else {
            return true;
        }
    }
    
    /**
     * 拆分文件名 和 文件类型
     * @param string $file
     * @return mixed[]|string[]|string[]
     */
    private function getFileName($file) {
        $str = explode(".", $file);
        $type = array_pop($str);
        $name = "";
        $count = count($str);
        foreach($str as $k => $s) {
            $name .= $s;
            if(($k+1) < $count) $name .= ".";
        }
        return array("name"=>$name, "type"=>$type);
    }

}