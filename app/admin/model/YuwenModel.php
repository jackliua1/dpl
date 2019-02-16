<?php
namespace app\admin\model;

use think\Exception;
use think\Model;
use tree\Tree;
use think\Db;
use think\Request;
class YuwenModel extends Model
{

    public function plugins()
    {

    }
	
	//获取试卷列表
    public function get_list($uid,$grade,$type = 1){
        $list0= Db::name('yuwenti')->where(array('grade'=>$grade))->select();
		// var_dump($list0);die;
		$arr = array();
		foreach ($list0 as $k => $v) {
			$arr[$k] = $v;
			$arr[$k]['shiping'] = cmf_get_image_preview_url($v['shiping']);
		} 

        return $arr;
    }
	
	public function get_info($id){
        $info= Db::name('yuwenti')->where('id',$id)->find();

		$info['shiping'] = cmf_get_image_preview_url($info['shiping']);
        return $info;
    }
	
	//语文写作列表
	public function write_list($nid){
		$list= Db::name('writing')->where(array('G_ararry'=>$nid))->order('time desc')->select();
		// var_dump($nid);var_dump($list);die;
		return $list;
	}
	//语文写作详情
	public function write_info($id){
		$info= Db::name('writing')->where(array('id'=>$id))->find();
		return $info;
	}
	//语文查询写作记录
	public function write_jilu_find($sid,$uid){
		$info= Db::name('writingjilu')->where(array('sid'=>$sid,'uid'=>$uid))->find();
		return $info;
	}
	//语文查询写作记录
	public function my_write_info($id){
		$info= Db::name('writingjilu')->where(array('id'=>$id))->find();
		$info['image'] = cmf_get_image_url(substr_replace($info['image'],"",0,1));
		return $info;
	}
	//语文个人写作记录
	public function write_jilu_select($uid,$start,$limit){
		$list0= Db::name('yw_writingjilu')->where(array('uid'=>$uid))->order('time desc')->limit($start,$limit)->select();
		$list=array();
		foreach($list0 as $key => $vo){
			$list[] = $vo;
			$imagegai =[];
			if($vo['image']){
				$image = json_decode($vo['image']);
				foreach($image as $k => $v){
					$imagegai[] = cmf_get_image_url($v);
				}
			}else{
				$imagegai = '';
			}
			$list[$key]['image'] =$imagegai;
			$list[$key]['time'] = date('Y-m-d',$vo['time']);
		}
		return $list;
	}
	//语文添加写作记录
	public function write_jilu_add($uid,$url){
		// $info= $this->write_info($sid);
		$data = array('uid'=> $uid,'image'=> $url,'time'=> time());
		$url = Db::name('yw_writingjilu')->insertGetId($data);
        return $url;
	}
	
	//多图片存入文件夹
	public function imgfile($file,$key,$wenjian){
		$imgtype = substr ( strrchr ( $file['file']['name'], '.' ), 1 );

		$name = $key.rand( 6, 0 ) . time().'.' . $imgtype;
		
		$date = date('Ymd',time()).'/';
		$dir = iconv("UTF-8", "GBK", './upload/'.$wenjian.'/'.$date);	//生成文件夹
        if (!file_exists($dir)){
            mkdir ($dir,0777,true);
        } 
		$ok =move_uploaded_file($file['file']['tmp_name'], './upload/'.$wenjian.'/'.$date.$name );
		if($ok){
			$url ='/upload/'.$wenjian.'/'.$date.$name;
			return $url;
		}else{
			return 0;
		}
    }
	
	//获取美文类型列表
    public function get_topic_class_list(){
        $list0= Db::name('topic_class')->select();
		// var_dump($list0);die;
		$arr = array();
		foreach ($list0 as $k => $v) {
			$arr[$k] = $v;
			$arr[$k]['image'] = cmf_get_image_preview_url($v['image']);
		} 
        return $arr;
    }
	
	//更具类型获取美文列表
    public function get_topiclist_byclass($classid = 1,$start,$limit){
        // $list0= Db::name('topic')->where(array('classid'=>$classid))->limit($start,$limit)->select();
		// var_dump($list0);die;
		$list0 = Db::name('topic')
               ->field('b.*,a.name as classname')
               ->alias('b')
               ->join('topic_class a ','b.classid=a.id ' )
			   ->limit($start,$limit)
			   ->where(array('b.classid'=>$classid))
			   ->order('b.nums desc')
               ->select();
		// var_dump($list0);die;
		$arr = array();
		foreach ($list0 as $k => $v) {
			$arr[$k] = $v;
			$arr[$k]['image'] = cmf_get_image_preview_url($v['image']);
			$arr[$k]['con'] = mb_substr(strip_tags ($v['con']),0,20,'utf-8');
		} 
		// var_dump($arr);die;
		return $arr;
    }
	
	//美文详情
	public function get_topic_info($id){
        $info= Db::name('topic')->where('id',$id)->find();
		$info['image'] = cmf_get_image_preview_url($info['image']);
		// $info['content'] = cmf_replace_content_file_url($info['content']);
		// $info['content'] = cmf_replace_content_file_url(htmlspecialchars_decode($info['content']));
        return $info;
    }
	//美文详情
	public function topic_num_add($id){
        $info= Db::name('topic')->where('id',$id)->setInc('nums', 1);
        return $info;
    }
	
	//获取阅读理解列表
    public function get_reading_list($start,$limit){
        $list0= Db::name('yw_reading')->limit($start,$limit)->select();

		// var_dump($list0);die;
		$arr = array();
		foreach ($list0 as $k => $v) {
			$arr[$k] = $v;
			$arr[$k]['url'] = cmf_get_image_preview_url($v['url']);
			// $arr[$k]['url'] = 'http://www.ti.com.cn/cn/lit/ug/tidub07/tidub07.pdf';
		} 
        return $arr;
    }
	
	//美文详情
	public function get_reading_info($id){
        $info= Db::name('yw_reading')->where('id',$id)->find();
		$info['url'] = cmf_get_image_preview_url($info['url']);
		// $info['url'] = 'http://www.ti.com.cn/cn/lit/ug/tidub07/tidub07.pdf';
        return $info;
    }
	

}