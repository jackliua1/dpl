<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class SchoolController extends AdminbaseController{
	protected $ceping_model;
	
	function _initialize() {
		parent::_initialize();
		$this->ceping_model = D("Common/Ceping");
		$this->ceping_sc_model = D("Common/School");
	}
	
	function index(){
		$ads=$this->ceping_sc_model->select();
		$this->assign("ads",$ads);
		$this->display();
	}
	
	function add(){
		$this->display();
	}
	
	function add_post(){
		if(IS_POST){
			if ($this->ceping_sc_model->create()){
				if ($this->ceping_sc_model->add()!==false) {
					$this->success(L('ADD_SUCCESS'), U("school/index"));
				} else {
					$this->error(L('ADD_FAILED'));
				}
			} else {
				$this->error($this->ceping_sc_model->getError());
			}
		}
	}
	
	function edit(){
		$id=I("get.id");
		$ad=$this->ceping_sc_model->where("id=$id")->find();
		$this->assign($ad);
		$this->display();
	}
	
	function edit_post(){
		if (IS_POST) {
			if ($this->ceping_sc_model->create()) {
				if ($this->ceping_sc_model->save()!==false) {
					$this->success("保存成功！", U("school/index"));
				} else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->ceping_sc_model->getError());
			}
		}
	}
	
	function delete(){
		$id = I("get.id",0,"intval");
		if ($this->ceping_sc_model->delete($id)!==false) {
			$this->success("删除成功！");
		} else {
			$this->error("删除失败！");
		}
	}
	


	
	
	
	
	
}