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
            header('username:'.$res['username']);
            $this->result([],10000,'登录成功','json');
        }else{
            $this->result($res,10001,'账号或密码错误','json');
        }
    } 

    public function regist(){
        $imgUrl = $this->uploadFile();
        $username = input('username');
        $password = input('password');
        $data = [];
        $data['username'] = $username;
        $data['password'] = md5($password);
        $data['avatarUrl'] = $imgUrl;
        $data['iAddTime'] = time();
        $res = db('user_list')->insert($data);
        if($res){
            $this->result($res,10000,'注册成功','json');
        }else{
            $this->result($res,10001,'注册失败','json');
        }
    }

    public function uploadFile(){
        $file = request()->file('image');
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'avatarUrl');
            if($info){
                // 成功上传后 获取上传信息
                $filePath = $info->getSaveName(); 
            }else{
            }
        }
        $filePath = './avatarUrl/'.str_replace('\\','/',$filePath);
        return $filePath;
    }

    public function getUserImg(){
        $uid = input('uid');
        $list = db('user_list')->field('avatarUrl')->find($uid);
        $list['avatarUrl'] = 'http://'.$_SERVER['SERVER_NAME'].ltrim($list['avatarUrl'],'.');

        $this->result($list,10000,'请求成功','json');
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
