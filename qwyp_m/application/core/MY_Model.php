<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->db = init_db('master');  //默认为主库
    }

    public function get_one($sql) {
        $db_r = init_db('slave');
        $row  = $db_r->query($sql)->row_array();
        return is_array($row) ? array_shift($row) : '';
    }

    public function get_row($sql) {
        $db_r = init_db('slave');
        return $db_r->query($sql)->row_array();
    }

    public function get_all($sql) {
        $db_r = init_db('slave');
        return $db_r->query($sql)->result_array();
    }

    public function find_rows() {
        $db_r = init_db('slave');
        $row  = $db_r->select('found_rows() as nums')->get()->row_array();
        return $row['nums'];
    }

    //获取某表的一行记录
    public function row_info($table, $id_or_where, $column = "*") {
        if (empty($id_or_where)) {
            return '';
        }
        if (is_numeric($id_or_where)) {
            $id_or_where = array('id' => $id_or_where);
        }
        $db_r = init_db('slave');
        return $db_r->select($column)->from($table)->where($id_or_where)->limit(1)->get()->row_array();
    }

    //获取某表一行记录某字段值
    public function column_val($table, $id_or_where, $column) {
        $info = $this->row_info($table, $id_or_where, $column);
        return !empty($info) ? $info[$column] : '';
    }

}
