//分页的显示与隐藏
function xifenye(a){
	$("#uljia").empty();
	$("#xab").toggle();
	//显示出的一共多少页
	var uljia=$("#uljia");
	var page=parseInt($("#xiye").html());//获取当前的页数
	var pages=parseInt($("#mo").html());//获取当前的总页数
	for(var i=1;i<=pages;i++)
	{
		var H="<li  onClick='fl("+i+","+pages+")'>"+i+"</li>";//添加一共多少页和点击事件
		
		uljia.append(H);
	}
	scrolltop(page);
}
//点击分页显示的方法
function fl(p1,p2){
	//var p=p1;
	$("#xiye").empty();
	$("#xiye").html(p1);//给显示的页数赋值
	selectByPage(p1);
}
//分页的的上一页和下一页
function topclick(){
	var v=document.getElementById("xiye");
	var num=v.innerHTML;
	if(num>1){
		num--;
		v.innerHTML=num;
		var hei=25*num-25;
		$("#xab").scrollTop(hei);
		selectByPage(num);
	}
}
function downclick(){
	var pages=parseInt($("#mo").html());//获取当前的总页数
	var v=$("#xiye");
	var num=parseInt(v.html());
	if(num < pages){
		num = ++num;
		v.html(num);
		scrolltop(num);
		selectByPage(num);
	}
}
function selectByPage(page){
	var url = window.location.href;	
	switch(true){
		case url.indexOf('orderMgr')!=-1 :
		window.location.href = APP + "/Index/orderMgr?p=" + page;
		break;
		case url.indexOf('consumption')!=-1 :
		window.location.href = APP + "/Index/consumption?p=" + page;
		break;		
		case url.indexOf('msgMgr')!=-1 :
			if(url.getQuery("type")!=null){
				window.location.href = APP + "/Index/msgMgr?type=" + url.getQuery("type") + "&p=" + page;
			}else{
				window.location.href = APP + "/Index/msgMgr?p=" + page;	
			}
		break;
	}

}
//分页的的首页和未页
function firstclick(){
	var v=document.getElementById("xiye");
	var num=v.innerHTML;
	if(num>1){
		var v=document.getElementById("xiye");
		v.innerHTML=1;
		scrolltop(v.innerHTML);
		selectByPage(1);
	}
}
function lastclick(){
	var pages=parseInt($("#mo").html());
	var x=$("#xiye");
	var num=parseInt(x.html());
	if(num < pages){
		var v=document.getElementById("xiye");
		var l=document.getElementById("mo");
		v.innerHTML=l.innerHTML;
		scrolltop(v.innerHTML);
		selectByPage($("#mo").html());
	}
}
//滚动条
function scrolltop(top){
	var hei=25*top-25;
	$("#xab").scrollTop(hei);
}
