var mobile,link;
var htmls;
link= 'http://a.lvmaque.net/admin.php/agents/manager/getLikeMobile';

$(document).ready(function(){
    $(".form-builder").keydown(function(e){
        var curKey = e.which;
        if(curKey == 13){
            //$("#回车事件按钮控件").click();
            return false;
        }
    });
});

$(function(){
   $("#mobile").on('input',function(){
		mobile = $("#mobile").val();
		 $.get(link,{'mobile':mobile},function(data){
			 if(mobile=='') $("#dowmList").hide();
			if(!data.length) $("#dowmList").hide();
			else{
				$("#dowmList").show();
					htmls = '';
				$.each(data,function(i,v){  
					htmls+="<li onclick='autoInput("+v.mobile+")'><a>"+v.mobile+"</a></li>"  
				}) 
				  $("#dowmList").html(htmls)  
			}
		});
	})
});
function autoInput(val){
	$("#mobile").val(val);
	 $("#dowmList").hide();
}
