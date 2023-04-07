<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:99:"D:\phpStudy\PHPTutorial\WWW\laravel\TP5Admin\public/../application/admin\view\admin\newsdetail.html";i:1680855372;s:79:"D:\phpStudy\PHPTutorial\WWW\laravel\TP5Admin\application\admin\view\layout.html";i:1680832365;}*/ ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta charset="utf-8" />
        <title>后台管理</title>

        <meta name="description" content="overview &amp; stats" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

        <!-- bootstrap & fontawesome -->
        <link rel="stylesheet" href="<?php echo config('public.static'); ?>/ace1.4/assets/css/bootstrap.min.css" />
        <link rel="stylesheet" href="<?php echo config('public.static'); ?>/ace1.4/assets/font-awesome/4.5.0/css/font-awesome.min.css" />

        <!-- page specific plugin styles -->

        <!-- text fonts -->
        <link rel="stylesheet" href="<?php echo config('public.static'); ?>/ace1.4/assets/css/fonts.googleapis.com.css" />

        <!-- ace styles -->
        <link rel="stylesheet" href="<?php echo config('public.static'); ?>/ace1.4/assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />

        <!--[if lte IE 9]>
                <link rel="stylesheet" href="<?php echo config('public.static'); ?>/ace1.4/assets/css/ace-part2.min.css" class="ace-main-stylesheet" />
        <![endif]-->
        <link rel="stylesheet" href="<?php echo config('public.static'); ?>/ace1.4/assets/css/ace-skins.min.css" />
        <link rel="stylesheet" href="<?php echo config('public.static'); ?>/ace1.4/assets/css/ace-rtl.min.css" />

        <!--[if lte IE 9]>
          <link rel="stylesheet" href="<?php echo config('public.static'); ?>/ace1.4/assets/css/ace-ie.min.css" />
        <![endif]-->

        <!-- inline styles related to this page -->

        <!-- ace settings handler -->
        <script src="<?php echo config('public.static'); ?>/ace1.4/assets/js/ace-extra.min.js"></script>

        <!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

        <!--[if lte IE 8]>
        <script src="<?php echo config('public.static'); ?>/ace1.4/assets/js/html5shiv.min.js"></script>
        <script src="<?php echo config('public.static'); ?>/ace1.4/assets/js/respond.min.js"></script>
        <![endif]-->
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
    </head>

    <body class="no-skin">
        <div id="navbar" class="navbar navbar-default          ace-save-state">
            <div class="navbar-container ace-save-state" id="navbar-container">
                <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
                    <span class="sr-only">Toggle sidebar</span>

                    <span class="icon-bar"></span>

                    <span class="icon-bar"></span>

                    <span class="icon-bar"></span>
                </button>

                <div class="navbar-header pull-left">
                    <a href="<?php echo url('index/index'); ?>" class="navbar-brand">
                        <small>
                            <i class="fa fa-ra"></i>
                            后台管理
                        </small>
                    </a>
                </div>

                <div class="navbar-buttons navbar-header pull-right" role="navigation">
                    <ul class="nav ace-nav">
                   

                        <li class="light-blue dropdown-modal">
                            <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                                <img class="nav-user-photo" src="<?php echo config('public.static'); ?>/ace1.4/assets/images/avatars/user.jpg" alt="Jason's Photo" />
                                <span class="user-info">
                                    <small>Welcome,</small>
                                    <?php echo session('user_name'); ?>
                                </span>
                                <i class="ace-icon fa fa-caret-down"></i>
                            </a>

                            <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                                <li>
                                    <a href="<?php echo url('admin/public_edit_info'); ?>">
                                        <i class="ace-icon fa fa-user"></i>
                                        个人设置
                                    </a>
                                </li>

                                <li class="divider"></li>
                                <li>
                                    <a href="<?php echo url('login/logout'); ?>">
                                        <i class="ace-icon fa fa-power-off"></i>
                                        退出
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div><!-- /.navbar-container -->
        </div>

        <div class="main-container ace-save-state" id="main-container">
            <script type="text/javascript">
                try {
                    ace.settings.loadState('main-container')
                } catch (e) {
                }
            </script>

            <div id="sidebar" class="sidebar                  responsive                    ace-save-state">
                <script type="text/javascript">
                    try {
                        ace.settings.loadState('sidebar')
                    } catch (e) {
                    }
                </script>

               


                <?php echo action('Menu/index','','widget'); ?>

                <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
                    <i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
                </div>
            </div>

            <div class="main-content">
                <div class="main-content-inner">
                    <div class="page-content">
   
    <div class="page-header">
        <h1>
            新闻管理
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                编辑新闻
            </small>
        </h1>
    </div><!-- /.page-header -->

    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <form class="form-horizontal" role="form" action="" method="post" name="myfrom" >
                <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right"> 新闻标题 </label>

                    <div class="col-sm-9">
                        <input type="password" name="password" placeholder="Password" class="col-xs-10 col-sm-5" />
                        <span class="help-inline col-xs-12 col-sm-7">
                            <span class="middle"></span>
                        </span>
                    </div>
                </div>
                <div class="space-4"></div>

                <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right"> 新闻内容 </label>

                    <div class="col-sm-9">
                        <input type="password" name="rpassword" placeholder="Password" class="col-xs-10 col-sm-5" />
                        <span class="help-inline col-xs-12 col-sm-7">
                            <span class="middle"></span>
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right">邮箱</label>
                    <div class="col-sm-9">
                        <input type="text" class="col-xs-10 col-sm-5" name="email" value='<?php echo (isset($info['email']) && ($info['email'] !== '')?$info['email']:''); ?>'/>

                    </div>
                </div>    
                <div class="space-4"></div>
                <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right">手机号</label>
                    <div class="col-sm-9">
                        <input type="text" class="col-xs-10 col-sm-5" name="mobile"  value='<?php echo (isset($info['mobile']) && ($info['mobile'] !== '')?$info['mobile']:''); ?>'/>
                    </div>
                </div>    
                <div class="space-4"></div>
                <div class="space-4"></div>
                <input type="hidden" name="dosubmit" value="1">
                <div class="clearfix form-actions">
                    <div class="col-md-offset-3 col-md-9">
                        <button class="btn btn-info" type="button" onclick="myfrom.submit()">
                            <i class="ace-icon fa fa-check bigger-110"></i>
                            提交
                        </button>
                        &nbsp; &nbsp; &nbsp;
                        <button class="btn" type="reset">
                            <i class="ace-icon fa fa-undo bigger-110"></i>
                            重置
                        </button>
                    </div>
                </div>
            </form>
            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.page-content -->
                </div>
            </div><!-- /.main-content -->

            <div class="footer">
                <div class="footer-inner">
                    <div class="footer-content">
                        <span class="bigger-120">
                            <span class="blue bolder"><a href="https://github.com/duzhenxun/TP5Admin" target="_blank">TP5Admin</a></span>
                             &copy; 2016-2018
                        </span>

                        &nbsp; &nbsp;
                        <span class="action-buttons">
                            <a href="#">
                                <i class="ace-icon fa fa-twitter-square light-blue bigger-150"></i>
                            </a>

                            <a href="#">
                                <i class="ace-icon fa fa-facebook-square text-primary bigger-150"></i>
                            </a>

                            <a href="#">
                                <i class="ace-icon fa fa-rss-square orange bigger-150"></i>
                            </a>
                        </span>
                    </div>
                </div>
            </div>

            <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
                <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
            </a>
        </div><!-- /.main-container -->

        <!-- basic scripts -->

        <!--[if !IE]> -->
        <script src="<?php echo config('public.static'); ?>/ace1.4/assets/js/jquery-2.1.4.min.js"></script>

        <!-- <![endif]-->

        <!--[if IE]>
