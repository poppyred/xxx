<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>云数据中心后台支撑</title>
	
	<link rel="stylesheet" href="__ROOT__/Public/css/main_css.css" type="text/css" />
	<link rel="stylesheet" href="__ROOT__/Public/css/zTreeStyle.css" type="text/css" />
	<script type="text/javascript" src="__ROOT__/Public/js/jquery/jquery-1.7.1.js"></script>
	<script type="text/javascript" src="__ROOT__/Public/js/zTree/jquery.ztree.core-3.2.js"></script>
	<script type="text/javascript" src="__ROOT__/Public/js/authority/commonAll.js"></script>

	<script type="text/javascript" src="__ROOT__/Public/js/fancyBox-2.1.5/jquery.fancybox.js?v=2.1.5"></script>
	<link rel="stylesheet" type="text/css" href="__ROOT__/Public/js/fancyBox-2.1.5/jquery.fancybox.css?v=2.1.5" media="screen" />

	<script type="text/javascript">
		/**退出系统**/
		function logout(){
			if(confirm("您确定要退出本系统吗？")){
				window.location.href = "__APP__/Index/logout";
			}
		}

		/**获得当前日期时间**/
		function  getDate01(){
			/*var time = new Date();
			var myYear = time.getFullYear();
			var myMonth = time.getMonth()+1;
			var myDay = time.getDate();
			if(myMonth < 10){
				myMonth = "0" + myMonth;
			}
			document.getElementById("yue_fen").innerHTML =  myYear + "." + myMonth;
			document.getElementById("day_day").innerHTML =  myYear + "." + myMonth + "." + myDay;*/

			var time = new Date();
			document.getElementById("yue_fen").innerHTML =  time.Format('yyyy-MM-dd');
			document.getElementById("day_day").innerHTML =  time.Format('hh:mm:ss');

			setInterval(function(){
				document.getElementById("day_day").innerHTML =  new Date().Format('hh:mm:ss');
			}, 1000);
		}

	</script>
	<script type="text/javascript">
		/* zTree插件加载目录的处理  */
		var zTree;
		var setting = {
			view: {
				dblClickExpand: false,
				showLine: false,
				expandSpeed: ($.browser.msie && parseInt($.browser.version)<=6)?"":"fast"
			},
			data: {
				key: {
					name: "resourceName"
				},
				simpleData: {
					enable: true,
					idKey: "resourceID",
					pIdKey: "parentID",
					rootPId: ""
				}
			},
			callback: {
				// beforeExpand: beforeExpand,
				// onExpand: onExpand,
				onClick: zTreeOnClick			
			}
		};
		
		/** 用于捕获节点被点击的事件回调函数  **/
		function zTreeOnClick(event, treeId, treeNode) {
			var zTree = $.fn.zTree.getZTreeObj("dleft_tab1");
			zTree.expandNode(treeNode, null, null, null, true);
	// 		zTree.expandNode(treeNode);
			// 规定：如果是父类节点，不允许单击操作
			if(treeNode.isParent){
	// 			alert("父类节点无法点击哦...");
				return false;
			}
			// 如果节点路径为空或者为"#"，不允许单击操作
			if(treeNode.accessPath=="" || treeNode.accessPath=="#"){
				//alert("节点路径为空或者为'#'哦...");
				return false;
			}
		    // 跳到该节点下对应的路径, 把当前资源ID(resourceID)传到后台，写进Session
		    rightMain(treeNode.accessPath);

		    $('#here_area').html('当前位置：系统&nbsp;>&nbsp;<span style="color:#1A5CC6;cursor:pointer;" \
		    	onclick="rightMain(\'' + treeNode.accessPath + '\');">'+treeNode.resourceName+'</span>');
		    
		    /*if( treeNode.isParent ){
			    $('#here_area').html('当前位置：'+treeNode.getParentNode().resourceName+'&nbsp;>&nbsp;<span style="color:#1A5CC6">'+treeNode.resourceName+'</span>');
		    }else{
		    	$('#here_area').html('当前位置：系统&nbsp;>&nbsp;<span style="color:#1A5CC6">'+treeNode.resourceName+'</span>');
		    }*/
		};
		
		data1 = [
			{"accessPath":"__APP__/Template/add","parentID":0,"resourceID":3,"resourceName":"添加模板"},
			{"accessPath":"__APP__/Group/add","parentID":0,"resourceID":9,"resourceName":"添加池组"},
			{"accessPath":"__APP__/Pool/add","parentID":0,"resourceID":1,"resourceName":"添加池"},
			{"accessPath":"__APP__/Host/add","parentID":0,"resourceID":5,"resourceName":"添加主机"},
			{"accessPath":"__APP__/VM/add","parentID":0,"resourceID":7,"resourceName":"添加VM"},
			{"accessPath":"__APP__/Template/tem_list","parentID":0,"resourceID":4,"resourceName":"模板查询&操作"},
			{"accessPath":"__APP__/Group/group_list","parentID":0,"resourceID":10,"resourceName":"池组查询&操作"},
			{"accessPath":"__APP__/Pool/pool_list","parentID":0,"resourceID":2,"resourceName":"池查询&操作"},
			{"accessPath":"__APP__/Host/host_list","parentID":0,"resourceID":6,"resourceName":"主机查询&操作"},
			{"accessPath":"__APP__/VM/vm_list","parentID":0,"resourceID":8,"resourceName":"VM查询&操作"}
		];

		data2 = [
			{"accessPath":"__APP__/Event/add","parentID":0,"resourceID":1,"resourceName":"添加事件"},
			{"accessPath":"__APP__/EventTrigger/add","parentID":0,"resourceID":2,"resourceName":"添加触发器"},
			{"accessPath":"__APP__/Event/event_list","parentID":0,"resourceID":3,"resourceName":"事件查询&操作"},
			{"accessPath":"__APP__/EventTrigger/et_list","parentID":0,"resourceID":4,"resourceName":"触发查询&操作"}
		];

		data3 = [
			{"accessPath":"","parentID":0,"resourceID":1,"resourceName":"添加产品"},
			{"accessPath":"","parentID":0,"resourceID":2,"resourceName":"产品查询&操作"}
		];

		/* 上方菜单 */
		function switchTab(tabpage,tabid){
			var oItem = document.getElementById(tabpage).getElementsByTagName("li"); 
		    for(var i=0; i<oItem.length; i++){
		        var x = oItem[i];
		        x.className = "";
			}

			if('left_tab1' == tabid){
				// 加载"业务模块"下的菜单
			  	loadMenu('dleft_tab1', data1);
			}else  if('left_tab2' == tabid){
				// 加载"系统管理"下的菜单
				loadMenu('dleft_tab1', data2);
			}else  if('left_tab3' == tabid){
				// 加载"其他"下的菜单
				loadMenu('dleft_tab1', data3);
			}
		}

		function loadMenu(treeObj, data){
		    // 如果返回数据不为空，加载"业务模块"目录
		    if(data != null){
				// 将返回的数据赋给zTree
				$.fn.zTree.init($("#"+treeObj), setting, data);
				// alert(treeObj);
				zTree = $.fn.zTree.getZTreeObj(treeObj);
				if( zTree ){
				    // 默认展开所有节点
				    zTree.expandAll(true);
				}
		    }
		}
		
	</script>

	<script type="text/javascript">
		$(function(){
			$('#TabPage2 li').click(function(){
				switchTab('TabPage2', this.id);

				$('#TabPage2 li').each(function(i, ele){
					$(ele).find('img').attr('src', '__ROOT__/Public/images/common/'+ (i + 1) +'.jpg');
					$(ele).css({background:'#044599'});
				});

				var index = $(this).index();
				$(this).find('img').attr('src', '__ROOT__/Public/images/common/'+ (index + 1) +'_hover.jpg');
				$(this).css({background:'#fff'});

				var resourceID = 0;//代表默认点击当前菜单哪个项目
				if(index == 0){
					$('#nav_module').html("云管理 / Cloud");
					resourceID = 2;
				}else if(index == 1){
					$('#nav_module').html("事件管理 / Event");
				}else if(index == 2){
					$('#nav_module').html("报表中心 / Report");
				}

				// 显示侧边栏
				switchSysBar(true);
				
				var node = zTree.getNodeByParam("resourceID", resourceID, null);//获取节点
				zTree.selectNode(node);//选择节点
				zTree.setting.callback.onClick(null, zTree.setting.treeId, node);//调用节点事件
			});
			
			// 显示隐藏侧边栏
			$("#show_hide_btn").click(function() {
		        switchSysBar($('#left_menu_cnt').is(':hidden'));
		    });
		});

		
		/**隐藏或者显示侧边栏**/
		function switchSysBar(flag){
			var side = $('#side');
			var left_menu_cnt = $('#left_menu_cnt');

			left_menu_cnt.stop(true, true);
			side.stop(true, true);
			$("#main").stop(true, true);
			$("#top_nav").stop(true, true);

			if ( flag ) {
				//show
				var time = 300;

				$('#main').animate({left:'280px'}, time);
				$('#top_nav').animate({width:'82%', left:'304px', 'padding-left':'0px'}, time);
				left_menu_cnt.show(time, 'linear');
				side.animate({width:'280px'}, time);

				$("#show_hide_btn").find('img').attr('src', '__ROOT__/Public/images/common/nav_hide.png');
			} else {
				//hide
				var time = 300;

				$('#main').animate({left:'90px'}, time);
				$('#top_nav').animate({width:'100%', left:'90px', 'padding-left':'28px'}, time);
				left_menu_cnt.hide(time, 'linear');
				side.animate({width:'60px'}, time);
				
				$("#show_hide_btn").find('img').attr('src', '__ROOT__/Public/images/common/nav_show.png');
			}
		}


		$(document).ready(function(){
 			//系统日期时间
			getDate01();

			//默认点击第一个
			$('#TabPage2 li:first').click();
		});

	</script>

