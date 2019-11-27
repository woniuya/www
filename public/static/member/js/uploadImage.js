
 function uploadFile(fileid,data,callBack, source){
	var dom = document.getElementById(fileid);

	var file =  dom.files[0];//File对象;
		
	if(validationFile(file, source)){
		$.ajaxFileUpload({
		
			url : urls, //用于文件上传的服务器端请求地址
			secureuri : false, //一般设置为false
			fileElementId : fileid, //文件上传空间的id属性  <input type="file" id="file" name="file" />
			dataType : 'json', //返回值类型 一般设置为json
			data : data,
			async : false,
			contentType : "text/json;charset=utf-8",
			success : function(res){ //服务器成功响应处理函数
				callBack.call(this,res);
			},
			error: function (data, status, e)//服务器响应失败处理函数
			{
				//alert(e);
			}
		});
	}
}
function validationFile(file, source) {

	var fileTypeArr = ['application/php','text/html','application/javascript','application/msword','application/x-msdownload','text/plain'];
	if(null == file) return false;
	
	if(!file.type){
		if(source == 1) layer.msg("文件类型不合法");
		
		else if(source == "pc" ) $.msg("文件类型不合法");
			
		else showTip("文件类型不合法","warning");
		
		return false;
	}
	
	var flag = false;
	for(var i=0;i<fileTypeArr.length;i++){
		if(file.type == fileTypeArr[i]){
			flag = true;
			break;
		}
	}
	
	if(flag){
		if(source == 1) layer.msg("文件类型不合法");

		else if(source == "pc" ) $.msg("文件类型不合法");
		
		else showTip("文件类型不合法","warning");
		
		return false;
	}
	return true;
}
