<?php

namespace sys\lib;
/**
 * Class Module
 * @package sys\lib
 */
class Module
{
	protected $db = null;
	protected $sql = null;

    /**
     * 实例化 sql 和 database，方便子类使用
     * Module constructor.
     * @throws \Exception
     */
	protected function __construct() {
		$this->loadLib('Sql');
		$this->loadLib('Database');
        if(!$this->sql) $this->sql = new Sql(db_name);
		if(!$this->db) $this->db = new Database(
		    db_host,
            db_user,
            db_password,
            db_name,
            db_port);
	}

	/**
	 * 载入内库，模型不能载入视图，这个是很好理解的
	 * @param String $name
     * @throws \Exception
	 */
	protected function loadLib($name) {
		Load::lib($name);
	}

    /**
     * 便于基础查找
     * @param $table
     * @param $data
     * @return array
     * @throws \Exception
     */
    public function find($data, $table) {
        $sql = $this->sql->select($table);
        if(!empty($data) && is_array($data)) {
            foreach ($data as $k => $v) {
                $sql->where($k, "=", $v);
            }
        }
        return $this->db->query($sql);
    }
}