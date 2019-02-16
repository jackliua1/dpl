<?php
namespace Admin\Controller;

namespace app\admin\controller;

use app\admin\model\BookModel;
use cmf\controller\AdminBaseController;
use mindplay\annotations\standard\VarAnnotation;
use think\Db;

class LiveController extends AdminBaseController{


    //题型列表
    //直播列表
    public function live_index(){
		$where = array();
		 if( $this->request->post()) {
			 $title = $this->request->post('title');
			 $where['b.title'] = array('like','%'.$title.'%');
		 }
		
		
        $rolesSrc = Db::name('live')
            ->field('b.*,a.name')
            ->alias('b')
            ->join('live_teacher a ','b.authorid=a.id ' )
			->order('b.id desc')
			->where($where)
            ->paginate(20);
		// var_dump($rolesSrc);die;
        $page = $rolesSrc->render();
        $this->assign('page',$page);
        $this->assign('result',$rolesSrc);
        return $this->fetch();
    }
	//添加直播
    public function live_add(){
		$teacher=Db::name('live_teacher')->select();
		$this->assign('teacherlist',$teacher);
		// $this->assign('data',$data);
        if( $this->request->post()) {
            $data = $this->request->param();
			$data['time'] = time();
// var_dump($data);die;
            Db::name('live')->insert($data);
            $this->success("添加成功", url("live/live_index"));
        }
        return $this->fetch();
    }
    //编辑直播
    public function live_edit(){
        // $data=Db::name('live')->select();
        // $this->assign('data',$data);
        $id = $this->request->param('id', 0, 'intval');
		$data=Db::name('live')->where('id',$id )->find();
		$this->assign('data',$data);
		$teacher=Db::name('live_teacher')->select();
		$this->assign('teacherlist',$teacher);
        if( $this->request->post()) {
            $savedata = $this->request->param();

            Db::name('live')->update($savedata);
            $this->success("修改成功", url("live/live_index"));
        }


        return $this->fetch();

    }
    //删除直播
    public function live_delete(){
        $id = $this->request->param('id', 0, 'intval');
        Db::name('live')->delete($id);
        $this->success("删除成功", url("live/live_index"));
    }
	
	
	
	
	//直播类型列表
    public function live_teacher_index(){
		$where = array();
		 if( $this->request->post()) {
			 $name = $this->request->post('name');
			 $where['b.name'] = array('like','%'.$name.'%');
		 }

		$rolesSrc = Db::name('live_teacher')
            ->field('b.*,a.avatar')
            ->alias('b')
            ->join('huiyuan a ','b.uid=a.id ' )
			->order('b.id desc')
			->where($where)
            ->paginate(20);
			// var_dump($rolesSrc);die;
        $page = $rolesSrc->render();
        $this->assign('page',$page);
        $this->assign('result',$rolesSrc);
        return $this->fetch();
    }
	//添加直播类型
    public function live_teacher_add(){
        if( $this->request->post()) {
            $data = $this->request->param();
			
			$data['uid'] = 1;
// var_dump($data);die;
            Db::name('live_teacher')->insert($data);
            $this->success("添加成功", url("live/live_teacher_index"));
        }
        return $this->fetch();
    }
    //编辑直播类型
    public function live_teacher_edit(){
        // $data=Db::name('live')->select();
        // $this->assign('data',$data);
        $id = $this->request->param('id', 0, 'intval');
		$data=Db::name('live_teacher')->where('id',$id )->find();
		$this->assign('data',$data);
        if( $this->request->post()) {
            $savedata = $this->request->param();
            Db::name('live_teacher')->update($savedata);
            $this->success("修改成功", url("live/live_teacher_index"));
        }


        return $this->fetch();

    }
    //删除直播类型
    public function live_teacher_delete(){
        $id = $this->request->param('id', 0, 'intval');
        Db::name('live_teacher')->delete($id);
        $this->success("删除成功", url("live/live_teacher_index"));
    }
	
	//直播类型列表
    public function live_comment_index(){
		$where = array();
		$id = $this->request->param('id', 0, 'intval');
		if($id){
			$where['b.liveid'] = $id;
		}
		 if( $this->request->post()) {
			 $content = $this->request->post('content');
			 $where['b.content'] = array('like','%'.$content.'%');
		 }
		
		$rolesSrc = Db::name('live_comment')
            ->field('b.*,a.title,c.username')
            ->alias('b')
            ->join('live a ','b.liveid=a.id ' )
			->join('huiyuan c ','b.uid=c.id ' )
			->order('b.id desc')
			->where($where)
            ->paginate(20);
        // $rolesSrc = Db::name('live_comment')
			// ->field('b.*,a.title,c.username')
            // ->alias('b')
            // ->join('live a ','b.liveid=a.id ' )
			// ->join('huiyuan c ','b.uid=c.id ' )
			// ->order('b.id desc')
			// ->where($where)
            // ->select();
		// var_dump($rolesSrc);die;
        $page = $rolesSrc->render();
        $this->assign('page',$page);
        $this->assign('result',$rolesSrc);
        return $this->fetch();
    }

