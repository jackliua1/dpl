<?php
namespace Admin\Controller;

namespace app\admin\controller;

use app\admin\model\BookModel;
use cmf\controller\AdminBaseController;
use mindplay\annotations\standard\VarAnnotation;
use think\Db;
class MemberController extends AdminBaseController
{

    //会员列表
    public function index()
    {
        $data=Db::name('huiyuan')    ->order("id")
            ->paginate(20);
        $page = $data->render();;
        $this->assign('data',$data);
        $this->assign('page',$page);
        $list=Db::name('school')->select();
        $this->assign('list',$list);
//        $id = $this->request->param('id', 0, 'intval');
//        $datas=Db::name('huiyuan') ->where('id',$id)->select();
//        $this->assign('data',$datas);
        return $this->fetch();

    }
    public function  ajaxArea()
    {
        $pid=$this->request->get('id');

            $list = Db::name('huiyuan')->where('bid', $pid)->select();
            echo json_encode($list);
    }
    public function chaxun(){
        if( $this->request->post()){
        $data = $this->request->param();
        $tj['loginname'] = array('like','%'.$data['loginname'].'%');
        $tj['username'] = array('like','%'.$data['username'].'%');
        $tj['tel'] = array('like','%'.$data['tel'].'%');
        $datas= Db::name('huiyuan') ->where($tj)
            ->select();
        $this->assign('loginname', isset($data['loginname']) ? $data['loginname'] : '');
        $this->assign('username', isset($data['username']) ? $data['username'] : '');
        $this->assign('tel', isset($data['tel']) ? $data['tel'] : '');
        $this->assign('data',$datas);
    }
        return $this->fetch('Member/index');
    }
    //导入文件
    public function add()
    {

        $data=Db::name('school')->select();
        $this->assign('data',$data);

        return $this->fetch();
    }

    //导入文件操作
    public function implode_post()
    {
        if ($this->request->isPost()) {
            $excel_name = $this->request->param('excel_name');
            $excel_url = $this->request->param('excel_url');
            $bid = $this->request->param('bid');
            if (!empty($excel_name) && !empty($excel_url)) {
                $filename = ROOT_PATH . 'public/upload/' . $excel_url;  //文件路径
                $extension = cmf_get_file_extension($excel_url);    //文件扩展名
                if ($extension == 'xlsx') {
                    $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
                    $objPHPExcel = $objReader->load($filename, $encode = 'utf-8');  //加载文件内容,编码utf-8
                } else if ($extension == 'xls') {
                    $objReader = \PHPExcel_IOFactory::createReader('Excel5');
                    $objPHPExcel = $objReader->load($filename, $encode = 'utf-8');  //加载文件内容,编码utf-8
                } else {
                    $this->error('请上传Excel格式的文件！', '');
                }
                $excel_array = $objPHPExcel->getsheet(0)->toArray();
                $data = [];
                foreach ($excel_array as $k => $v) {
                    $data[$k]['bid'] = $bid;
                    $data[$k]['loginname'] = $v[0];
                    $data[$k]['password'] = $v[1];
                    $data[$k]['username'] = $v[2];
                    $data[$k]['sex'] = $v[3];
                    $data[$k]['birthday'] =$v[4];
                    $data[$k]['class_id'] = $v[5];
                    $data[$k]['address'] =$v[6];
                    $data[$k]['type'] = $v[7];
                    $data[$k]['integral'] = 0;
                    $data[$k]['balance'] = 0;
                    $data[$k]['tel'] = $v[8];
                    $data[$k]['password'] = cmf_password($v[1]);
                }
                if(Db::name('huiyuan')->where('bid',$bid)->find()==null) {
                    if (Db::name('huiyuan')->insertAll($data)) {
                        $this->success('导入数据成功！', url('Member/index'));
                    } else {
                        $this->error('导入数据失败!', '');
                    }
                }else{
                    $this->error('该学校已经添加!', '');  die;
                }
            } else {
                $this->error('文件导入失败!', '');
            }
        }

    }
    //删除数据
    public function dell(){
        $id = $this->request->param('id', 0, 'intval');
        Db::name('huiyuan')->delete($id);
        $this->success("删除成功！",url('Member/index'));
    }
    //编辑
    public function etid(){

        return $this->fetch();
    }
    //学生列表
    public function x_index(){

        $data=Db::name('huiyuan')->where('type',1)->order("id")
            ->paginate(20);
        $page = $data->render();;

        $this->assign('page',$page);
        $this->assign('data',$data);
        if( $this->request->post()){
            $data = $this->request->param();

            $datas= Db::name('huiyuan')->where($data)->where('type',1)->select();
            $this->assign('data',$datas);
        }
        return $this->fetch();
    }
    //学生编辑
    public function x_add(){
        $id = $this->request->param('id', 0, 'intval');
       $date= Db::name('huiyuan')->where('id',$id)->find();
        $this->assign('date',$date);
        $data=Db::name('school')->select();
        $this->assign('data',$data);
        return $this->fetch();
    }
    //修改操作
    public function x_post(){
        if( $this->request->post()){
            $data = $this->request->param();

            Db::name('huiyuan')->update($data);
            $this->success("修改成功",url("Member/x_index"));
        }
    }
    //删除学生
    public function x_dell(){
        $id = $this->request->param('id', 0, 'intval');
        Db::name('huiyuan')->delete($id);
        $this->success("删除成功！",url('Member/x_index'));
    }
    //老师列表
    public function l_index(){

        $data=Db::name('huiyuan')->where('type',2)->order("id")
            ->paginate(20);
        $page = $data->render();;
        $this->assign('page',$page);;
        $this->assign('data',$data);
        if( $this->request->post()){
            $data = $this->request->param();

            $datas= Db::name('huiyuan')->where($data)->where('type',2)->select();
            $this->assign('data',$datas);
        }
        return $this->fetch();
    }
    //编辑老师
    public function l_add(){
        $id = $this->request->param('id', 0, 'intval');
        $date= Db::name('huiyuan')->where('id',$id)->find();
        $this->assign('date',$date);
        return $this->fetch();
    }
    //提交修改
    public function l_post(){
        if( $this->request->post()){
            $data = $this->request->param();
            Db::name('huiyuan')->update($data);
            $this->success("修改成功",url("Member/l_index"));
        }
    }
    //删除老师
    public function l_dell(){
        $id = $this->request->param('id', 0, 'intval');
        Db::name('huiyuan')->delete($id);
        $this->success("删除成功！",url('Member/l_index'));
    }
}