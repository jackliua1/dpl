<?php
namespace Admin\Controller;

namespace app\admin\controller;

use app\admin\model\BookModel;
use cmf\controller\AdminBaseController;
use mindplay\annotations\standard\VarAnnotation;
use think\Db;

class YuwenController extends AdminBaseController{
//写作列表
    public function index(){

        return $this->fetch();

    }
    //题型列表
    public function t_index(){
        $rolesSrc = Db::name('yuwenti')
            ->alias('b')
            ->join('nianji as ','b.grade=as.id' )
            ->field('b.id,as.n_name,b.time,b.title,b.authentication,b.mun,b.shiping')
            ->paginate(20);

        $page = $rolesSrc->render();
        $this->assign('page',$page);
        $this->assign('result',$rolesSrc);
        return $this->fetch();
    }
    public function t_indexs(){
        if( $this->request->post()) {
            $data = $this->request->param();
            $tj['title'] = array('like','%'.$data['title'].'%');

            $datass =Db::name('yuwenti')
                ->alias('b')
                ->join('nianji as ','b.grade=as.id' )
                ->field('b.id,as.n_name,b.time,b.title,b.authentication,b.mun,b.shiping')
                ->where($tj)
                ->select();

            $datas =Db::name('yuwenti')
                ->alias('b')
                ->join('nianji as ','b.grade=as.id' )
                ->field('b.id,as.n_name,b.time,b.title,b.authentication,b.mun,b.shiping')
                ->where($tj)
                ->find();

            $this->assign('title', isset($data['title']) ? $data['title'] : '');
            $this->assign('result',$datass);
            $this->assign('datas', $datas);
        }
        return $this->fetch();
    }
    //添加题型
    public function add(){
        $data=Db::name('nianji')->select();
        $this->assign('data',$data);
        if( $this->request->post()) {
            $data = $this->request->param();

            Db::name('yuwenti')->insert($data);
            $this->success("添加成功", url("yuwen/t_index"));
        }
        return $this->fetch();
    }
    //编辑试卷
    public function etid(){
        $data=Db::name('nianji')->select();
        $this->assign('data',$data);
        $id = $this->request->param('id', 0, 'intval');
        $rolesSrc =    Db::name('yuwenti')
            ->alias('b')
            ->join('nianji as ','b.grade=as.id' )
            ->field('b.id,as.n_name,b.time,b.title,b.authentication,b.mun,b.shiping,b.grade')
            ->where('b.id',$id)
            ->find();
        $this->assign('datas',$rolesSrc);

        if( $this->request->post()) {
            $data = $this->request->param();

            Db::name('yuwenti')->update($data);
            $this->success("修改成功", url("yuwen/t_index"));
        }


        return $this->fetch();

    }
    //删除试卷
    public function dell(){
        $id = $this->request->param('id', 0, 'intval');
        Db::name('yuwenti')->delete($id);
        $this->success("删除成功", url("yuwen/t_index"));
    }
	
	//诗词列表
    public function poetry_index(){
		$where = array();
		 if( $this->request->post()) {
			 $title = $this->request->post('title');
			 $where['b.title'] = array('like','%'.$title.'%');
		 }
		
		
        $rolesSrc = Db::name('poetry')
            ->field('b.*,a.dynasty')
            ->alias('b')
            ->join('poetry_author a ','b.authorid=a.id ' )
			->order('b.id desc')
			->where($where)
            ->paginate(20);
		// var_dump($rolesSrc);die;
        $page = $rolesSrc->render();
        $this->assign('page',$page);
        $this->assign('result',$rolesSrc);
        return $this->fetch();
    }
	//添加诗词
    public function poetry_add(){
        $class=Db::name('poetry_class')->select();
		$author=Db::name('poetry_author')->select();
        $this->assign('classlist',$class);
		$this->assign('authorlist',$author);
		// $this->assign('data',$data);
        if( $this->request->post()) {
            $data = $this->request->param();
// var_dump($data);die;
            Db::name('poetry')->insert($data);
            $this->success("添加成功", url("yuwen/poetry_index"));
        }
        return $this->fetch();
    }
    //编辑诗词
    public function poetry_edit(){
        // $data=Db::name('poetry')->select();
        // $this->assign('data',$data);
        $id = $this->request->param('id', 0, 'intval');
		$data=Db::name('poetry')->where('id',$id )->find();
		$this->assign('data',$data);
		$class=Db::name('poetry_class')->select();
		$author=Db::name('poetry_author')->select();
        $this->assign('classlist',$class);
		$this->assign('authorlist',$author);
        if( $this->request->post()) {
            $savedata = $this->request->param();

            Db::name('poetry')->update($savedata);
            $this->success("修改成功", url("yuwen/poetry_index"));
        }


        return $this->fetch();

    }
    //删除诗词
    public function poetry_delete(){
        $id = $this->request->param('id', 0, 'intval');
        Db::name('poetry')->delete($id);
        $this->success("删除成功", url("yuwen/poetry_index"));
    }
	
