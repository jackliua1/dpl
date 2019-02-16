<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use think\Db;
class BookController extends AdminbaseController{

    function _initialize() {
        parent::_initialize();
        $this->booksection_model = D("Common/booksection");
        $this->book_sc_model = D("Common/book");

    }
    //名著列表
    public function index(){
        $result=$this->book_sc_model->select();
//获取图片字段
        $date=$this->book_sc_model->field('id,image')->select();
        //对象转换数组
        foreach ($date as $k=>$v){

            $data[$k]=json_decode($date[$k]['image'],true);

        }
        //两个二维数组合并
        foreach($result as $key=>$vo){

            $list[] = array_merge($vo,$data[$key]);

        }
        $this->assign('result',$list);
        $this->display();
    }
    //添加名著
        public function book_add(){
        $this->display();
        }
        //添加名著处理
        public function kc_add_post(){
            if(IS_POST){
                //获取照片
                $_POST['smeta']['thumb'] = sp_asset_relative_url($_POST['smeta']['thumb']);
                //获取用户id
                $_POST['post']['uid']=get_current_admin_id();
                $page=I("post.post");
                $page['image']=json_encode($_POST['smeta']);
                $result=$this->book_sc_model->add($page);
                if ($result) {
                    $this->success("添加成功",U("book/index"));
                } else {
                    $this->error("添加失败！");
                }
            }

        }
        //编辑查询
        public function book_edit(){
            $id= intval(I("get.id"));
            $result=$this->book_sc_model->where("id=$id")->find();
            $this->assign('result',$result);

            $this->display();
        }
        //编辑修改
        public function book_update(){
            if(IS_POST){
                $id= intval(I("post.id"));
                $_POST['smeta']['thumb'] = sp_asset_relative_url($_POST['smeta']['thumb']);
                $page=I("post.post");
                $page['image']=json_encode($_POST['smeta']);
                $result=$this->book_sc_model->save($page);
                if($result !== false){
                    $this->success("修改成功",U("book/index"));
                }else{
                    $this->error("修改失败！");
                }
            }


        }
        //删除数据
        public function dell (){
            $id = I("get.id",0,"intval");
            if ($this->book_sc_model->delete($id)!==false) {
                $this->success("删除成功！");
            } else {
                $this->error("删除失败！");
            }

        }
        //分段列表
        public function fe_index(){
            $role_user_model=M("booksection");

            $role_user_join = C('DB_PREFIX').'book as b on a.bid =b.id';

            $result=$role_user_model->alias("a")->join($role_user_join)->field('a.id,b.name,a.time,b.uid')->select();
            $date=$this->booksection_model->field('content')->select();
            //对象转换数组
            foreach ($date as $k=>$v){

                $data[$k]=json_decode($date[$k]['content'],true);

            }
            //两个二维数组合并
            foreach($result as $key=>$vo){

                $list[] = array_merge($vo,$data[$key]);

            }
         $this->assign('result',$list);

            $this->display();
        }
        //分段添加
        public function fe_add(){
            $result=$this->book_sc_model->select();
            $this->assign('list',$result);
            $this->display();
        }
        public function book_fe_add(){
            if(IS_POST) {
                $_POST['smeta']['thumb'] = sp_asset_relative_url($_POST['smeta']['thumb']);
                $_POST['smetas']['thumbs'] = sp_asset_relative_url($_POST['smetas']['thumbs']);
                $page = I("post.post");
                $page['content'] = json_encode($_POST['smeta']);
                $page['url'] = json_encode($_POST['smetas']);
                $page['bid'] = $_POST['term'];
var_dump($page);die;
                $result=$this->booksection_model->add($page);
                if ($result) {
                    $this->success("添加成功",U("book/fe_index"));
                } else {
                    $this->error("添加失败！");
                }
            }

        }
        //分段编辑
        public function fe_edit(){
            $result=$this->book_sc_model->select();
            $this->assign('list',$result);
            if(IS_POST){
                $id= intval(I("post.id"));
                $_POST['smeta']['thumb'] = sp_asset_relative_url($_POST['smeta']['thumb']);
                $_POST['smetas']['thumbs'] = sp_asset_relative_url($_POST['smetas']['thumbs']);
                $page = I("post.post");
                $page['content'] = json_encode($_POST['smeta']);
                $page['url'] = json_encode($_POST['smetas']);
                $page['bid'] = $_POST['term'];
                $result=$this->booksection_model->save($page);
                if ($result) {
                    $this->success("修改成功",U("book/fe_index"));
                } else {
                    $this->error("修改失败！");
                }
            }
            $this->display();
        }
        //删除
        public function fe_dell(){
            $id = I("get.id",0,"intval");
            if ($this->booksection_model->delete($id)!==false) {
                $this->success("删除成功！");
            } else {
                $this->error("删除失败！");
            }
        }
}