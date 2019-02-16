<?php
namespace app\admin\model;

use think\Exception;
use think\Model;
use tree\Tree;
use think\Db;
use think\Request;
class ShuxueModel extends Model
{

    public function plugins()
    {

    }
	
	//获取试卷列表
    public function get_list($uid,$grade,$type = 1){
        $list0= Db::name('souxue')->where(array('grade'=>$grade))->select();
		// var_dump($list0);die;
		$arr = array();
		foreach ($list0 as $k => $v) {
			$arr[$k] = $v;
			$arr[$k]['shiping'] = cmf_get_image_preview_url($v['shiping']);
		} 

        return $arr;
    }
	
	public function get_info($id){
        $info= Db::name('souxue')->where('id',$id)->find();

		$info['shiping'] = cmf_get_image_preview_url($info['shiping']);
        return $info;
    }

}