<?php
namespace app\admin\controller;

use think\Loader;

class User extends Common {

    //任务列表
    public function list() {
        $list = db('user_list')->select();
        // foreach($list as &$l){
        //     $l['status_txt'] = $statusArr[$l['iStatus']];
        // }
        $this->assign('user_list',$list);
        return $this->fetch();
    }

    //删除新闻
    public function deluser(){
        $id = input('id');
        $res = db('user_list')->where(['id' => $id])->delete();
        if ($res) {
            $this->success('删除成功', url('list'),'',1);
        } else {
            $this->error('删除失败',null,'',1);
        }
    }

    //新闻详情
    public function userdetail(){
        $id = input('id');
        if(!empty($id)){
            $user = db('user_list')->find($id);
            $this->assign('user',$user);
            $this->assign('title','编辑用户');
        }
        return $this->fetch();
    }

    //新闻保存
    public function usersave(){
        $id = input('id');
        $data = [];
        $data['username'] = input('username');
        $data['iIntegral'] = input('iIntegral');
        if(!empty($id)){
            $res = db('user_list')->where(['id'=>$id])->update($data);
        }
        if ($res) {
            $this->success('修改成功', url('list'),'',1);
        } else {
            $this->error('修改失败',null,'',1);
        }
    }
}
