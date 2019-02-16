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

class LiveModel extends Model
{
	
	/**
	* 获取直播教师列表
	* $kcid 课程id 语数外
	*/
	public function liveteacherlist_by_kcid($kcid,$start,$limit){
		$list= Db::name('live_teacher')->where(array('kecheng'=>$kcid))->limit($start,$limit)->select()->toArray();
		foreach($list as $k=>$v){
			$list[$k]['image']=$v['image']?cmf_get_image_preview_url($v['image']):'';
			//标签
            $list[$k]['biaoqian'] = array("幽默","专业");
		}
		$data['list']=$list;
		// $data['studyword']=$studyword;
		// var_dump($info);var_dump($word);die;
		return $data;
	}
	/**
	* 获取直播列表
	* $tid 教师id 
	*/
	public function livelist_bytid($tid,$start,$limit){
		$list0= Db::name('live')->where(array('authorid'=>$tid))->limit($start,$limit)->select();
		// $studyword=array();
		foreach($list0 as $key => $vo){
			
			$arr[$key]=$vo;
			$arr[$key]['time'] = date('Y-m-d H:i:s',$vo['time']);
		}
		$data['list']=$arr;
		// $data['studyword']=$studyword;
		// var_dump($info);var_dump($word);die;
		return $data;
	}
	/**
	* 获取直播详情
	* $id 直播id 
	*/
	public function liveinfo_byid($id){
		// $info= Db::name('live')->where(array('authorid'=>$tid))->limit($start,$limit)->select();
		$info = Db::name('live')
            ->field('b.*,a.name')
            ->alias('b')
            ->join('live_teacher a ','b.authorid=a.id ' )
			->order('b.id desc')
			->where(array('b.id'=>$id))
            ->find();

		$info['time'] = date('Y-m-d H:i:s',$info['time']);
		
		$data['info']=$info;
		// $data['studyword']=$studyword;
		// var_dump($info);var_dump($word);die;
		return $data;
	}
	
	/**
	* 获取直播评论列表
	* $lid 直播id 
	*/
	public function commentlist_bylid($lid,$start,$limit){
		$list0= Db::name('live_comment')->where(array('liveid'=>$lid))->limit($start,$limit)->select();
		// $studyword=array();
		foreach($list0 as $key => $vo){
			
			$arr[$key]=$vo;
			// $arr[$key]['time'] = date('Y-m-d H:i:s',$vo['time']);
		}
		$data['list']=$arr;
		// $data['studyword']=$studyword;
		// var_dump($info);var_dump($word);die;
		return $data;
	}
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