<?php
namespace app\api\controller;
use think\Controller;

//商品
class Login extends Controller
{

    //用户登录
    public function login(){
        $username = input('username');
        $password = input('password');
        $map = [];
        $map['username'] = $username;
        $map['password'] = md5($password);
        $res = db('user_list')->where($map)->find();
        if($res){
            header('uid:'.$res['id']);
            $this->result([],10000,'登录成功','json');
        }else{
            $this->result($res,10000,'账号或密码错误','json');
        }
    } 

    public function regist(){
        $username = input('username');
        $password = input('password');
        $data = [];
        $data['username'] = $username;
        $data['password'] = md5($password);
        $res = db('user_list')->insert($data);
        if($res){
            $this->result($res,10000,'注册成功','json');
        }else{
            $this->result($res,10000,'注册失败','json');
        }
    }

    public function getCategory(){
        $uid = $_COOKIE['uid'];
        var_dump($uid);exit;
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
