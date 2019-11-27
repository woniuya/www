"use strict";

(function($,window){
	//slider
	$.fn.fullWidthSlider = function(config){
		var windowWidth = $(window).width();
		var setting = $.extend({
			intervalTime: 4000,
			autoPlay:true,
			showPager: true
		},config);


		return this.each(function(index,item){
			var activeIndex = 0,
				timer,
				sliderNum = $(item).find('ul>li').length,
				slideHeight = $(item).find('ul>li img').height();
			
			$(item).find("ul").css({marginLeft : ~ (1920 - windowWidth) / 2 , height : slideHeight + 'px' })
			// create pager
			if(setting.showPager){
				var pagerTmpl = "<div class='pager'>";

				for(var i = 0;i < sliderNum; i++){
					pagerTmpl += "<span></span>"
				}
				pagerTmpl += "</div>"
				$(pagerTmpl).appendTo($(item));
			}

			play(item,activeIndex); // init status

			if(setting.autoPlay)  startInvterval();
			
			// hover pause
			$(item).on("mouseover",function(){
				clearInterval(timer);
			}).on('mouseout',function(){
				startInvterval();
			})

			// pager click
			$(item).find(".pager>span").on('click',function(){
				activeIndex = $(this).index();
				play(item,activeIndex);
			})

			function startInvterval(){
				timer = setInterval(function(){
					activeIndex < sliderNum - 1 ? activeIndex++ : activeIndex = 0;
					play(item,activeIndex);
				},setting.intervalTime)
			}

		})
	}

	function play(item,activeIndex){
		$(item).find("ul>li").eq(activeIndex).animate({opacity:1},800, '', function(){
			$(this).show().siblings().hide();
		}).siblings().animate({opacity:0},800);
		$(item).find(".pager>span").eq(activeIndex).addClass('active').siblings().removeClass('active');
	}

}(jQuery,window))