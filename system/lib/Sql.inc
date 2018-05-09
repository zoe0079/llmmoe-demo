<?php
/**
 * 这个类用于组织 sql 语句
 */
namespace sys\lib;
/**
 * 用于组织 sql 语句
 * Class Sql
 * @package sys\lib
 */
class Sql
{
	private $sql = "";
	private $db_name = "";

    /**
     * Sql constructor.
     * @param $db_name
     */
	function __construct($db_name) {
		$this->db_name = $db_name;
	}
	
	public function select($table, array $value=array('*')) {
		$this->sql = "SELECT ";
		if(is_string(array_rand($value))){
			foreach($value as $k=>$v) {
				$this->sql .= "`".$v."` as `".$k."`, ";
			}
		}else{
			foreach($value as $v) {
				$this->sql .= "`".$v."`, ";
			}
		}
		$this->sql = chop($this->sql, ", ") . " ";
		$this->sql .= "FROM `".$this->db_name."`.`" . $table . "` ";
		return $this;
	}
	
	public function insert($talbe, array $value){
		$this->sql = "INSERT INTO `".$this->db_name."`.`".$talbe."` SET ";
		foreach($value as $k=>$v) {
			$this->sql .= "`".$k."` = '".$v."',";
		}
		$this->sql = chop($this->sql, ",");
		return $this;
	}
	
	public function delete($table) {
		$this->sql = "DELETE FROM `".$this->db_name."`.`".$table."`";
		return $this;
	}
	
	public function update($talbe, array $value) {
		$this->sql = "UPDATE `".$this->db_name."`.`".$talbe."` SET ";
		foreach($value as $k=>$v) {
			$this->sql .= "`".$k."` = '".$v."',";
		}
		$this->sql = chop($this->sql, ",");
		return $this;
	}
	
	public function where($data, $condition, $value) {
		if(stristr($this->sql, "WHERE")) {
			$this->sql .= " AND ";
		}else{
			$this->sql .= " WHERE ";
		}
		$this->sql .= '`'.$data.'`'.$condition."'".$value."'";
		return $this;
	}
	
	public function or_where($data, $condition, $value) {
		if(stristr($this->sql, "WHERE")) {
			$this->sql .= " OR `";
		}else{
			$this->sql .= " WHERE `";
		}
		$this->sql .= $data."`".$condition."'".$value."'";
		return $this;
	}
	
	public function orderby($value, $condition = "asc") {
		if(stristr($this->sql, "ORDER BY")) {
			$this->sql .= ", ";
		}else{
			$this->sql .= " ORDER BY ";
		}
		$this->sql .= $value." ".$condition;
		return $this;
	}
	
	public function limit($start, $num) {
		$this->sql .= " LIMIT ".$start.",".$num;
		return $this;
	}
	
	public function __toString() {
		return $this->sql;
	}
}