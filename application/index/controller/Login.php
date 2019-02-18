<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
class Login extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
    public function dologin()
    {
        $param = input('post.');
        if(empty($param['email'])){
            $this->error('email不能为空');
        }
        if(empty($param['passw'])){
            $this->error('密码不能为空');
        }
        $rs = Db::table('member')->where('email',$param['email'])->find();
        if(empty($rs)){
            $this->error('输入用户名有误');
        }elseif ($rs['password']!=$param['passw']){
            $this->error('输入密码有误');
        }else {
            session('email',$rs['email']);
            return redirect(url('index/index'));
        }
    }
    public function logOut(){
        session('email',null);
        return redirect(url('index/index'));
    }
}