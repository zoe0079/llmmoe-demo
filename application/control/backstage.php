<?php

class backstage extends Control {


    private $user;
    private $article;
    private $column;
    private $file;
    private $settings;

    function __construct() {
        session_start();
        $username = null;
        if (isset($_SESSION['username']))
            $username = $_SESSION['username'];

        $this->loadModule("user");
        $this->user = new user();
        $this->loadModule("article");
        $this->article = new article();
        $this->loadModule("column");
        $this->column = new column();
        $this->loadModule("file");
        $this->file = new file();
        $this->loadModule("settings");
        $settings = new settings();
        $this->settings = $settings->get_all_value();

        $this->setHead("backstage_head", array("column" => $this->column->getColumn(), "username" => $username));
        $this->setFoot("backstage_foot");
    }

    /**
     * 后台首页
     */
    function homepage() {
        $this->check_passport();
        if (!empty($_GET['column_id']) && !empty($this->column->findById($_GET['column_id']))) {
            $_column_id = $_GET['column_id'];
        } else {
            $_column_id = null;
        }
        $number_pre_page = empty($this->settings['number_pre_page']) ? 15 : $this->settings['number_pre_page'];
        $total_number = $this->article->get_number_by_column($_column_id);
        $total_page_number = ceil($total_number / $number_pre_page);
        $page = (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
        $start = ($page - 1) * $number_pre_page;
        $this->autoLoadView("backstage", array(
            "article_list" => $this->article->get_article_list($_column_id, $start, $number_pre_page),
            "total_page_number" => $total_page_number,
            "page" => $page,
            "column_list" => $this->column->getColumn(),
            "column_id" => $_column_id,
            "total_number" => $total_number,
        ));
    }

    /**
     * 文章内容管理
     * @throws Exception
     */
    function content() {
        $this->check_passport();
        $content = array("column" => $this->column->getColumn());
        if (isset($_GET['article'])) {
            $content = array_merge($content, $this->article->get_content($_GET['article']));
            if (empty($content))
                throw new Exception("文章不存在");
        }
        $this->autoLoadView("backstage_content", $content);
    }

    /**
     * 添加或者更新
     * @throws Exception
     */
    function update_content() {
        $this->check_passport();
        if (!empty($_POST)) {
            $_POST['auto_format'] = isset($_POST['auto_format']) ? 1 : 0;
            if (empty($_POST['id'])) {
                $this->article->add($_POST);
            } else {
                $this->article->update($_POST);
            }
            header("location:?mod=backstage");
        } else {
            throw new Exception("不要提交空数据！");
        }
    }

    function delete_article() {
        $this->check_passport();
        if (isset($_POST) && is_array($_POST) && !empty($_POST)) {
            $this->article->delete($_POST);
            header("location:?mod=backstage");
        } else {
            header("location:?mod=backstage");
        }
    }

    /**
     * 栏目
     */
    function column() {
        $this->check_passport();
        if (isset($_GET['column']) && !empty($column = $this->column->getInfo($_GET['column']))) {
            $this->autoLoadView("backstage_column", $column);
        } else {
            $this->autoLoadView("backstage_column_list", array("column" => $this->column->getColumn()));
        }
    }

    function add_column() {
        $this->check_passport();
        $this->autoLoadView("backstage_column");
    }

    function update_column() {
        $this->check_passport();
        if (!empty($_POST)) {
            if (empty($_POST['id'])) {
                $this->column->add($_POST);
            } else {
                $this->column->update($_POST);
            }
            header("location:?mod=backstage&act=column");
        } else {
            throw new Exception("不要提交空数据！");
        }
    }

    /**
     * 文件管理
     */
    function file() {
        $this->check_passport();

        $number_pre_page = empty($this->settings['number_pre_page']) ? 15 : $this->settings['number_pre_page'];
        $total_number = $this->file->get_num();
        $total_page_number = ceil($total_number / $number_pre_page);
        $page = (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
        $start = ($page - 1) * $number_pre_page;

        $files = $this->file->get_file_list($start, $number_pre_page);
        $is_upload = isset($_GET['is_upload']) ? isset($_GET['is_upload']) : null;
        $this->autoLoadView("backstage_file", array(
            "is_upload" => $is_upload,
            "files" => $files,
            "page" => $page,
            "total_page_number" => $total_page_number,
            "total_number" => $total_number,
        ));
    }

    function upload_file() {
        $this->check_passport();
        $is_upload = $this->file->upload() ? true : false;
        header("location:?mod=backstage&act=file&is_upload=" . $is_upload);
    }

    function delete_file() {
        $this->check_passport();
        if (isset($_POST) && is_array($_POST) && !empty($_POST)) {
            $this->file->delete($_POST);
            header("location:?mod=backstage&act=file");
        } else {
            header("location:?mod=backstage&act=file");
        }
    }

    /**
     * 基本设置
     */
    function settings() {
        $this->check_passport();
        $this->loadModule("settings");
        $settings = new settings();
        $set = $settings->get_all();
        $this->autoLoadView("backstage_settings", array("settings" => $set));
    }

    function update_settings() {
        $this->check_passport();
        if (!empty($_POST)) {
            $this->loadModule("settings");
            $settings = new settings();
            if ($settings->update($_POST))
                header("location:?mod=backstage&act=settings&status=1");
        }else {
            throw new Exception("不要提交空数据！");
        }
    }

    /**
     * 用户登录
     */
    function login() {
        $this->setHead("head", array("settings" => $this->settings));
        $this->setFoot("foot", array("settings" => $this->settings));
        $this->autoLoadView("login");
    }

    function logout() {
        unset($_SESSION);
        session_destroy();
        header("location:?mod=backstage");
    }

    /**
     * 验证用户身份
     * 如果session里有用户的记录就通过，
     * 否则重新登陆
     */
    function check_passport() {
        if (isset($_SESSION['username'])) {
            $user_info = $this->user->get_user_info($_SESSION['username']);
            $this->addHeadValue($user_info);
        } else {
            header("location:?mod=backstage&act=login");
            exit();
        }
    }

    /**
     * 这是门卫，用来检查登陆后台的用户身份
     */
    function guard() {
        if (empty($_POST)) {
            header("location:?mod=backstage&act=login");
            exit();
        }
        if (!isset($_SESSION['username'])) {
            if (isset($_POST['username']) && isset($_POST['password']) &&
                    $this->user->can_i_pass($_POST['username'], $_POST['password'])) {
                $this->user->record_login_info($_POST['username']);
                $_SESSION['username'] = $_POST['username'];
                $_SESSION['last_access'] = time();
                header("location:?mod=backstage");
            } else {
                header("location:?mod=backstage&act=login&login=fail");
            }
        } else {
            header("location:?mod=backstage");
        }
    }

}