	//诗词类型列表
    public function poetry_class_index(){
        $rolesSrc = Db::name('poetry_class')
            ->paginate(20);
		// var_dump($rolesSrc);die;
        $page = $rolesSrc->render();
        $this->assign('page',$page);
        $this->assign('result',$rolesSrc);
        return $this->fetch();
    }
	//添加诗词类型
    public function poetry_class_add(){
        if( $this->request->post()) {
            $data = $this->request->param();
// var_dump($data);die;
            Db::name('poetry_class')->insert($data);
            $this->success("添加成功", url("yuwen/poetry_class_index"));
        }
        return $this->fetch();
    }
    //编辑诗词类型
    public function poetry_class_edit(){
        // $data=Db::name('poetry')->select();
        // $this->assign('data',$data);
        $id = $this->request->param('id', 0, 'intval');
		$data=Db::name('poetry_class')->where('id',$id )->find();
		$this->assign('data',$data);
        if( $this->request->post()) {
            $savedata = $this->request->param();
            Db::name('poetry_class')->update($savedata);
            $this->success("修改成功", url("yuwen/poetry_class_index"));
        }


        return $this->fetch();

    }
    //删除诗词类型
    public function poetry_class_delete(){
        $id = $this->request->param('id', 0, 'intval');
        Db::name('poetry_class')->delete($id);
        $this->success("删除成功", url("yuwen/poetry_class_index"));
    }
	//诗词类型列表
    public function poetry_author_index(){
		$where = array();
		 if( $this->request->post()) {
			 $name = $this->request->post('name');
			 $where['name'] = array('like','%'.$name.'%');
		 }
		
		

        $rolesSrc = Db::name('poetry_author')
			->where($where)
            ->paginate(20);
		// var_dump($rolesSrc);die;
        $page = $rolesSrc->render();
        $this->assign('page',$page);
        $this->assign('result',$rolesSrc);
        return $this->fetch();
    }
	//添加诗词类型
    public function poetry_author_add(){
        if( $this->request->post()) {
            $data = $this->request->param();
// var_dump($data);die;
            Db::name('poetry_author')->insert($data);
            $this->success("添加成功", url("yuwen/poetry_author_index"));
        }
        return $this->fetch();
    }
    //编辑诗词类型
    public function poetry_author_edit(){
        // $data=Db::name('poetry')->select();
        // $this->assign('data',$data);
        $id = $this->request->param('id', 0, 'intval');
		$data=Db::name('poetry_author')->where('id',$id )->find();
		$this->assign('data',$data);
        if( $this->request->post()) {
            $savedata = $this->request->param();
            Db::name('poetry_author')->update($savedata);
            $this->success("修改成功", url("yuwen/poetry_author_index"));
        }


        return $this->fetch();

    }
    //删除诗词类型
    public function poetry_author_delete(){
        $id = $this->request->param('id', 0, 'intval');
        Db::name('poetry_author')->delete($id);
        $this->success("删除成功", url("yuwen/poetry_author_index"));
    }
	
	
	//诗词列表
    public function topic_index(){
		$where = array();
		 if( $this->request->post()) {
			 $title = $this->request->post('title');
			 $where['title'] = array('like','%'.$title.'%');
		 }
		
		
        $rolesSrc = Db::name('topic')
			->where($where)
			->order('id desc')
            ->paginate(20);
		// var_dump($rolesSrc);die;
        $page = $rolesSrc->render();
        $this->assign('page',$page);
        $this->assign('result',$rolesSrc);
        return $this->fetch();
    }
	//添加诗词
    public function topic_add(){
        $class=Db::name('topic_class')->select();
        $this->assign('classlist',$class);
        if( $this->request->post()) {
            $data = $this->request->param();
			// $data['content'] = cmf_replace_content_file_url(htmlspecialchars_decode($data['content']), true);
			$data['time'] = date('Y-m-d H:i:s');
            Db::name('topic')->insert($data);
            $this->success("添加成功", url("yuwen/topic_index"));
        }
        return $this->fetch();
    }
    //编辑诗词
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
			// var_dump($savedata['content']);
			$savedata['content'] = cmf_replace_content_file_url(htmlspecialchars_decode($savedata['content']), true);
// var_dump($savedata['content']);die;
            Db::name('topic')->update($savedata);
            $this->success("修改成功", url("yuwen/topic_index"));
        }


        return $this->fetch();

    }
    //删除诗词
    public function topic_delete(){
        $id = $this->request->param('id', 0, 'intval');
        Db::name('topic')->delete($id);
        $this->success("删除成功", url("yuwen/topic_index"));
    }
	
	//诗词类型列表
    public function topic_class_index(){
        $rolesSrc = Db::name('topic_class')
            ->paginate(20);
        $page = $rolesSrc->render();
        $this->assign('page',$page);
        $this->assign('result',$rolesSrc);
        return $this->fetch();
    }
	//添加诗词类型
    public function topic_class_add(){
        if( $this->request->post()) {
            $data = $this->request->param();
            Db::name('topic_class')->insert($data);
            $this->success("添加成功", url("yuwen/topic_class_index"));
        }
        return $this->fetch();
    }
    //编辑诗词类型
    public function topic_class_edit(){
        $id = $this->request->param('id', 0, 'intval');
		$data=Db::name('topic_class')->where('id',$id )->find();
		$this->assign('data',$data);
        if( $this->request->post()) {
            $savedata = $this->request->param();
            Db::name('topic_class')->update($savedata);
            $this->success("修改成功", url("yuwen/topic_class_index"));
        }


        return $this->fetch();

    }
    //删除诗词类型
    public function topic_class_delete(){
        $id = $this->request->param('id', 0, 'intval');
        Db::name('topic_class')->delete($id);
        $this->success("删除成功", url("yuwen/topic_class_index"));
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
            $this->success("添加成功", url("yuwen/reading_index"));
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
            $this->success("修改成功", url("yuwen/reading_index"));
        }


        return $this->fetch();

    }
    //删除阅读
    public function reading_delete(){
        $id = $this->request->param('id', 0, 'intval');
        Db::name('yw_reading')->delete($id);
        $this->success("删除成功", url("yuwen/reading_index"));
    }
}