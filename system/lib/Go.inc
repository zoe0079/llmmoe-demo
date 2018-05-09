<?php
/**
 * Created by PhpStorm.
 * User: llmmoe
 * Date: 2018/4/6
 * Time: 16:39
 */

namespace sys\lib;


class Go {
    function __construct(){
        require_once("system/config/config.php");
        require_once(LIB_DIR . "Load.inc");
    }

    /**
     * @throws \\Exception
     */
    function go() {
        try {
            if(file_exists("system/config/database_config.php")) require_once("system/config/database_config.php");
            Load::lib("Control");
            Load::lib("Module");
            Load::lib('Global');

            // mod参数的路由
            if( !isset($_GET['mod']) ) {
                //默认主页
                if(file_exists(CTRL_DIR."Home.php")) {
                    require_once CTRL_DIR . "home.php";
                    $classname = "\\app\\ctrl\\home";
                    if(class_exists($classname)) {
                        $mod = new $classname();
                        if(is_callable(array($classname, "homepage"))) {$mod->homepage();}
                    }
                } else {
                    throw new \Exception("默认页面不存在！");
                }
            } else {
                // 判断文件是否存在
                if( file_exists(CTRL_DIR.$_GET['mod'].".php") ) {
                    $classname = NAMESPACE_CTRL.$_GET['mod'];
                    include(CTRL_DIR.$_GET['mod'].".php");
                    // 判断类是否存在
                    if(class_exists($classname)){
                        $mod = new $classname();
                        if(isset($_GET['act'])) {
                            // 判断方法是否存在
                            $function_name = $_GET['act'];
                            if(is_callable(array($classname, $function_name))) {
                                $mod->$function_name();
                            } else {
                                throw new \Exception("你所查找的页面不存在！");
                            }
                        } else {
                            // 如果没有明确调用方法，默认调用homepage
                            $mod->homepage();
                        }
                    } else {
                        throw new \Exception("你所查找的页面不存在！");
                    }
                } else {
                    throw new \Exception("The resource you request is not exists! 你所查找的页面不存在！");
                }
            }
        } catch(\Exception $e) {
            if(file_exists(CTRL_DIR."error.php")) {
                include(CTRL_DIR."error.php");
                if(class_exists("error")) {
                    $classname = NAMESPACE_CTRL.'error';
                    $mod = new $classname();
                    if(is_callable(array($classname, "homepage"))) {
                        $mod->homepage($e);
                        exit;
                    }
                }
            } else {
                echo "<h1>".$e->getMessage()."</h1>";
                echo "<pre>".$e."</pre>";
            }
        }
    }
}