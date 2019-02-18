<?php
namespace app\admin\controller;
use think\Controller;
use app\index\model\Myorder;
use think\Db;
class Order extends Controller{
    public function index(){
        $orders=Db::table('peisong')->select();
        $this->assign('orders',$orders);
        $orderlists=array();
        foreach ($orders as $order){
            $shoplists=Db::table('showshoplist')->where('orderID',$order['orderID'])->select();
            $lists=array();
            foreach ($shoplists as $shoplist){
                array_push($lists,$shoplist);
            }
            array_push($orderlists, $lists);
        }
        $this->assign('orderlists',$orderlists);
        return $this->fetch();
    }
    public function updateOrder(){
        $kddh=input('post.kddh');
        $orderID=input('post.orderID');
        $order=Myorder::get($orderID);
        $order->kddh=$kddh;
        $order->ddzt="已发货";
        $order->cltime=date('Y-m-d H:i:s');
        $order->save();
        return $this->redirect('index/');
    }
}