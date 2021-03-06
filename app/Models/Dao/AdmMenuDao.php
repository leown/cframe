<?php

namespace App\Models\Dao;

use App\Support\Pager;

class AdmMenuDao extends BaseDao
{
    protected $table = 'adm_menus';

    public function getByUid($uid)
    {
        $sql = "select * ";
        $sql.= "from ".$this->table." ";
        $sql.= "where uid = ? ";
        $row = $this->getExecutor()->query($sql, array($uid));
        if (empty($row)) {
            return null;
        }
        return $row;
    }

    public function getPager($request_param, $sql_condition = array(), $sql_param = array(), $options, $export = false)
    {
        $sql = "select * ";
        $sql.= "from ".$this->table." ";
        if (!empty($sql_condition)) {
            $sql.= 'where '. implode(' and ', $sql_condition);
        }
        if ($options['orderby']) {
            $sql.= " order by ".$options['orderby']." ";
        } else {
            $sql.= " order by id desc ";
        }
        $options['sql']        = $sql;
        $options['sql_param']    = $sql_param;
        $options['request_param'] = $request_param;
        $options['per_page']      = $options['per_page']?$options['per_page']:20;
        $list = Pager::render($options);

        if ($export) {

        } else {

        }

        return $list;
    }

    public function getAll()
    {
        $sql = "select oneclass,groupid ";
        $sql.= "from ".$this->table." group by oneclass order by id desc";
        $data = $this->getExecutor()->querys($sql, array());
        if (empty($data)) {
            return array();
        }
        return $data;

    }

    public function getMenus($param = null)
    {
        $sql = "select * from ".$this->table;
        if (!is_null($param)) {
            if (empty($param)) {
                return array();
            }
            $aidStr = implode(',', $param);
            $sql .= " where aid in(".$aidStr.")";
        }
        $sql .= " order by sort desc";
        $data = $this->getExecutor()->querys($sql, array());
        if (empty($data)) {
            return array();
        }
        return $data;
    }
    public function getOneclass()
    {
        $sql = "select sort ,oneclass from ".$this->table." group by oneclass order by ctime asc";
        $data = $this->getExecutor()->querys($sql, array());
        foreach ($data as $value) {
            $sql = "select * from ".$this->table." where oneclass ='".$value['oneclass']."'";
            $re = $this->getExecutor()->querys($sql, array());
            $res['oneclass'][] = $value['oneclass'];
            $res['nodes'][] = $re;
        }
        return $res;
    }

    public function getByonclass($oneclass)
    {
        $sql = "select * from ".$this->table." where oneclass = '".$oneclass."' order by sort desc";
        $re  = $this->getExecutor()->querys($sql, array());
        return $re;
    }

}