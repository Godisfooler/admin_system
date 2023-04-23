<?php
namespace app\api\controller;
use think\Controller;

//商品
class Goods extends Controller
{

    //任务列表
    public function taskList(){
        $list = db('task_list')->select();
        $this->result($list,10000,'请求成功','json');
    } 

    public function getCategory(){
        $list = db('categorys')->select();
        $return = list_to_tree($list,0,'id','parentid','children');

        $this->result($return,10000,'请求成功','json');
    }

    public function getGoodslist(){
        $categoryid = input('categoryid');
        $list = db('goods_list')->where(['pid'=>$categoryid])->select();
        $this->result($list,10000,'请求成功','json');
    }

    //已发布商品列表
    public function publishlist(){
        $uid = empty(input('uid'))?1:input('uid');
        $list = db('goods_list')->where(['uid'=>$uid])->select();
        $this->result($list,10000,'请求成功','json');
    }
}
