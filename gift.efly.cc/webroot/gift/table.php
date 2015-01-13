<!DOCTYPE HTML>
<?php 

	session_start(); 
	
	if(empty($_SESSION['user'])){
		echo '<script type="text/javascript">window.location.href = "./index.html";</script>';
	}
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<style type="text/css">
	a:link {text-decoration:none;} 
	a:visited {text-decoration:none;}
	a:hover{text-decoration:none;}
	a:active {text-decoration:none;} 

	#regist-process { position:relative;margin:0 auto;width:780px;margin-top:18px;font-size:14px;color:#999; height: 80px;}
	#regist-process-bar { width:780px;height:36px;background:url(images/regist-process.png) no-repeat; }
	#regist-process-step1 { position:absolute;top:46px;left:120px; }
	#regist-process-step2 { position:absolute;top:46px;left:365px; }
	#regist-process-step3 { position:absolute;top:46px;left:610px; }
	.step2 #regist-process-bar { background-position:0 -36px; }
	.step3 #regist-process-bar { background-position:0 -72px; }
	.step1 #regist-process-step1,
	.step2 #regist-process-step2 { color:#626262; }
	
	.table{font-size:14px; color:#333; width:800px; margin:0 auto;}
	.table span{ margin-left:50px;}
	.table table{ border:1px solid #ccc; color:#333;border-collapse: collapse;border: none; margin:40px 0px 0px 50px;}
	.table table td, .table table th{border: solid #ccc 1px; width:200px; padding-left:20px; height:30px; text-align:left;}
	.btn{ width:780px; margin:0 auto; bottom: 18px; position:absolute;}
	.btn a{width:160px; text-align:center; height:40px; display:block; float:right; background:#f8d650;color:#fff; margin-right:80px; font-size:24px; line-height:40px;}
	.btn a:hover{ background:#f6c64d}
	#item_1{ margin-top:20px;}
	#item_1 p{ margin:5px 0px;}
	#item_2{ overflow-y:scroll; border:1px solid #9d9d9d; height:316px;}
	#item_2, #item_3{ display:none; width:760px; margin:0 auto;}
	#item_2 h3{ text-align:center}
	#item_2 p{text-indent: 2em; margin:5px 0px; font-size:12px;}
	#item_3 label{width:106px;height: 34px;display: inline-block;}
	#item_3 input{width:220px;height:34px; line-height:26px; padding-left:8px; border:1px solid #ccc; font-size:14px;color:#666}
	#item_3 input:focus, #item_3 select:focus{ border-color: #f8d650;outline: 0;}
	#item_3 span{color: #FF8888;}
	#item_3 p{*margin:5px 0px;}
	#item_3 select{ width:130px;height:30px; line-height:30px; background:#fff; border:1px solid #ccc; color:#666}
</style>
<script type="text/javascript" src="js/jquery-1.8.1.min.js"></script>
<script type="text/javascript" src="js/layer/layer.min.js"></script>
<script type="text/javascript" >
	function next(str){
		if(str=='item_2'){
			$("#regist-process").attr('class','step2');
		}else if(str=='item_3'){
			if($("#item_2 :checkbox").attr('checked')!="checked"){
				layer.msg('需同意本协议',3,5);
				return;
			}
			$("#regist-process").attr('class','step3');
		}else if(str=='ok'){
			var ReceiverName = $("#ReceiverName").val(),
				CompanyName = $("#CompanyName").val(),
				ReceiverMail = $("#ReceiverMail").val(),
				ReceiverPhone = $("#ReceiverPhone").val(),
				ReceiverAdd = $("#ReceiverAdd").val(),
				SelProvince = $("#SelProvince").val(),
				SelCity = $("#SelCity").val(),
				SelArea = $("#SelArea").val(),Add_str = "";
			if(!ReceiverName){
				$("#ReceiverName").focus();
				return ;
			}
			if(!CompanyName){
				$("#CompanyName").focus();
				return ;
			}
			if(!ReceiverPhone){
				$("#ReceiverPhone").focus();
				return ;
			}
			if(!isPhone(ReceiverPhone)){
				$("#ReceiverPhone").focus();
				layer.msg('手机格式输入有误',2,5);
				return ;
			}
			if(!ReceiverMail){
				$("#ReceiverMail").focus();
				return ;
			}
			if(!isEmail(ReceiverMail)){
				$("#ReceiverMail").focus();
				layer.msg('邮箱格式输入有误',2,5);
				return ;
			}
			if(SelProvince == "请选择" || SelCity == "请选择"){
				$("#SelProvince").focus();
				layer.msg('请选择省份城市',2,5);
				return ;
			}
			if(!ReceiverAdd){
				$("#ReceiverAdd").focus();
				return ;
			}
			if(SelProvince != "请选择" && SelCity != "请选择"){
				if(SelProvince == SelCity){
					Add_str += SelProvince ;
				}else{
					Add_str += SelProvince + SelCity ;
				}
			}
			if(SelArea != "请选择"){
				Add_str += SelArea;
			}
			//通过验证
			$.ajax({
				url:'receive.php?opt=insert',
				type:'post',
				data:{
						'ID':$("#ID").val(),
						'ReceiverName':ReceiverName,
						'CompanyName':CompanyName,
						'ReceiverPhone':ReceiverPhone,
						'ReceiverMail':ReceiverMail,
						'ReceiverAdd':Add_str + ReceiverAdd
				},
				dataType: "json", 
				success:function(data){
					if(data.info=="success"){						
						layer.msg('礼品领取完毕，耐心等待快递。',2,1);
						setInterval(function(){window.parent.location.reload();},2000);						
					}else{
						layer.msg(data.data,3,5);
						setInterval(function(){window.location.reload();},2000);
					}
				},
				error:function(data){				
					layer.msg(data.statusText,3,5);
				}
			});
		}
		$(".table #item_1,.table #item_2,.table #item_3").hide();
		$("#"+str).show();
	}
	
	function GetQueryString(name){
		 var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
		 var r = window.location.search.substr(1).match(reg);
		 if(r!=null)return  unescape(r[2]); return null;
	}
	$(function(){
		$("#ID").val(GetQueryString('ID'));
		$.ajax({
			url: "./xml/area.xml",
			dataType: "xml",
			success: function (xml) {
				$(xml).find("province").each(function () {                                                  //找到(province)省份节点;
					$("<option></option>").html($(this).attr("name")).appendTo("#SelProvince");             //加载(province)省份信息到列表中
				})
			}
		})
		//省份列表信息更改时，加载城市列表信息
		$("#SelProvince").change(function () {
		var value = $("#SelProvince").val();                                                            //省份值;
		if (value != "请选择") {
			$("#SelCity").css("display", "inline-block").find("option").remove();                              //显示城市下拉列表框删除城市下拉列表中的数据;
			$("#SelCity").html("<option>请选择</option>");                                              //加载城市列表中的请选择;
			$("#SelArea").find("option").remove();                                                      //删除地区下拉列表中的数据;
			$("#SelArea").html("<option>请选择</option>")                                               //加载地区列表中的请选择;
			$.ajax({
				url: "./xml/area.xml",
				dataType: "xml",
				success: function (xml) {
					$(xml).find("[name='" + value + "']").find("city").each(function () {               //根据省份name属性得到子节点City节点name属性;
						$("<option></option>").html($(this).attr("name")).appendTo("#SelCity");         //加载City(城市)信息到下拉列表中;
					})
				}
			})
		}
		})
		//城市列表信息改变时，加载地区列表信息
		$("#SelCity").change(function () {
			var value = $("#SelCity").val();                                                                //城市值;
			if (value != "请选择") {
				$("#SelArea").css("display", "inline-block").find("option").remove();                              //显示地区下拉列表框删除地区下拉列表中的数据;
				$("#SelArea").html("<option>请选择</option>");                                              //加载地区列表中的请选择;
				$.ajax({
					url: "./xml/area.xml",
					dataType: "xml",
					success: function (xml) {
						$(xml).find("[name='" + value + "']").find("country").each(function () {            //根据城市节点name得到子节点Area节点name属性;
							$("<option></option>").html($(this).attr("name")).appendTo("#SelArea");         //加载到Area(地区)下拉列表中;
						})
					}
				})
			}
		})
	});
	function isEmail(str){
        var myReg = /^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/;
        if(myReg.test(str)) return true; 
        return false; 
    }
	function isPhone(str){
        var myReg = /^1\d{10}$/;
        if(myReg.test(str)) return true; 
        return false; 
    }
	
</script>

</head>
<body>
	<div id="regist-process" class="step1">
        <div id="regist-process-bar"></div>
        <div id="regist-process-step1">礼品信息</div>
        <div id="regist-process-step2">安全协议</div>
        <div id="regist-process-step3">领取礼品</div>
    </div>
    <div class="table">
    	<div id="item_1">
            <p><span>VPN盒子：活动价：免费使用一年</span></p>
            <p><span>标准价：600元/M/月；超值套餐：1M国际带宽，年付3000元</span></p>
            <p><span>活动优惠价：400元/M月</span></p>
            <p><span>恭喜您获得睿江"国际互联盒子"礼品一份</span></p>
            <table>
                <tr>
                    <th>名称</th>
                    <th>数量</th>
                    <th>说明</th>
                </tr>
                <tr>
                    <td>睿江国际互联盒子</td>
                    <td>x 1</td>
                    <td>国际互联盒子</td>
                </tr>
                <tr>
                    <td>电源适配器</td>
                    <td>x 1</td>
                    <td>国际互联盒子的电源适配器</td>
                </tr>
                <tr>
                    <td>使用说明书</td>
                    <td>x 1</td>
                    <td>国际互联盒子使用说明书</td>
                </tr>
                <tr>
                    <td>新年贺卡</td>
                    <td>x 1</td>
                    <td>祝您新年快乐</td>
                </tr>
            </table>
            <div class="btn">
                <a href="javascript:void(0);" onClick="next('item_2')">下一步</a>
            </div>
		</div>
        <div id="item_2">
        	<div style="width:730px; margin:0 auto;margin-top: 10px;">
                <h3>EFLY  NETWORK网络优化服务</h3>
                <h3>网络信息安全承诺书</h3>
                <p>EFLY NETWORK LIMITED：</p>
                <p>关于睿江科技的网络优化服务,本单位（个人）郑重承诺遵守本承诺书的有关条款，如有违反本承诺书有关条款的行为，由本单（个人）位承担由此带来的一切民事、行政和刑事责任。</p>
                <p>一、本单位（个人）承诺遵守《中华人民共和国计算机信息系统安全保护条例》和《计算机信息网络国际联网安全保护管理办法》及其他国家有关法律、法规和行政规章制度。</p>
                <p>二、本单位（个人）已知悉并承诺遵守《电信业务经营许可管理办法》、《互联网IP地址备案管理办法》、《非经营性互联网信息服务备案管理办法》、等国家相关部门有关文件的规定。</p>
                <p>三、本单位（个人）保证不通过使用睿江科技的网络优化服务设备或带宽危害国家安全、泄露国家秘密，不侵犯国家的、社会的、集体的利益和第三方的合法权益，不从事违法犯罪活动。</p>
                <p>四、本单位（个人）承诺不通过使用睿江科技的网络优化服务设备或带宽制作、复制、查阅和传播下列信息：</p>
                <p>1、反对宪法所确定的基本原则的；</p>
                <p>2、危害国家安全，泄露国家秘密，颠覆国家政权，破坏国家统一的；</p>
                <p>3、损害国家荣誉和利益的；</p>
                <p>4、煽动民族仇恨、民族歧视，破坏民族团结的；</p>
                <p>5、破坏国家宗教政策，宣扬邪教和封建迷信的；</p>
                <p>6、散布谣言，扰乱社会秩序，破坏社会稳定的；</p>
                <p>7、散布淫秽、色情、赌博、暴力、凶杀、恐怖或者教唆犯罪的；</p>
                <p>8、侮辱或者诽谤他人，侵害他人合法权益的；</p>
                <p>9、含有法律、行政法规禁止的其他内容的。</p>
                <p>五、本单位（个人）承诺不通过使用睿江科技的网络优化服务设备或带宽从事下列危害计算机信息网络安全的活动：</p>
                <p>1、未经允许，进入计算机信息网络或者使用计算机信息网络资源的；</p>
                <p>2、未经允许，对计算机信息网络功能进行删除、修改或者增加的；</p>
                <p>3、未经允许，对计算机信息网络中存储或者传输的数据和应用程序进行删除、修改或者增加的；</p>
                <p>4、故意制作、传播计算机病毒等破坏性程序的；</p>
                <p>5、其他危害计算机信息网络安全的。</p>
                <p>六、若违反本承诺书有关条款和国家相关法律法规的，本单位（个人）直接承担相应法律责任，造成财产损失的，由本单位（个人）直接赔偿。你单位有权停止服务。</p>
                <p>七、本承诺书自签署之日起生效。</p>
                <p><input type="checkbox" style="margin:20px 0px 0px 300px;" checked/><span style="margin-left:10px;">我同意本协议</span></p>
            </div>            
            <div class="btn">
                <a href="javascript:void(0);" onClick="next('item_3')">下一步</a>
            </div>
        </div>
        <div id="item_3">        	
        	<div style="width: 530px;margin: 0 auto;margin-top: 20px;">
                <form action="" method="post">
                    <p>
                        <input type="hidden" id="ID"/>
                        <label>联系人姓名：</label>
                        <input id="ReceiverName" tabindex="1" />
                        <span>* 请输入联系人姓名</span>
                    </p>
                    <p>
                        <label>单位名称：</label>
                        <input id="CompanyName" tabindex="2"/>
                        <span>* 请输入单位名称</span>
                    </p>
                    <p>
                        <label>手机号码：</label>
                        <input id="ReceiverPhone" tabindex="3"/>
                        <span>* 请输入手机号码</span>
                    </p>
                    <p>
                        <label>邮箱地址：</label>
                        <input id="ReceiverMail" tabindex="4"/>
                        <span>* 请输入邮箱地址</span>
                    </p>
                    <p>
                        <label>收货地址：</label>
                        <select id="SelProvince" tabindex="5">
                            <option>请选择</option>
                        </select>
                        <select id="SelCity" tabindex="6">
                            <option>请选择</option>
                        </select>
                        <select id="SelArea" tabindex="7">
                            <option>请选择</option>
                        </select>
                    </p>
                    <p>
                        <input style="margin-left:110px;"  id="ReceiverAdd" tabindex="8"/>
                        <span>* 请输入详细地址</span>
                    </p>
             	</form>
           	</div>        
            <div class="btn">
                <a href="javascript:void(0);" onClick="next('ok')">完成</a>
            </div>
        </div>
    </div>
    
</body>
</html>