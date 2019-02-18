<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Customer;
use think\Db;
use app\index\model\Myorder;
use app\index\model\Cart;
use app\index\model\Shoplist;
use app\index\model\Flower;
use app\index\model\Showorder;
use app\index\model\Showshoplist;
class Order extends Controller{
    public function index()
    {
        if (empty(session('email')))
        {
            $this->error('请先登录','login/index');
        }
        $customer=Customer::where('email',session('email'))->select();
        if (empty($customer)){
            $this->error('请添加收货人地址','customer/index');
        }else {
            $this->assign('customer',$customer);
        }
        $cartIDs=input('post.cartID/a');
        if(!empty($cartIDs))
        {
            session('cartIDs',$cartIDs);
        }
        $cart=Db::table('vcart')->where('cartID','in',session('cartIDs'))->select();
        if (empty($cart)){
            $this->error('您的购物车为空','index/index');
        }else{
            $this->assign('vcart',$cart);
        }
        $tday=date('Y-m-d');
        $this->assign('tday',$tday);
        return $this->fetch();
    }
    public function addOrder(){
        Db::transaction(function(){
            
            $cartIDs=input('post.cartID/a');
            $vcarts=Db::table('vcart')->where('cartID','in',$cartIDs)->select();
            $sum=0;
            foreach ($vcarts as $vcart){
                $sum+=$vcart['yourprice'] * $vcart['num'];
            }
            //(1)添加订单信息到order表
            
            //(1.1)新建myorder对象，并将获取的表单值绑定到对应的属性。
            $myorder=new Myorder();
            $myorder->email=session('email');
            $myorder->custID=input('post.custID/d');
            $myorder->shifu=$sum;
            $myorder->inputtime=date("Y-m-d H:i:s");
            $myorder->peisongtime=input('post.peisongtime');
            $myorder->peisong=input('post.peisong');
            $myorder->psyq=input('post.psyq');
            $myorder->liuyan=input('post.liuyan');
            $myorder->shuming=input('post.shuming');
            $myorder->fkfs=input('post.fkfs');
            $myorder->fp=input('post.fp');
            $myorder->fpaddress=input('post.fpaddress');
            $myorder->zip=input('post.zip');
            $myorder->fpsname=input('post.fpname');
            $myorder->ddzt='未付款';
            $myorder->cltime=$myorder->peisongtime;
            //(1.2)添加订单
            $myorder->save();
        
            //(1.3)查找新添加的订单编号
            $order=Myorder::where('email',session('email'))->order('inputtime desc')->limit(1)->find();
            $orderID=$order->orderID;
            //（2）添加订单状态信息到ddzt表
            //（2.1）新建tbddzt表对象$ddzt
        
            //（2.2）绑定属性
        
            //（2.3）添加订单状态到tbddtz表。
        
            //（3）添加购买的商品信息及数量添加到shoplist表
            //（3.1）查看cart表
            $cartIDs=input('post.cartID/a');
            $carts=Cart::where('cartID','in',$cartIDs)->select();
        
            //（3.2）循环取出该用户购物车中的商品编号和数量。flowerID和num
            foreach ($carts as $cart){
                
            //(3.3)新建shoplist表对象$shoplist
            $shoplist = new Shoplist();
        
            //(3.4)绑定orderID、email、flowerID、num属性属性
            $shoplist->orderID = $orderID;
            $shoplist->email=session('email');
            $shoplist->flowerID=$cart->flowerID;
            $shoplist->num=$cart->num;
        
            //(3.5)添加到shoplist表
            $shoplist->save();
        
            //(4)根据购物车中的flowerID查找flower表，将其销售数量+num
            $flower=Flower::get($cart->flowerID);
            $flower->SelledNum=$flower->SelledNum+$cart->num;
            $flower->save();
        
            //(5)删除购物车表
            $cart->delete();
            }
        });
            //(6)跳转
            $this->redirect('order/showorder');
    }
    public function showorder(){
        if(empty(session('email'))){
            $this->error('请先登录','login/index');
        }
        $orders=Showorder::where('email',session('email'))->paginate(3);
        $page=$orders->render();
        $this->assign('page',$page);
        $this->assign('orders',$orders);
        $orderlists=array();
        foreach ($orders as $order){
            $Showshoplists = Showshoplist::where('orderID',$order->orderID)->select();
            $shoplistitems=array();
            foreach ($Showshoplists as $Showshoplist){
                array_push($shoplistitems, $Showshoplist);
            }
            array_push($orderlists,$shoplistitems);
        }
        $this->assign('orderlists',$orderlists);
        return $this->fetch();
    }
    public function orderDelete()
    {
        Db::transaction(function(){
            $order=Myorder::get(input('get.orderID/d'));
            $orderID=$order->orderID;
            $order->delete();
            $shoplists=Shoplist::where('orderID',$orderID)->select();
            foreach ($shoplists as $shoplist)
            {
                $shoplist->delete();
            }
            
        });
        $this->redirect('order/showorder');
    }
    public function orderUpdate(){
        $orderID=input('get.orderID/d');
        $order=Myorder::get($orderID);
        $order->ddzt='未评价';
        $order->cltime=date('Y-m-d H:i:s');
        $order->save();
        $this->redirect('order/showorder');
    }
}