<?php
namespace app\admin\controller;

use think\Loader;

class Tasklist extends Common {

    //任务列表
    public function list() {
        $list = db('task_list')->select();
        $statusArr = ['未完成','进行中','已完成'];
        foreach($list as &$l){
            $l['status_txt'] = $statusArr[$l['iStatus']];
        }
        $this->assign('task_list',$list);
        return $this->fetch();
    }

    //删除新闻
    public function deltask(){
        $id = input('id');
        $res = db('task_list')->where(['id' => $id])->delete();
        if ($res) {
            $this->success('删除成功', url('newslist'),'',1);
        } else {
            $this->error('删除失败',null,'',1);
        }
    }

    //新闻详情
    public function taskdetail(){
        $id = input('id');
        if(!empty($id)){
            $task = db('task_list')->find($id);
            $this->assign('task',$task);
            $this->assign('title','编辑任务');
        }
        $statusArr = ['未完成','进行中','已完成'];
        $this->assign('statusArr',$statusArr);
        return $this->fetch();
    }

    //任务保存
    public function tasksave(){
        $id = input('id');
        $data = [];
        $data['sTaskTitle'] = input('title');
        $data['sTaskContent'] = input('content');
        $data['sLocation'] = input('sLocation');
        $data['sContact'] = input('sContact');
        $data['iAddTime'] = time();
        if(!empty($id)){
            $res = db('task_list')->where(['id'=>$id])->update($data);
        }else{
            $res = db('task_list')->insert($data);
        }
        if ($res) {
            $data['iPublisher'] = 1;
            $this->success('修改成功', url('newslist'),'',1);
        } else {
            $this->error('修改失败',null,'',1);
        }
    }
}