<script src="<?php echo config('public.static'); ?>/ace1.4/assets/js/jquery-1.11.3.min.js"></script>
<![endif]-->
        <script type="text/javascript">
                    if ('ontouchstart' in document.documentElement)
                        document.write("<script src='assets/js/jquery.mobile.custom.min.js'>" + "<" + "/script>");
        </script>
        <script src="<?php echo config('public.static'); ?>/ace1.4/assets/js/bootstrap.min.js"></script>

        <!-- page specific plugin scripts -->

        <!--[if lte IE 8]>
          <script src="<?php echo config('public.static'); ?>/ace1.4/assets/js/excanvas.min.js"></script>
        <![endif]-->
    

        <!-- ace scripts -->
        <script src="<?php echo config('public.static'); ?>/ace1.4/assets/js/ace-elements.min.js"></script>
        <script src="<?php echo config('public.static'); ?>/ace1.4/assets/js/ace.min.js"></script>

        <!-- inline scripts related to this page -->
        <link rel="stylesheet" href="<?php echo config('public.static'); ?>/artDialog/dialog.css" />
        <script src="<?php echo config('public.static'); ?>/artDialog/dialog.js"></script>
        <!--artDialog end-->
        
        
        <script>
                    var u = $(".active").parent('ul');
                    
                    var uc = u.attr("class");//
                   
                    if (uc == 'submenu') {
                       u.parent().attr("class", "open active");
                       if(u.parent().parent().attr('class')=='submenu'){
                           u.parent().parent().parent().attr("class","open active");
                       }
                    }

                    //弹出图片
                    function alert_img(url, width, heigth, title) {

                        art.dialog({
                            padding: 0,
                            title: title,
                            content: '<img src="' + url + '" width="' + width + '" height="' + heigth + '" />',
                            lock: true
                        });
                    }
                    //弹出确认操作
                    function alert_del(url, title) {
                        art.dialog({
                            lock: true,
                            background: '#300', // 背景色
                            opacity: 0.87, // 透明度
                            content: title,
                            ok: function () {
                                return window.location.href = url;
                            },
                            cancel: true
                        });
                    }
        </script>
    </body>
</html>
