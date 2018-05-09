<?php

namespace sys\lib;
/**
 * Class Load
 * @package sys\lib
 */
class Load
{
	function __construct(){
	}

    /**
     * 载入库
     * @param string $_lib_name
     * @return mixed
     * @throws \Exception
     */
	public static function lib($_lib_name = '') {
		$path = LIB_DIR . $_lib_name;
		if(empty($_lib_name) && !file_exists($path . '.inc'))
            throw new \Exception('Load Library Error: file not exists...');
		return include_once($path . '.inc');
	}

    /**
     * 载入模型
     * @param string $_mod_name
     * @throws \Exception
     */
	public static function module($_mod_name = ''){
	    if(empty($_mod_name) && !file_exists(MODULE_DIR . $_mod_name . '.module'))
	        throw new \Exception('Load Module Error: file not exists...');
		include_once(MODULE_DIR . $_mod_name . '.module');
	}

    /**
     * 载入视图
     * @param string $_view_name 文件名
     * @param string $_view_value 传入视图的值
     * @throws \Exception
     */
	public static function view($_view_name = '', $_view_value = '') {
        if(is_array($_view_value) && !empty($_view_value)) {
            foreach($_view_value as $k => $v){
                $$k = $v;
            }
        }
        if(empty($_mod_name) && !file_exists(VIEW_DIR . $_view_name . '.phtml'))
            throw new \Exception('Load View Error: file not exists...');
        include(VIEW_DIR . $_view_name . '.phtml');
	}
}