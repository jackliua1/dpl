<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author:chancle < 893296307@qq.com>
// +----------------------------------------------------------------------
namespace app\portal\model;

use think\Model;
use think\Db;

class SchoolModel extends Model
{
	/**
	* 获取单个学校信息
	* $id 学校id
	*/
    public function getSchool_byId($id){
		$info = Db::name('school')->where(array('id'=>$id))->find();
        return $info;
    }
    /**
	* 获取省市区街道列表
	* Array $param 查询条件
	*/
	public function getregions($param=array()){
		$param['ispass'] = 1;//只查询显示的
        $info= Db::name('region')->where($param)->select();
        return $info;
    }
	/**
	* 获取学校列表
	* Array $param 查询条件
	*/
	public function getSchools($param=array()){
        $info= Db::name('school')->where($param)->select();
        return $info;
    }
    /**
	* 获取班级列表
	* Array $param 查询条件
	*/
	public function getBanjis($param=array()){
        $info= Db::name('banji')->where($param)->select();
        return $info;
    }
	
	/**
	* 获取年级列表
	* Array $param 查询条件
	*/
	public function getNianjis($param=array()){
        $info= Db::name('nianji')->where($param)->select();
        return $info;
    }
	
	
}