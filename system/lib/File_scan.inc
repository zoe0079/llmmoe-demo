<?php 
/**
 * 本意是增强扫描文件，但最后不仅速度慢，实用性也不强，无用，弃用
 * @author ZhangYin
 * 
 */
class File_scan
{

    var $files = array();

    /**
     * 查找$path目录下的，扩展名为$types的文件
     * @param string $path
     * @param array $types
     * @return 有文件：array，否则：NULL;
     */
    function __construct($path, Array $types=array(), $deep_scan = false){
        $result = array();
        $files = @scandir($path);
        if($files !== false) {
            foreach($files as $key=>$_file){
                if($_file !== '.' && $_file !== '..'){
                    $_filename = iconv("GBK", "UTF-8", $_file);

                    // 递归查找子文件夹
                    if($deep_scan && @scandir($path."/".$_file) !== false){
                        File_scan::File_scan($path."/".$_file, $types);
                    }
                    // 过滤文件
                    if(is_array($types)){
                        $_moviename = explode(".", $_filename);
                        $_filetype = array_pop($_moviename);
                        foreach($types as $_type){
                            if($_filetype == $_type){
                                $name = '';
                                foreach ($_moviename as $_key=>$_name){
                                    if($_key == 0) {
                                        $name = $_name;
                                    }else{
                                        $name = $name.'.'.$_name;
                                    }
                                }
                                $result['name'] = $name;
                                $result['type'] = $_filetype;
                                $result['uri'] = iconv("GBK", "UTF-8", $path).'/'.$_filename;
                                $result['time'] = @filemtime($path.'/'.$_file);
                                array_push($this->files, $result);
                                break;
                            }
                        }
                    }else{
                        $result['name'] = $_filename;
                        $result['uri'] = $path.'/'.$_filename;
                        $result['time'] = @filemtime($path.'/'.$_file);
                        array_push($this->files, $result);
                    }
                }
            }
            return (Array)$result;
        }
        return false;
    }

    /**
     * 按时间排序
     * @return boolean
     */
    public function time_sort(){
        $result = array();
        while ($this->files) {
            reset($this->files);
            $min = each($this->files);
            $min = $min['key'];
            foreach ($this->files as $k => $value) {
                if( $this->files[$min]['time'] < $value['time'] ){
                    $min = $k;
                }
            }
            array_push($result, $this->files[$min]);
            unset($this->files[$min]);
        }
        $this->files = $result;
        return true;
    }
}