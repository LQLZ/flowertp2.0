<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use app\index\model\Customer as CustomerModel;
class Customer extends Controller{
    public function index()
    {
        return $this->fetch();
    }
    public function customerAdd()
    {
        if(empty(session('email'))){
            $this->error('请先登录','login/index');
        }
        $custs=customerModel::where('email',session('email'))->select();
        $customer = new CustomerModel();
        $customer->email=session('email');
        $customer->sname=input ('post.sname');
        $customer->sex=input ('post.sex');
        $customer->mobile=input ('post.mobile');
        $customer->address=input ('post.address');
        $customer->zip=input ('post.zip');
        if (empty($custs)){
            $customer->cdefault='1';
        }else {
            $customer->cdefault='0';
        }
        $customer->save();
        $this->redirect('order/index');
    }
    public function customerUpdate()
    {
        Db::transaction(function(){
            $cust1=CustomerModel::where('email',session('email'))->where('cdefault','1')->find();
            $cust1->cdefault='0';
            $cust1->save();
            $cust2=CustomerModel::get(input('get.custID/d'));
            $cust2->cdefault='1';
            $cust2->save();
        });
        $this->redirect('order/index');
    }
    public function customerDelete(){
        $cust=CustomerModel::get(input('get.custID/d'));
        $cust->delete();
        $this->redirect('order/index');
    }
}