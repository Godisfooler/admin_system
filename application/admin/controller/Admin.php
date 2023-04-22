<?php
namespace app\admin\controller;

use think\Loader;

class Admin extends Common {

    public function index() {
        
        $where = array();
        if ($group_id = input('group_id')) {
            $where['t2.group_id'] = $group_id;
            
        }
        $lists = db('admin')->alias('t1')->field('t1.*')
                ->where($where)
                ->join(config('database.prefix').'admin_group_access t2', 't1.id=t2.uid', 'left')
                ->group('t1.id')
                ->order('t1.id desc')
                ->select();
     
        foreach ($lists as $k => $v) {
            //取出组名
            $lists[$k]['groups'] = '';
            $groups = Loader::model('Admin')->getUserGroups($v['id']);
            if ($groups) {
                $tmp = db('admin_group')->field('name')->where('id', 'in', $groups)->select();

                foreach ($tmp as $vv) {
                    $lists[$k]['groups'] .= $vv['name'] . ',';
                }
                $lists[$k]['groups'] = trim($lists[$k]['groups'], ',');
            }
        }

        $this->assign('lists', $lists);
        return $this->fetch();
    }

    /*
     * 查看
     */

    public function info() {
        $id = input('id');
        if ($id) {
            //当前用户信息
            $info = model('Admin')->getInfo($id);
            $info['userGroups'] = Loader::model('Admin')->getUserGroups($id);
            $this->assign('info', $info);
        }

        //所有组信息
        $groups = model('AdminGroup')->getGroups();

        $this->assign('groups', $groups);
        return $this->fetch();
    }

    /*
     * 添加
     */

    public function add() {
        $data = input();
        $count = db('admin')->where('username', $data['username'])->count();

        if ($count) {
            $this->error('用户名已存在');
        }

        if ($data['password'] != $data['rpassword']) {
            $this->error('两次密码不一致');
        }

        $data['password'] = md5($data['password']);

        $res = model('Admin')->allowField(true)->save($data);

        if ($res) {
            if (isset($data['groups'])) {
                $uid = model('Admin')->id;
                foreach ($data['groups'] as $v) {
                    db('admin_group_access')->insert(['uid' => $uid, 'group_id' => $v]);
                }
            }
            $this->success('操作成功', url('index'));
        } else {
            $this->error('操作失败');
        }
    }

    /*
     * 修改
     */

    public function edit() {
        $data = input();
        db('admin_group_access')->where(['uid' => $data['id']])->delete();

        if (isset($data['groups'])) {
            foreach ($data['groups'] as $v) {
                db('admin_group_access')->insert(['uid' => $data['id'], 'group_id' => $v]);
            }
        }

        if (!$data['password']) {
            unset($data['password']);
        } else {
            if ($data['password'] != $data['rpassword']) {
                $this->error('两次密码不一致!');
            }
            $data['password'] = md5($data['password']);
        }

        $res = Loader::model('Admin')->editInfo(2, $data['id'], $data);

        if ($res) {
            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
    }

    /*
     * 删除
     */

    public function del() {
        $id = input('id');
        $res = db('admin')->where(['id' => $id])->delete();
        if ($res) {
            db('admin_group_access')->where(['uid' => $id])->delete();
            $this->success('操作成功', url('index'));
        } else {
            $this->error('操作失败');
        }
    }

    /**
     * 修改个人信息
     */
    public function public_edit_info() {
        $data = input();
        if (isset($data['dosubmit'])) {
            if (!$data['password']) {
                unset($data['password']);
            } else {
                if ($data['password'] != $data['rpassword']) {
                    $this->error('两次密码不一致!');
                }
                $data['password'] = md5($data['password']);
            }

            $res = Loader::model('Admin')->editInfo(2, $this->user_id, $data);

            if ($res) {
                $this->success('修改成功');
            } else {
                $this->error('修改失败');
            }
        } else {
            $info = db('admin')->where('id', $this->user_id)->find();
            $this->assign('info', $info);
            return $this->fetch();
        }
    }

    //新闻列表
    public function newslist(){
       $newsList = db('news_list')->select();
       $this->assign('newslist', $newsList);
       return $this->fetch();
    }

    //删除新闻
    public function delnews(){
        $id = input('id');
        $res = db('news_list')->where(['id' => $id])->delete();
        if ($res) {
            $this->success('删除成功', url('newslist'),'',1);
        } else {
            $this->error('删除失败',null,'',1);
        }
    }

    //新闻详情
    public function newsdetail(){
        $id = input('id');
        if(!empty($id)){
            $news = db('news_list')->find($id);
            $this->assign('news',$news);
            $this->assign('title','编辑新闻');
        }else{
            $this->assign('title','发布新闻');
        }
        return $this->fetch();
    }

    //新闻保存
    public function newssave(){
        $id = input('id');
        $data = [];
        $data['sTitle'] = input('title');
        $data['sContent'] = input('content');
        $data['iType'] = input('cover_select');
        $data['sImgUrl'] = $this->uploadFile();
        $data['iAddTime'] = time();
        if(!empty($id)){
            $res = db('news_list')->where(['id'=>$id])->update($data);
        }else{
            $res = db('news_list')->insert($data);
        }
        if ($res) {
            $this->success('修改成功', url('newslist'),'',1);
        } else {
            $this->error('修改失败',null,'',1);
        }
    }
    
    public function uploadFile(){
        $file = request()->file('image');
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                // 成功上传后 获取上传信息
                $filePath = $info->getSaveName(); 
            }else{
            }
        }
        $filePath = './uploads/'.str_replace('\\','/',$filePath);
        return $filePath;
    }
}
