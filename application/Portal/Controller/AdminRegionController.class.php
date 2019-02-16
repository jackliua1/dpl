<?php
// +----------------------------------------------------------------------
// | 
// +----------------------------------------------------------------------
// | 
// +----------------------------------------------------------------------
// | 
// +----------------------------------------------------------------------
namespace Portal\Controller;
use Common\Controller\AdminbaseController;
class AdminRegionController extends AdminbaseController {
	protected $region_obj;
	
	function _initialize() {
		parent::_initialize();
		$this->region_obj = M("Region");
	}
	
	public function index(){
		
		$parent_id = empty($_GET['pid'])?0:$_GET['pid'];
		$parent_name = $this->region_obj->field('region_name,region_type')->where('region_id = '.$parent_id)->find();
		if(empty($parent_name['region_name'])){ $parent_name['region_name'] = '中国';}
		
		$area_type = array(0=>"市级区域",4=>"四级区域",1=>'区/县区域',2=>'乡镇区域');
		
		$region_type = empty($parent_name['region_type'])?0:$parent_name['region_type'];
		
		$region_type_name = $area_type[$region_type];
		
		

		$area_list = $this->area_list($parent_id);
		
		$this->assign("parent_id",$parent_id);
		$this->assign("region_type",($region_type+1));
		$this->assign("region_type_name",$region_type_name);
		$this->assign("parent_name",$parent_name['region_name']);
		$this->assign("area_list",$area_list);
		$this->display();
	}
	
	//新增区域
	function add(){
		
		$parent_id      = intval($_POST['post']['parent_id']);
		$region_name    = trim($_POST['post']['region_name']);
		$region_type    = intval($_POST['post']['region_type']);
		if(empty($region_name)){
			$this->error('请填写地区名称');
		}
		$check_name = $this->region_obj->where('region_name = "'.$region_name.'" and parent_id = '.$parent_id)->find();
		if($check_name){
			$this->error('该地区已经存在。');
		}else{
			
			$result = $this->region_obj->add($_POST['post']);
			if ($result) {
					$this->success("添加成功！");
			} else {
					$this->error("添加失败！");
			}
			
		}	
	}
	
	//删除维修方法
	function delete(){
		
		if(isset($_GET['id'])){
			$id = intval($_GET['id']);
			$this->region_obj->where(" region_id = ".$id)->delete();
			$this->success("数据删除成功！");
			
		}
		
		if(isset($_POST['ids'])){
			$ids=join(",",$_POST['ids']);
			if ($this->region_obj->where("region_id in (".$ids.")")->delete()) {
				$this->success("数据删除成功！");
			} else {
				$this->error("数据删除失败！");
			}
		}
		
	}
	
	//更新地区名称
	function ajax_region_update(){
		
		$result = array('error'=>0,'info'=>'');
		$region_id = intval($_POST['region_id']);
		if(empty($_POST['region_name']) || empty($region_id)){
			
			$result['error'] = 1;
			$result['info'] = '名称不能为空！';
		
		}else{
		
			$check_name = $this->region_obj->where('region_name = "'.$_POST['region_name'].'" and region_id != '.$region_id)->find();
			if($check_name){
				$result['error'] = 1;
				$result['info'] = '该名称已经存在！';
			}else{
				$check = $this->region_obj->where('region_id = '.$region_id)->setField('region_name',$_POST['region_name']);
			}
		}
		$data = json_encode($result);
		echo $data;
	}
	/**
	 * 获取地区列表的函数。
	 *
	 * @access  public
	 * @param   int     $region_id  上级地区id
	 * @return  void
	 */
	function area_list($region_id=0)
	{
		$area_arr = array();
		$row = $this->region_obj->where(" parent_id = ".$region_id)->order('region_id')->select();	
		return $row;
	}
	
}