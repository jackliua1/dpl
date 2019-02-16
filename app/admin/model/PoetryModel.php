<?php
namespace app\admin\model;

use think\Exception;
use think\Model;
use tree\Tree;
use think\Db;
use think\Request;
class PoetryModel extends Model
{

    public function plugins()
    {

    }
	//获取试卷列表
    public function get_class_list(){
        $list0= Db::name('poetry_class')->select();
		// var_dump($list0);die;
		$arr = array();
		foreach ($list0 as $k => $v) {
			$arr[$k] = $v;
			$arr[$k]['image'] = cmf_get_image_preview_url($v['image']);
		} 
        return $arr;
    }
	
	//获取热门古诗列表
    public function get_hot_list($limit = 5){
        // $list0= Db::name('poetry')->limit($limit)->order('nums desc')->select();
		
		$list0 = Db::name('poetry')
               ->field('b.*,a.dynasty')
               ->alias('b')
               ->join('poetry_author a ','b.authorid=a.id ' )
			   ->limit($limit)
			   ->order('nums desc')
               ->select();
		// var_dump($list0);die;
		$arr = array();
		foreach ($list0 as $k => $v) {
			// $arr[$k] = $v;
			$arr[$k]['id'] = $v['id'];
			$arr[$k]['title'] = $v['title'];
			$arr[$k]['author'] = $v['author'];
			$arr[$k]['dynasty'] = $v['dynasty'];
			$arr[$k]['content'] = $v['content'];
			$content = explode('。', $v['content']) ;
			$arr[$k]['firstcon'] = $content[0].'。' ;
			// $arr[$k]['shiping'] = cmf_get_image_preview_url($v['shiping']);
		} 

        return $arr;
    }
	
	//获取试卷列表
    public function get_list_by_classid($classid,$start,$limit){
        // $list0= Db::name('poetry')->where(array('classid'=>$classid))->select();
		$list0 = Db::name('poetry')
               ->field('b.*,a.dynasty')
               ->alias('b')
               ->join('poetry_author a ','b.authorid=a.id ' )
			   ->where(array('b.classid'=>$classid))
			   ->order('nums desc')
			   ->limit($start,$limit)
               ->select();
		// var_dump($list0);die;
		$arr = array();
		foreach ($list0 as $k => $v) {
			$arr[$k]['id'] = $v['id'];
			$arr[$k]['title'] = $v['title'];
			$arr[$k]['author'] = $v['author'];
			$arr[$k]['dynasty'] = $v['dynasty'];
			$arr[$k]['content'] = $v['content'];
			$content = explode('。', $v['content']) ;
			$arr[$k]['firstcon'] = $content[0].'。' ;
		} 
        return $arr;
    }
	//获取试卷列表
    public function get_classinfo($id){
		$info= Db::name('poetry_class')->where(array('id'=>$id))->find();
		$info['image'] = cmf_get_image_preview_url($info['image']);
        // $list0= Db::name('poetry')->where(array('classid'=>$classid))->select();
		
        return $info;
    }
	public function get_info($id){
		$info = Db::name('poetry')
               ->field('b.*,a.dynasty,a.content as author_content')
               ->alias('b')
               ->join('poetry_author a ','b.authorid=a.id ' )
			   ->where(array('b.id'=>$id))
               ->find();
        // $info= Db::name('poetry')->where('id',$id)->find();
		$info['url'] = cmf_get_image_preview_url($info['url']);
        return $info;
    }
	
	public function poetry_search($name,$start,$limit){
		$where1['b.title|a.name'] = array('like','%'.$name.'%');
		$info = Db::name('poetry')
               ->field('b.*,a.dynasty,a.content as author_content,a.name as author_name')
               ->alias('b')
               ->join('poetry_author a ','b.authorid=a.id ' )
			   ->where($where1)
			   ->limit($start,$limit)
               ->select();

        $arr = array();
		foreach ($info as $k => $v) {
			$arr[$k]['id'] = $v['id'];
			$arr[$k]['title'] = $v['title'];
			$arr[$k]['author'] = $v['author'];
			$arr[$k]['dynasty'] = $v['dynasty'];
			$arr[$k]['content'] = $v['content'];
			$content = explode('。', $v['content']) ;
			$arr[$k]['firstcon'] = $content[0].'。' ;
		} 
		
		$data['list1'] = $arr;
		// $data['list2'] = $arr2;
        return $data;
    }
	
	

}