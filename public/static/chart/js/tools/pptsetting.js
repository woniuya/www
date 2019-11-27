xh5_define('tools.pptsetting',['utils.util'],function(util_){
	'use strict';
	var isFunc=util_.isFunc;

	return new function(){
		this.get=function(cb_){
			/*
			 我知道了
			 next: {
				 zIndex:1,
				 background: 'url("//n.sinaimg.cn/finance/chartimg/pptx.png") no-repeat',
				 backgroundPosition: '0px -30px',
				 position: 'absolute',
				 cursor: 'pointer',
				 width: '90px',
				 height: '30px',
				 left: '190px',
				 top: '140px'
			 }
			 下一步
			 next: {
				 zIndex:1,
				 background: 'url("//n.sinaimg.cn/finance/chartimg/pptx.png") no-repeat',
				 backgroundPosition: '0px 0px',
				 cursor: 'pointer',
				 position: 'absolute',
				 width: '90px',
				 height: '30px',
				 left: '292px',
				 top: '150px'
			 }
			 */

			window.kke_tool_demo = {

				parentDom: 'h5Figure',
				container: {
					//position: 'absolute',
					display: 'none'
					//background:'#ffffff',
					//filter:'Alpha(Opacity=50)',
					//opacity:0.5,
					//top: '0px',
					//zIndex: '305'
					//width: '560px',
					//height: '490px'
				},
				pages: [
					{
						prev: {
						},
						next: {
							zIndex:306,
							background: 'url("//n.sinaimg.cn/finance/chartimg/pptx.png") no-repeat',
							backgroundPosition: '0px 0px',
							cursor: 'pointer',
							margin: '13px 0 0 45px',
							//width: '90px',
							height: '30px'
						},
						//这个是我知道了按钮
						bg: {
							background: 'url("//n.sinaimg.cn/finance/chartimg/pptx.png") no-repeat',
							backgroundPosition: '0px -192px',
							position: 'absolute',
							//cursor: 'pointer',
							width: '190px',
							height: '122px',
							left: '140px',
							zIndex: '305',
							top: '12%'
						},
						close:{

							cursor: 'pointer',
							backgroundColor: 'transparent',
							//background: 'url("//n.sinaimg.cn/finance/chartimg/painttool_icon.png?24") -81px -7px no-repeat',
							position: 'absolute',
							width:'15px',
							height:'15px',
							right:'6px',
							top:'7px',
							textAlign:'center',
							verticalAlign:'middle',
							border: 'none',
							margin:0,
							padding:0,
							outline: 'none',
							lineHeight: '10px',
							color:'#fff'
						},
						context:{
							fontSize:'14px',
							lineHeight: '20px',
							padding:'20px 15px 0 20px',
							text:'鼠标点击K线后按回车键查看当日历史分时走势',
							wordWrap:'break-word',
							wordBreak:'break-all',
							color:'#ffffff',
							display:'block',
							width: '160px',
							zIndex: '305'
						}
					},
					{
						prev: {
						},
						next: {
							zIndex:306,
							background: 'url("//n.sinaimg.cn/finance/chartimg/pptx.png") no-repeat',
							backgroundPosition: '0px 0px',
							cursor: 'pointer',
							margin: '13px 0 0 45px',
							//width: '90px',
							height: '30px'
						},
						bg: {
							background: 'url("//n.sinaimg.cn/finance/chartimg/pptx.png") no-repeat',
							backgroundPosition: '0px -60px',
							position: 'absolute',
							width: '192px',
							height: '125px',
							zIndex: '305',
							left: '250px',
							top: '65px'
						},
						close:{
							cursor: 'pointer',
							backgroundColor: 'transparent',
							//background: 'url("//n.sinaimg.cn/finance/chartimg/painttool_icon.png?24") -81px -7px no-repeat',
							position: 'absolute',
							width:'15px',
							height:'15px',
							right:'8px',
							top:'20px',
							textAlign:'center',
							verticalAlign:'middle',
							border: 'none',
							margin:0,
							padding:0,
							outline: 'none',
							lineHeight: '10px',
							color:'#ffffff'
						},
						context:{
							fontSize:'14px',
							lineHeight: '20px',
							padding:'35px 15px 0 16px',
							text:'点击指标名称或数值区域进行参数配置',
							wordWrap:'break-word',
							wordBreak:'break-all', color:'#ffffff',
							display:'block',
							width: '160px',
							zIndex: '305'
						}
					},
					{
						prev: {
						},
						next: {
							zIndex:306,
							background: 'url("//n.sinaimg.cn/finance/chartimg/pptx.png") no-repeat',
							backgroundPosition: '0px 0px',
							cursor: 'pointer',
							margin: '15px 0 0 45px',
							//width: '90px',
							height: '30px'
						},
						//这个是我知道了按钮
						bg: {
							background: 'url("//n.sinaimg.cn/finance/chartimg/pptx.png") no-repeat',
							backgroundPosition: '0px -192px',
							position: 'absolute',
							zIndex: '305',
							width: '190px',
							height: '122px',
							left: '80px',
							bottom: '11%'//'100px'
						},
						close:{
							cursor: 'pointer',
							backgroundColor: 'transparent',
							//background: 'url("//n.sinaimg.cn/finance/chartimg/painttool_icon.png?24") -81px -7px no-repeat',
							position: 'absolute',
							width:'15px',
							height:'15px',
							right:'6px',
							top:'7px',
							textAlign:'center',
							verticalAlign:'middle',
							border: 'none',
							margin:0,
							padding:0,
							outline: 'none',
							lineHeight: '10px',
							color:'#ffffff'
						},
						context:{
							fontSize:'14px',
							lineHeight: '20px',
							padding:'20px 15px 0 16px',
							text:'点击\u25b2\u25bc按钮翻页，查看更多技术指标',
							wordWrap:'break-word',
							wordBreak:'break-all',
							color:'#ffffff',
							display:'block',
							zIndex: '305',
							width: '160px'
						}
					},
					{
						prev: {
						},
						next: {
							zIndex:306,
							background: 'url("//n.sinaimg.cn/finance/chartimg/pptx.png") no-repeat',
							backgroundPosition: '0px -30px',
							margin: '32px 0 0 45px',
							cursor: 'pointer',
							//width: '190px',
							height: '30px'
						},
						//这个是我知道了按钮
						bg: {
							background: 'url("//n.sinaimg.cn/finance/chartimg/pptx.png") no-repeat',
							backgroundPosition: '0px -320px',
							position: 'absolute',
							zIndex:305,
							width: '190px',
							height: '122px',
							right: '2%',
							top: '30px'
						},
						close:{
							cursor: 'pointer',
							backgroundColor: 'transparent',
							//background: 'url("//n.sinaimg.cn/finance/chartimg/painttool_icon.png?24") -81px -7px no-repeat',
							position: 'absolute',
							width:'15px',
							height:'15px',
							right:'6px',
							top:'20px',
							textAlign:'center',
							verticalAlign:'middle',
							border: 'none',
							margin:0,
							padding:0,
							outline: 'none',
							lineHeight: '10px',
							color:'#ffffff'
						},
						context:{
							fontSize:'14px',
							lineHeight: '20px',
							padding:'30px 15px 0 20px',
							text:'更多功能在这里',
							wordWrap:'break-word',
							wordBreak:'break-all',
							color:'#ffffff',
							display:'block',
							zIndex:305,
							width: '160px'
						}
					}
				]
			};

			isFunc(cb_)&&cb_();

		};
	};
});