<?php

/**
 * 初始化，只能适用支持文件操作的环境
 * @author ZhangYin
 *
 */
class init extends Control {

    function __construct() {
        $this->setHead("head", array(
                        "title" => "llmmoe - 初始化"
        ));
        $this->setFoot("foot");
    }

    function homepage() {
        $this->autoLoadView("init");
    }

    function write() {
        if(empty($_POST)) {
            header("location:?mod=init");
            exit();
        }
        if(isset($_POST['db_host']) && isset($_POST['db_name']) && isset($_POST['db_user']) && isset($_POST['db_password'])) {
            if($_POST['type'] == "str") {
                $data = array(
                                "db_port" => empty($_POST['db_port']) ? 3306 : $_POST['db_port'],
                                "db_host" => '"' . $_POST['db_host'] . '"',
                                "db_name" => '"' . $_POST['db_name'] . '"',
                                "db_user" => '"' . $_POST['db_user'] . '"',
                                "db_pass" => '"' . $_POST['db_password'] . '"'
                );
                $this->write_local($data);
            } elseif($_POST['type'] == "macro") {
                $data = array(
                                "db_port" => empty($_POST['db_port']) ? 3306 : $_POST['db_port'],
                                "db_host" => $_POST['db_host'],
                                "db_name" => $_POST['db_name'],
                                "db_user" => $_POST['db_user'],
                                "db_pass" => $_POST['db_password']
                );
                $this->write_sae($data);
            }
        }
        header("location:?mod=init&act=result");
    }

    function write_local(array $data) {
        $filename = "./system/config/database_config.php";
        if(file_exists($filename)) unlink($filename);
        $current = '<?php
if(defined("SAE_MYSQL_HOST_M")) {
	define("db_host", SAE_MYSQL_HOST_M);
}else{
	define("db_host", ' . $data['db_host'] . ');
}

if(defined("SAE_MYSQL_PORT")) {
	define("db_port", SAE_MYSQL_PORT);
}else{
	define("db_port", ' . $data['db_port'] . ');
}

if(defined("SAE_MYSQL_DB")) {
	define("db_name", SAE_MYSQL_DB);
}else{
	define("db_name", ' . $data['db_name'] . ');
}

if(defined("SAE_MYSQL_USER")) {
	define("db_user", SAE_MYSQL_USER);
}else{
	define("db_user", ' . $data['db_user'] . ');
}

if(defined("SAE_MYSQL_PASS")) {
	define("db_password", SAE_MYSQL_PASS);
}else{
	define("db_password", ' . $data['db_pass'] . ');
}
?>';
        file_put_contents($filename, $current);
    }

    function result() {
        $this->loadLib("Database");
        if(new Database(db_host, db_user, db_password, db_name, db_port)) {
            echo "数据验证通过...";
        }
    }

}