</head>
<body>
    <div id="top">
		<div id="top_logo">
			<h2>云数据中心后台支撑</h2><!--img alt="logo" src="__ROOT__/Public/images/common/logo.jpg" width="274" height="49" style="vertical-align:middle;"-->
		</div>
		<div id="top_links">
			<div id="top_op">
				<ul>
					<li>
						<img alt="当前用户" src="__ROOT__/Public/images/common/user.jpg" />：
						<span><?php echo ($_SESSION['user']['username']); ?></span>
					</li>
					<li>
						<img alt="事务月份" src="__ROOT__/Public/images/common/month.jpg" />：
						<span id="yue_fen"></span>
					</li>
					<li>
						<img alt="今天是" src="__ROOT__/Public/images/common/date.jpg" />：
						<span id="day_day"></span>
					</li>
				</ul> 
			</div>
			<div id="top_close" onclick="logout();">
				<img alt="退出系统" title="退出系统" src="__ROOT__/Public/images/common/close.jpg" style="position: relative; top: 13px; left: 30px;">
			</div>
		</div>
	</div>
    <!-- side menu start -->
	<div id="side">
		<div id="left_menu">
		 	<ul id="TabPage2" style="height:200px; margin-top:50px;">
				<li id="left_tab1" title="产品管理">
					<img alt="产品管理" title="产品管理" src="__ROOT__/Public/images/common/1.jpg" width="33" height="31">
				</li>
				<li id="left_tab2" title="事件管理">
					<img alt="事件管理" title="事件管理" src="__ROOT__/Public/images/common/2.jpg" width="33" height="31">
				</li>
				<li id="left_tab3" title="报表中心">
					<img alt="报表中心" title="报表中心" src="__ROOT__/Public/images/common/3.jpg" width="33" height="31">
				</li>
			</ul>
			
			<div id="nav_show" style="position:absolute; bottom:0px; padding:10px;">
				<a href="javascript:;" id="show_hide_btn">
					<img alt="显示/隐藏" title="显示/隐藏" src="__ROOT__/Public/images/common/nav_hide.png" width="35" height="35">
				</a>
			</div>
		 </div>
		 <div id="left_menu_cnt">
		 	<div id="nav_module">
		 		首页 / Index
		 		<!--img src="__ROOT__/Public/images/common/module_1.png" width="210" height="58"/-->
		 	</div>
		 	<div id="nav_resource">
		 		<ul id="dleft_tab1" class="ztree"></ul>
		 	</div>
		 </div>
	</div>
	
    <!-- side menu start -->
    <div id="top_nav">
	 	<span id="here_area">当前位置：系统&nbsp;>&nbsp;<span style="color:#1A5CC6;">首页</span></span>
	</div>
    <div id="main">
      	<iframe name="right" id="rightMain" src="" frameborder="no" scrolling="auto" width="100%" height="100%" allowtransparency="true" />
    </div>
</body>
</html>