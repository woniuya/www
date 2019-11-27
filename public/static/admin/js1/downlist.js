var mobile,link;
var htmls;
var domain = window.location.host;
link= 'http://'+domain+'/admin.php/agents/manager/getLikeMobile';
$(function(){
   $("#mobile").on('input',function(){
		mobile = $("#mobile").val();
		 $.get(link,{'mobile':mobile},function(data){
			if(!data.length) $("#dowmList").hide();
			else{
				$("#dowmList").show();
					htmls = '';
				$.each(data,function(i,v){  
					htmls+="<div class='col-sm-12' style='background:#f5f5f5;'>"+v.mobile+"</div>"  
				}) 
				  $("#dowmList").html(htmls)  
			}
		});
	})
});