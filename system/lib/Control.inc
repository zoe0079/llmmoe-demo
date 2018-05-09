<?php
namespace sys\lib;
/**
 * Class Control
 * @package sys\lib
 */
class Control
{
    private $head         = null;
    private $foot         = null;
    private $value        = array();
    private $headValue    = array();
    private $footValue    = array();

    /**
     * Control constructor.
     */
    function __construct(){
    }

    /**
     * 载入内库，只写文件名，不写文件类型，因为只能载入.inc文件
     * 其实设置这个方法的时候，很纠结。
     * 控制器到底能不能加载内库，调用内库组织数据应该是模型的事。
     * 但是内库也不止是用来组织数据的，这就有些混乱。这就会使得
     * 控制器也能加载数据库类，也能执行数据库查询，这真让人头大。
     * 2018-02-07 到时候与其他语言配合就会出现很大的问题，使得
     * 模块无法分离出来。
     * 在想出更好办法时，只能期待使用者的规范了。
     * @param String|null $name
     * @throws \Exception
     */
    protected function loadLib(String $name = '') {
        Load::lib($name);
    }

    /**
     * 载入模型，返回模型实例〕
     * 也就是说，一个模型文件至少应该有一个类名和文件名相同的类
     * @param String $name
     * @return mixed
     * @throws \Exception
     */
    protected function loadModule(String $name = '') {
        Load::module($name);
//        $classname = NAMESPACE_MODULE . $name;
//        return new $classname();
    }

    /**
     * 载入视图。
     * $value 是一个数组，用于控制器给视图传值。
     * 在视图中使用时，键值是变量名，值就是变量的值。
     * 如：array("demo_name"=>"value")，在视图中调用时等于：$demo_name="value"。
     * 应该讲清楚了吧。
     * @param String $name
     * @param array|null $value
     * @throws \Exception
     */
    protected function loadView(String $name = '', array $value = array()) {
        Load::view($name, $value);
    }

    /**
     * 载入切分为头、身、脚的三段式视图。
     * 需要先设置头和脚，载入的时候只需填写身子的视图，
     * 头和脚会一起载入
     * @param String $name
     * @param array|null $value
     * @throws \Exception
     */
    protected function autoLoadView(String $name, array $value = array()) {
        $this->loadView($this->head, $this->headValue);
        $this->loadView($name, array_merge($this->value, $value));
        $this->loadView($this->foot, $this->footValue);
        $this->clearValue();
    }

    /**
     * 添加传入视图的值
     * @param array $value
     */
    protected function setValue(array $value) {
        if(!empty($value))
            $this->value = array_merge($this->value, $value);
    }

    /**
     * 清空传值
     */
    protected function clearValue() {
        $this->headValue    = null;
        $this->footValue    = null;
        $this->value        = null;
    }

    /**
     * 设置头，和加载视图类似。
     * 只是在调用时不会马上加载视图，需要在使用 autoLoadView 的时候才加载
     * @param String $name
     * @param array|null $value
     */
    protected function setHead(String $name, array $value = array()) {
        $this->head = $name;
        if(!empty($value)) $this->headValue = $value;
    }

    /**
     * 增加传入头视图的值。
     * 需求：因为设置头视图通常只在控制器的构造函数中使用，
     * 在具体的方法内，需要向头传入值的时候，建议使用此方法
     * @param array|null $value
     */
    protected function addHeadValue(array $value = array()) {
        if(!empty($value))
            $this->headValue = array_merge($this->headValue, $value);
    }

    /**
     * 设置脚视图
     * @param String $name
     * @param array|null $value
     */
    protected function setFoot(String $name, array $value = null) {
        $this->foot = $name;
        if(!empty($value)) $this->footValue = $value;
    }

}