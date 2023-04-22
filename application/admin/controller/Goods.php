<?php
namespace app\admin\controller;

use think\Loader;

class Goods extends Common {

    public function index() {
        $res = db('categorys')->select();
        $lists = nodeTree($res);
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
            $info = db('categorys')->find($id);
            $this->assign('info', $info);
        }
        $map = [];
        //下拉菜单
        $res = db('categorys')
        ->field('id,sName,parentid')
        ->where(['parentid'=>['EQ',0]])
        ->select();
        $tmpArr = nodeTree($res);

        $data = array();
        foreach ($tmpArr as $k => $v) {
            $name = $v['level'] == 0 ? '<b>' . $v['sName'] . '</b>' : '├─' . $v['sName'];

            $name = str_repeat("│        ", $v['level']) . $name;
            $data[$v['id']] = $name;
        }
        $this->assign('selectMenu', $data);
       
        return $this->fetch();
    }

    /*
     * 添加
     */

    public function add() {
        
        $data = input();
        if ($data['parentid'] == null) {
            $data['parentid'] = 0;
        }
        $data['sImgUrl'] = $this->uploadFile();
        $res = db('categorys')->insert($data);
        if ($res) {
            $this->success('操作成功', url('index'),'',1);
        } else {
            $this->error('操作失败',null,'',1);
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
    /*
     * 修改
     */
    public function edit() {

        $data = input();
        if ($data['parentid'] == null) {
            $data['parentid'] = 0;
        }
        $data['sImgUrl'] = $this->uploadFile();
        $res = db('categorys')->where( ['id' => $data['id']])->update($data);
        if ($res) {
            $this->success('操作成功', url('index'),'',1);
        } else {
            $this->error('操作失败',null,'',1);
        }
    }

    /*
     * 删除
     */

    public function del() {
        $id = input('id');
        $res = db('categorys')->where(['id' => $id])->delete();
        if ($res) {
            $this->success('操作成功', url('index'),'',1);
        } else {
            $this->error('操作失败',null,'',1);
        }
    }

    public function goodslist(){
        $goodslist = db('goods_list a')
        ->field('a.*,c.sName,u.username')
        ->join('categorys c','a.pid=c.id')
        ->join('user_list u','a.uid=u.id')
        ->select();
        foreach($goodslist as &$goods){
            $goods['date'] = date("Y-m-d H:i",$goods['iAddTime']);
        }
        $this->assign('goodslist',$goodslist);
        return $this->fetch();
    }

    public function goodsdel() {
        $id = input('id');
        $res = db('goods_list')->where(['id' => $id])->delete();
        if ($res) {
            $this->success('操作成功', url('goodslist'),'',1);
        } else {
            $this->error('操作失败',null,'',1);
        }
    }
}
