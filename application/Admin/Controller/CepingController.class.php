<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class CepingController extends AdminbaseController{
	protected $ceping_model;
	
	function _initialize() {
		parent::_initialize();
		$this->ceping_model = D("Common/Ceping");
		$this->ceping_kc_model = D("Common/Ceping_kc");
		$this->ceping_tk_model = D("Common/Ceping_tk");
		$this->ceping_tm_model = D("Common/Ceping_tm");
		$this->ceping_sc_model = D("Common/School");
	}
	
	function index(){
		$ads=$this->ceping_model->select();
		$this->assign("ads",$ads);
		$this->display();
	}
	
	function add(){
		$this->display();
	}
	
	function add_post(){
		if(IS_POST){
//		    print_r(IS_POST);die;
			if ($this->ceping_model->create()){
				if ($this->ceping_model->add()!==false) {
					$this->success(L('ADD_SUCCESS'), U("ceping/index"));
				} else {
					$this->error(L('ADD_FAILED'));
				}
			} else {
				$this->error($this->ceping_model->getError());
			}
		
		}
	}
	
	function edit(){
		$id=I("get.id");
		$ad=$this->ceping_model->where("cp_id=$id")->find();
		$this->assign($ad);
		$this->display();
	}
	
	function edit_post(){
		if (IS_POST) {
			if ($this->ceping_model->create()) {
				if ($this->ceping_model->save()!==false) {
					$this->success("保存成功！", U("ceping/index"));
				} else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->ceping_model->getError());
			}
		}
	}
	
	/**
	 *  删除
	 */
	function delete(){
		$id = I("get.id",0,"intval");
		if ($this->ceping_model->delete($id)!==false) {
			$this->success("删除成功！");
		} else {
			$this->error("删除失败！");
		}
	}
	//课程管理
	function kc_index(){
		$ads=$this->ceping_kc_model->select();
		$this->assign("ads",$ads);
		$this->display();
	}
	
	function kc_add(){
		$this->display();
	}
	
	function kc_add_post(){
		if(IS_POST){
//			 var_dump($_POST);die;
			// if ($this->ceping_kc_model->create()){
				if ($this->ceping_kc_model->add($_POST)!==false) {
					$this->success(L('ADD_SUCCESS'), U("ceping/kc_index"));
				} else {
					$this->error(L('ADD_FAILED'));
				}
			// } else {
				// $this->error($this->ceping_kc_model->getError());
			// }
		
		}
	}
	
	function kc_edit(){
		$id=I("get.id");
		$ad=$this->ceping_kc_model->where("kc_id=$id")->find();
		// var_dump($ad);die;
		$this->assign($ad);
		$this->display();
	}
	
	function kc_edit_post(){
		if (IS_POST) {
			if ($this->ceping_kc_model->create()) {
				if ($this->ceping_kc_model->save()!==false) {
					$this->success("保存成功！", U("ceping/kc_index"));
				} else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->ceping_kc_model->getError());
			}
		}
	}
	
	/**
	 *  删除
	 */
	function kc_delete(){
		$id = I("get.id",0,"intval");
		if ($this->ceping_kc_model->delete($id)!==false) {
			$this->success("删除成功！");
		} else {
			$this->error("删除失败！");
		}
	}
	
	//题库管理
	function tk_index(){
		$ads=$this->ceping_tk_model->select();
		$this->assign("ads",$ads);
		$this->display();
	}
	
	function tk_add(){
		$this->display();
	}
	
	function tk_add_post(){
		if(IS_POST){
			if ($this->ceping_tk_model->add($_POST)!==false) {
				$this->success(L('ADD_SUCCESS'), U("ceping/tk_index"));
			} else {
				$this->error(L('ADD_FAILED'));
			}
		}
	}
	
	function tk_edit(){
		$id=I("get.id");
		$ad=$this->ceping_tk_model->where("tk_id=$id")->find();
		$this->assign($ad);
		$this->display();
	}
	
	function tk_edit_post(){
		if (IS_POST) {
			if ($this->ceping_tk_model->create()) {
				if ($this->ceping_tk_model->save()!==false) {
					$this->success("保存成功！", U("ceping/tk_index"));
				} else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->ceping_tk_model->getError());
			}
		}
	}
	
	/**
	 *  删除
	 */
	function tk_delete(){
		$id = I("get.id",0,"intval");
		if ($this->ceping_tk_model->delete($id)!==false) {
			$this->success("删除成功！");
		} else {
			$this->error("删除失败！");
		}
	}
	
	//题目管理
	function tm_index(){
		$ads=$this->ceping_tm_model->where(array('tk_id'=>$_GET['tk_id']))->select();
		// var_dump($_GET);var_dump($ads);die;
		$this->assign("ads",$ads);
		$this->display();
	}
	
	function tm_add(){
		$this->display();
	}
	
	function tm_add_post(){
		if(IS_POST){
			if ($this->ceping_tm_model->add($_POST)!==false) {
				$this->success(L('ADD_SUCCESS'), U("ceping/tm_index",array('tk_id'=>$_GET['tk_id'])));
			} else {
				$this->error(L('ADD_FAILED'));
			}
		}
	}
	
	function tm_edit(){
		$id=I("get.id");
		$ad=$this->ceping_tm_model->where("tm_id=$id")->find();
		$this->assign($ad);
		$this->display();
	}
	
	function tm_edit_post(){
		// var_dump($_GET);die;
		if (IS_POST) {
			if ($this->ceping_tm_model->create()) {
				if ($this->ceping_tm_model->save()!==false) {
					$this->success("保存成功！", U("ceping/tm_index",array('tk_id'=>$_GET['tk_id'])));
				} else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->ceping_tm_model->getError());
			}
		}
	}
	
	/**
	 *  删除
	 */
	function tm_delete(){
		$id = I("get.id",0,"intval");
		if ($this->ceping_tm_model->delete($id)!==false) {
			$this->success("删除成功！");
		} else {
			$this->error("删除失败！");
		}
	}
	
	
	//学校管理
	function sc_index(){
		$ads=$this->ceping_sc_model->select();
		$this->assign("ads",$ads);
		$this->display();
	}
	
	function sc_add(){
		$this->display();
	}
	
	function sc_add_post(){
		if(IS_POST){
			if ($this->ceping_sc_model->add($_POST)!==false) {
				$this->success(L('ADD_SUCCESS'), U("ceping/sc_index"));
			} else {
				$this->error(L('ADD_FAILED'));
			}
		}
	}
	
	function sc_edit(){
		$id=I("get.id");
		$ad=$this->ceping_sc_model->where("id=$id")->find();
		$this->assign($ad);
		$this->display();
	}
	
	function sc_edit_post(){
		if (IS_POST) {
			if ($this->ceping_sc_model->create()) {
				if ($this->ceping_sc_model->save()!==false) {
					$this->success("保存成功！", U("ceping/sc_index"));
				} else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->ceping_sc_model->getError());
			}
		}
	}
	function sc_delete(){
		$id = I("get.id",0,"intval");
		if ($this->ceping_sc_model->delete($id)!==false) {
			$this->success("删除成功！");
		} else {
			$this->error("删除失败！");
		}
	}


	
	
	
	
	
}