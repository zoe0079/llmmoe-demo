<?php
/**
 * Class Database
 * @package sys\lib
 */
namespace sys\lib;

class Database
{
	private $mysqli;
	private $_db_name;
	private $_table_name;
	private $sql = null;

	/**
	 * 
	 * 在构造函数中建立连接，最早的版本是采用静态变量的单例，
	 * 但后面在使用的过程中出现很多不确定的因素（是我没发现原因）
     * 使得单例在使用上存在一些困难。
	 *
	 * @param String $db_host
	 * @param String $db_user
	 * @param String $db_password
	 * @param String $db_name
	 * @param Int $db_port
	 * @throws \Exception
	 * @return boolean
	 */
	function __construct(
			String $db_host,
			String $db_user,
			String $db_password,
			String $db_name = null,
			Int    $db_port = 3306
		) {
		$_mysqli = @new \mysqli($db_host, $db_user, $db_password, $db_name, $db_port);
		if($_mysqli->connect_errno) {
			throw new \Exception("无法连接到MySQL数据库：（" . $_mysqli->connect_errno . "）" . $_mysqli->connect_error);
		} else {
			$this->_db_name = $db_name;
			$this->mysqli = $_mysqli;
			Load::lib("Sql");
			$this->sql = new Sql($db_name);
			return true;
		}
	}
	
	function __destruct(){
		$this->_db_name = null;
		$this->_table_name = null;
		if(is_object($this->mysqli)) $this->mysqli->close(); // Warning:  mysqli::close(): Couldn't fetch mysqli
	}

    /**
     * 执行sql语句，如果是查询结果则将结果对象转换为数组
     * @param $sql
     * @return array|bool|\mysqli_result
     * @throws \Exception
     */
	public function query($sql) {
		if( !$result = $this->mysqli->query($sql) ) {
			throw new \Exception("执行SQL失败：（" . $this->mysqli->errno . "）" . $this->mysqli->error);
		}
		if(is_object($result)) {
			$temp = array();
			while($row = mysqli_fetch_array($result, 1)){
                array_push($temp, $row);
			}
			return $temp;
		}else{
			return $result;
		}
	}
	
	/**
	 * 旧的插入，有时候比组织sql方便。
	 * 但是为了代码的统一，不建议使用，早晚要弃用。
	 * @param string $talbe
	 * @param array $value
	 * @throws \Exception
	 * @return boolean
	 */
	public function insert($talbe, array $value) {
		$sql = "INSERT INTO `".$this->_db_name."`.`".$talbe."` SET ";
		foreach($value as $k=>$v) {
			$sql .= "`".$k."` = '".$v."',";
		}
		$sql = chop($sql, ",");
		if( !$this->mysqli->query($sql) ) {
			throw new \Exception("插入数据库失败：（" . $this->mysqli->errno . "）" . $this->mysqli->error);
		}
		return true;
	}

	/**
	 * 获取mysqli，不建议使用，实际上我也没用
	 */
	public function getMysqli() {
		return $this->mysqli;
	}
}