    //删除直播类型
    public function live_comment_delete(){
        $id = $this->request->param('id', 0, 'intval');
        Db::name('live_comment')->delete($id);
        $this->success("删除成功", url("live/live_comment_index"));
    }
	
	//直播列表
    public function topic_index(){
		$where = array();
		 if( $this->request->post()) {
			 $title = $this->request->post('title');
			 $where['title'] = array('like','%'.$title.'%');
		 }
		
		
        $rolesSrc = Db::name('topic')
			->where($where)
            ->paginate(20);
		// var_dump($rolesSrc);die;
        $page = $rolesSrc->render();
        $this->assign('page',$page);
        $this->assign('result',$rolesSrc);
        return $this->fetch();
    }
	//添加直播
    public function topic_add(){
        $class=Db::name('topic_class')->select();
        $this->assign('classlist',$class);
        if( $this->request->post()) {
            $data = $this->request->param();
			$data['time'] = date('Y-m-d H:i:s');
            Db::name('topic')->insert($data);
            $this->success("添加成功", url("live/topic_index"));
        }
        return $this->fetch();
    }
    //编辑直播
    public function topic_edit(){
        // $data=Db::name('topic')->select();
        // $this->assign('data',$data);
        $id = $this->request->param('id', 0, 'intval');
		$data=Db::name('topic')->where('id',$id )->find();
		$this->assign('data',$data);
		$class=Db::name('topic_class')->select();
        $this->assign('classlist',$class);
        if( $this->request->post()) {
            $savedata = $this->request->param();

            Db::name('topic')->update($savedata);
            $this->success("修改成功", url("live/topic_index"));
        }


        return $this->fetch();

    }
    //删除直播
    public function topic_delete(){
        $id = $this->request->param('id', 0, 'intval');
        Db::name('topic')->delete($id);
        $this->success("删除成功", url("live/topic_index"));
    }
	
	//直播类型列表
    public function topic_class_index(){
        $rolesSrc = Db::name('topic_class')
            ->paginate(20);
        $page = $rolesSrc->render();
        $this->assign('page',$page);
        $this->assign('result',$rolesSrc);
        return $this->fetch();
    }
	//添加直播类型
    public function topic_class_add(){
        if( $this->request->post()) {
            $data = $this->request->param();
            Db::name('topic_class')->insert($data);
            $this->success("添加成功", url("live/topic_class_index"));
        }
        return $this->fetch();
    }
    //编辑直播类型
    public function topic_class_edit(){
        $id = $this->request->param('id', 0, 'intval');
		$data=Db::name('topic_class')->where('id',$id )->find();
		$this->assign('data',$data);
        if( $this->request->post()) {
            $savedata = $this->request->param();
            Db::name('topic_class')->update($savedata);
            $this->success("修改成功", url("live/topic_class_index"));
        }


        return $this->fetch();

    }
    //删除直播类型
    public function topic_class_delete(){
        $id = $this->request->param('id', 0, 'intval');
        Db::name('topic_class')->delete($id);
        $this->success("删除成功", url("live/topic_class_index"));
    }
	
	//阅读列表
    public function reading_index(){
		$where = array();
		 if( $this->request->post()) {
			 $title = $this->request->post('title');
			 $where['title'] = array('like','%'.$title.'%');
		 }
		
		
        $rolesSrc = Db::name('yw_reading')
			->where($where)
            ->paginate(20);
		// var_dump($rolesSrc);die;
        $page = $rolesSrc->render();
        $this->assign('page',$page);
        $this->assign('result',$rolesSrc);
        return $this->fetch();
    }
	//添加阅读
    public function reading_add(){
        if( $this->request->post()) {
            $data = $this->request->param();
			$data['time'] = date('Y-m-d H:i:s');
            Db::name('yw_reading')->insert($data);
            $this->success("添加成功", url("live/reading_index"));
        }
        return $this->fetch();
    }
    //编辑阅读
    public function reading_edit(){
        $id = $this->request->param('id', 0, 'intval');
		$data=Db::name('yw_reading')->where('id',$id )->find();
		$this->assign('data',$data);
        if( $this->request->post()) {
            $savedata = $this->request->param();

            Db::name('yw_reading')->update($savedata);
            $this->success("修改成功", url("live/reading_index"));
        }


        return $this->fetch();

    }
    //删除阅读
    public function reading_delete(){
        $id = $this->request->param('id', 0, 'intval');
        Db::name('yw_reading')->delete($id);
        $this->success("删除成功", url("live/reading_index"));
    }
}