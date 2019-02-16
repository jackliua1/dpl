<?php
namespace Admin\Controller;

namespace app\admin\controller;

use app\admin\model\BookModel;
use cmf\controller\AdminBaseController;
use mindplay\annotations\standard\VarAnnotation;
use think\Db;
class BookController extends AdminBaseController{

    function _initialize() {
//        parent::_initialize();
//        $this->booksection_model = D("Common/booksection");
//        $this->book_sc_model = D("Common/book");

    }
    //名著列表
    public function index(){
        $rolesSrc = Db::table('cmf_book')->order("id")
            ->paginate(20);
        $page = $rolesSrc->render();;

        $this->assign('page',$page);
        $this->assign('result',$rolesSrc);
        if ($this->request->post()) {
            $data = $this->request->param();
            $datas = Db::name('book')->where($data)->select();
            $this->assign('result', $datas);
            $this->assign('name', isset($data['name']) ? $data['name'] : '');
        }
      return  $this->fetch();
    }
    //添加名著
        public function add(){
            return  $this->fetch();
        }
        //添加名著处理
        public function kc_add_post(){
          if( $this->request->post()){
              $data = array();
              $data['name'] = input('name');
			  $data['author'] = input('author');
              $data['type'] = input('type');
              $data['level'] = input('level');
              $data['time'] = input('time');
              $data['uid'] = cmf_get_current_admin_id();
              $data['image'] = input('image');
              $data['contents'] = input('contents');

               Db::name('book')->insert($data);

              $this->success("添加成功",url("book/index"));


          }
        }
        //编辑查询
        public function add_post(){
            if( $this->request->post()){
                $data = $this->request->param();
                Db::name('book')->update($data);

                $this->success("修改成功",url("book/index"));


            }
        }
        //编辑修改
        public function etid(){
            $id = $this->request->param('id', 0, 'intval');
            $data=  Db::name('book')->where('id',$id)->find();
             $this->assign('data',$data);
            return  $this->fetch();

        }
        //删除数据
        public function dell (){
            $id = $this->request->param('id', 0, 'intval');
            Db::name('book')->delete($id);
            $this->success("删除成功！", url("book/index"));

        }
        //搜索
//    public function search()
//    {
//        if ($this->request->post()) {
//            $data = $this->request->param();
//            $datas=  Db::name('book')->where($data)->select();
//            $this->assign('result',$datas);
//            return  $this->fetch();
//var_dump($datas);die;
//            Db::name('book')->update($data);
//
//            $this->success("修改成功", url("book/index"));
//
//        }
//    }
        //章节列表
        public function fe_index(){
			$id = $this->request->param('id', 0, 'intval');
            $rolesSrc = Db::name('booksection')
                ->alias('b')
                ->join('book as ','b.bid=as.id' )
                ->field('b.*,as.name')
                ->order("id")
				->where('b.bid',$id)
                ->paginate(20);
				// var_dump($id);var_dump($rolesSrc);die;
            $page = $rolesSrc->render();
            $this->assign('page',$page);
            $this->assign('result',$rolesSrc);
            if ($this->request->post()) {
                $data = $this->request->param();
				// var_dump($data);die;
				$where['name'] = array('like','%'.$data['name'].'%');
				// var_dump($data);die;
                $datas = Db::name('booksection')
                    ->alias('b')
                    ->join('book as ','b.bid=as.id' )
                    ->field('b.id,as.name,b.time,b.content,b.url')
                    ->where($where)
                    ->select();
                $this->assign('result', $datas);
                $this->assign('name', isset($data['name']) ? $data['name'] : '');
            }
            return $this->fetch();
        }
        //章节添加
        public function fe_add(){
            $rolesSrc = Db::table('cmf_book')->select();
            $this->assign('result',$rolesSrc);
            return $this->fetch();
        }
        //章节添加
        public function addPost(){
            if( $this->request->post()) {
				$bid = $this->request->param('bid', 0, 'intval');
                $data = $this->request->param();
//                $data['url'] = $_POST['photo_names']['url'];
//                var_dump($data);die;
                Db::name('booksection')->insert($data);

                $this->success("添加成功", url("book/fe_index",array('id'=>$bid)));
            }
        }
        //章节编辑
        public function fe_edit(){
            $rolesSrc = Db::table('cmf_book')->select();
            $this->assign('result',$rolesSrc);
            $id = $this->request->param('id', 0, 'intval');
			$bid = $this->request->param('bid', 0, 'intval');
            $data=  Db::name('booksection')
                    ->alias('b')
                    ->join('book as ','b.bid=as.id' )
                    ->field('b.*,as.name')
                    ->where('b.id',$id)
                    ->find();
            $this->assign('data',$data);
            if( $this->request->post()) {
                $datas = $this->request->param();
				// var_dump($datas);die;

                Db::name('booksection')->update($datas);
                $this->success("添加成功", url("book/fe_index",array('id'=>$bid)));
            }
            return $this->fetch();
        }
        //删除
        public function fe_dell()
        {
            $id = $this->request->param('id', 0, 'intval');
			$bid = $this->request->param('bid', 0, 'intval');
            Db::name('booksection')->delete($id);
            $this->success("删除成功！", url("book/fe_index",array('id'=>$bid)));
        }
		
		//分段列表
        public function dl_index(){
			$id = $this->request->param('id', 0, 'intval');
			$where = array('fid'=>$id);
			$list = Db::name('bookparagraph')->where($where)->select();
			// var_dump($list);die;
			$this->assign('list',$list);
            $this->assign('fid',$id);
            return $this->fetch();
        }
        //分段添加
        public function dl_add(){
            $rolesSrc = Db::table('cmf_book')->select();
            $this->assign('result',$rolesSrc);
            return $this->fetch();
        }
        //分段添加
        public function dl_addPost(){
            if( $this->request->post()) {
                $data = $this->request->param();
//                $data['url'] = $_POST['photo_names']['url'];
//                var_dump($data);die;
                Db::name('bookparagraph')->insert($data);

                $this->success("添加成功", url("book/dl_index"));
            }
        }
        //分段编辑
        public function dl_edit(){
			// var_dump($this->request->param());die;
            // Db::name('bookparagraph')->delete($id);
            // $this->success("编辑成功！", url("book/dl_index"));
			
			// $wids = $this->request->param('ids');
			$fid = $this->request->param('fid');
			$wids = $_POST['ids'];
			$list_orders =  $_POST['list_orders'];
			$english =  $_POST['english'];
			$chinese =  $_POST['chinese'];
			// var_dump();die;
			$wsize = count ( $wids );
			for($i = 0; $i < $wsize; $i ++) {
				$datas[$i]['list_order'] = $list_orders[$i];
				$datas[$i]['english'] = $english[$i];
				$datas[$i]['chinese'] = $chinese[$i];
				if ($wids [$i]) {					
					$datas[$i]['id'] = $wids[$i];
					Db::name('bookparagraph')->update($datas[$i]);
				} else {
					$datas[$i]['fid'] = $fid;
					if($english[$i]||$chinese[$i]){
						Db::name('bookparagraph')->insert($datas[$i]);				
					}
				}
			}
			$this->success("编辑成功", url("book/dl_index",array('id'=>$fid)));
        }
        //删除
        public function dl_dell()
        {
            $ids = $_POST['choseid'];
			$fid = $this->request->param('fid');
			foreach($ids as $vo){
				Db::name('bookparagraph')->delete($vo);
			}
            $this->success("删除成功！", url("book/dl_index",array('id'=>$fid)));
        }
		
}