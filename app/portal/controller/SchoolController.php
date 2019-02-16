<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: chancle <893296307@qq.com>
// +----------------------------------------------------------------------
namespace app\portal\controller;

use cmf\controller\HomeBaseController;
use app\portal\model\SchoolModel;

class SchoolController extends HomeBaseController
{
    function _initialize() {
		$this->SchoolModel = new SchoolModel();
    }
    
    /**
    * 获取年级列表
    */
    public function get_njlist(){
    	$list = $this->SchoolModel->getNianjis();
		echo return_json($list);
		exit ();
    }

    /**
    * 获取省份
    */
    public function get_region(){
    	$param['region_type'] = $this->request->param("type", 0, "intval");
    	$param['parent_id'] = $this->request->param("pid", 0, "intval");
    	
    	$list = $this->SchoolModel->getregions($param);
		echo return_json($list);
		exit ();
    }

    /**
    * 获取学校列表
    */
    public function get_schoollist(){
    	//省份id
    	$province_id = $this->request->param("province_id", -1, "intval");
    	$param=array();
    	if($province_id>=0){
    		$param['province_id'] = $province_id;
    	}

    	$list = $this->SchoolModel->getSchools($param);
		echo return_json($list);
		exit ();
    }

    /**
    * 获取班级列表
    */
    public function get_classlist(){
    	//年级id
    	$n_id = $this->request->param("n_id", 0, "intval");
    	//学校id  预留字段
    	$s_id = $this->request->param("s_id", 0, "intval");
    	
    	$param=array();
    	if($n_id>0){
    		$param['n_id'] = $n_id;
    	}
    	if($s_id>0){
    		$param['s_id'] = $s_id;
    	}
    	$list = $this->SchoolModel->getBanjis($param);
		echo return_json($list);
		exit ();
    }

}
