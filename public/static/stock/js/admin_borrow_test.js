$(function(){

	var trNum = $('.js-table-checkable').find("tbody").children("tr");
	var lens  = trNum.length;
	var str = '';
	$.each(trNum,function(index,value){
		 
	     str += $.trim($('.js-table-checkable').find("tbody").children("tr").eq(index).find('td').eq(0).html());
		 if(index !== lens-1){
		 str +=','; 
		 }
	});
	var url = dolphin.stock_operation_js;
			  $.ajax({
                url: url,
                data: {
                   str:str
                },
                type: "post",
                dataType: "json",
                success: function (res) {
					//var res = eval(res); //数组     
					var tt = "";  
					$.each(res.data, function (index,value) {  
						//循环获取数据     
						//alert(value.losswarn);
						$('.js-table-checkable').find("tbody").children("tr").eq(index).find('td').eq(8).html(value.losswarn)
						$('.js-table-checkable').find("tbody").children("tr").eq(index).find('td').eq(9).html(value.lossclose)
					});
						
                }
            })
	  setInterval(function () {
		
			pos_oper(url);
	    }, 5000);
		
		function pos_oper(url){
			
				 $.ajax({
                url: url,
                data: {
                   str:str
                },
                type: "post",
                dataType: "json",
                success: function (res) {
					//var res = eval(res); //数组     
					var tt = "";  
					$.each(res.data, function(index,value) {  
						//循环获取数据     
						//alert(value['losswarn']);
						$('.js-table-checkable').find("tbody").children("tr").eq(index).find('td').eq(8).html(value.losswarn)
						$('.js-table-checkable').find("tbody").children("tr").eq(index).find('td').eq(9).html(value.lossclose)
					});
						
                }
            })	
			
		}
});
		