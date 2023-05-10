<?php
namespace app\api\controller;
use think\Db;

class Index
{
    public function index()
    {
        return 'index';
    }

    public function addField(){
        $sql = "ALTER TABLE `wechat_news_applet`.`user_list` 
        ADD COLUMN `nickname` varchar(255) NOT NULL DEFAULT '' COMMENT '昵称' AFTER `username`,
        ADD COLUMN `gender` varchar(255) NOT NULL DEFAULT '' COMMENT '性别' AFTER `nickname`";
        $list = Db::query($sql);
        echo('执行成功！');
    }
}
