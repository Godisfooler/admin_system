<?php
namespace app\admin\controller;

use think\Controller;
use think\Loader;

class Login extends Controller {

    /**
     * 登入
     */
    public function index() {

        if ($_POST['dosubmit']) {
            $username = input('post.username');
            $password = input('post.password');
            if (!$username) {
                $this->error('用户名不能为空',null,'',1);
            }
            if (!$password) {
                $this->error('密码不能为空',null,'',1);
            }

            $info = db('admin')->field('id,username,password')->where('username', $username)->find();
            if (!$info) {
                $this->error('用户不存在',null,'',1);
            }

            if (md5($password) != $info['password']) {
                $this->error('密码不正确');
            } else {
                session('user_name', $info['username']);
                session('user_id', $info['id']);
                //记录登录信息
                Loader::model('Admin')->editInfo(1, $info['id']);
                $this->success('登入成功', 'index/index','',1);
            }
        } else {

            if (session('user_name')) {
                $this->success('您已登入', 'index/index','',1);
            }
            $this->view->engine->layout(false);
            return $this->fetch('login');
        }
    }

    /**
     * 登出
     */
    public function logout() {
        session('user_name', null);
        session('user_id', null);
        $this->success('退出成功', 'login/index','',1);
    }

    public function test(){
        echo 'test';
    }

     /**
     * 注册账号
     */
    public function regist() {
        if ($_POST['dosubmit']) {
            $username = input('post.username');
            $password = input('post.password');
            if (!$username) {
                $this->error('用户名不能为空',null,'',1);
            }
            if (!$password) {
                $this->error('密码不能为空',null,'',1);
            }

            $info = db('admin')->field('id,username,password')->where('username', $username)->find();
            if (!$info) {
                $this->error('用户不存在',null,'',1);
            }

            if (md5($password) != $info['password']) {
                $this->error('密码不正确',null,'',1);
            } else {
                session('user_name', $info['username']);
                session('user_id', $info['id']);
                //记录登录信息
                Loader::model('Admin')->editInfo(1, $info['id']);
                $this->ajaxReturn(['注册成功！']);
                // $this->success('登入成功', 'index/index');
            }
        } else {
            if (session('user_name')) {
                $this->success('您已登入', 'index/index','',1);
            }
            $this->view->engine->layout(false);
            return $this->fetch('login');
        }
    }
}
