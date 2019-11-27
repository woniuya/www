/**
 * auth liujunbin
 * Copyright (c) 2017-2034. (MIT Licensed)
 * https://github.com/976500133/IE-FBI-WARNing
 */

;(function() {

    var _icons = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAByCAYAAAAyPNMIAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyZpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMTM4IDc5LjE1OTgyNCwgMjAxNi8wOS8xNC0wMTowOTowMSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTcgKFdpbmRvd3MpIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkY0OUY5MkI2NzRGNDExRThBQzRCRTFDQzg2OTk2MDBEIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkY0OUY5MkI3NzRGNDExRThBQzRCRTFDQzg2OTk2MDBEIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6RjQ5RjkyQjQ3NEY0MTFFOEFDNEJFMUNDODY5OTYwMEQiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6RjQ5RjkyQjU3NEY0MTFFOEFDNEJFMUNDODY5OTYwMEQiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz4lh3FKAAAX/0lEQVR42uxb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";

    var _css = '*{margin:0;padding:0;font-size:12px; font-family:Arial,"\5B8B\4F53";line-height:20px;color:#535353;}'+
        'body { background:#fff;}'+
        '.clear { clear:both;}'+
        'em{color:#cd0000; font-style:normal; font-size:14px;}'+
        '.wrap{width:600px; margin:160px auto 0;overflow:hidden;background: #fff;padding: 30px;}'+
        '.update_logo { margin-top:20px;}'+
        '.update_logo a{display: block; font-size: 16px; font-weight: bold; text-decoration: none;}'+
        '.update_logo img { vertical-align: middle; border: none;}'+
        '.update_head {}'+
        '.update_head img{ margin-top:45px;}'+
        '.update_head h3{ color:#000; font-size:16px; font-weight:bold;margin:30px 0 5px;text-align:center;line-height: 50px;font-size: 22px;}'+
        '.update_head p{ line-height:26px; font-size:14px;}'+
        '.updata_bros {clear:both;border-radius:5px;padding:10px 0;}'+
        '.updata_bros h2 {color:#4b4b4b;float:left;padding-top:19px;}'+
        '.updata_bros p {position:relative;}'+
        '.updata_bros a {background:url('+ _icons +') no-repeat 100px 0;display:inline-block; vertical-align:middle;padding:10px 0 10px 35px; margin:10px 8px 0 5px; text-decoration:none;}'+
        '.updata_bros a.google { background-position:0 -41px;}'+
        '.updata_bros a.opera { background-position:0 0;}'+
        '.updata_bros a.safari { background-position:0 -83px;}'+
        '.updata_bros small {display:block;clear:both;}';

    var _html = '<!DOCTYPE html>'+
        '<html lang="en" >'+
        '<head>'+
        '<meta charset="UTF-8">'+
        '<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">'+
        '<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">'+
        '<title>浏览器提醒</title>'+
        '<style media="screen">'+ _css +'</style>'+
        ''+
        ''+
        '</head>'+
        '<body ontouchstart>'+
        '<div id="app"></div>'+
        '<div class="wrap">'+
        ''+
        '<div class="update_head">'+
        '<h3>改善您的体验</h3>'+
        '<p>我们使用了最新的技术来搭建我们的网站,使我们的网站更快和更容易使用。不幸的是，你的浏览器不支持这些技术。下载以下其中一种伟大的浏览器，你就可以正常访问了！'+
        '</div>'+
        '<div class="clear"></div>'+
        '<div class="updata_bros">'+
        '<h2>推荐浏览器下载地址：</h2>'+
        '<p>'+
        '<a href="http://www.baidu.com/s?wd=google%E6%B5%8F%E8%A7%88%E5%99%A8" target="_blank" class="google">chrome</a>'+
        '<a href="http://www.apple.com.cn/safari/" target="_blank" class="safari">苹果</a>'+
        '<a href="https://www.opera.com/" target="_blank" class="opera">Opera</a>'+
        '</p>'+
        '</div>'+
        '</div>'+
        '</body>'+
        '</html>'


    function checkIEVersion(versions){
        //判断是否为IE
        if (window.ActiveXObject){
            var rv = navigator.userAgent.indexOf("rv:11.0");
            var IEVersion=0;
            if(navigator.appVersion.match(/7./i)=="7."){
                IEVersion=7;
            }else if(navigator.appVersion.match(/8./i)=="8."){
                IEVersion=8;
            }else if(navigator.appVersion.match(/9./i)=="9."){
                IEVersion=9;
            }else if(navigator.appVersion.match(/6./i)=="6."){
                IEVersion=6;
            }
            IEVersion <= versions && document.write(_html)
        }else if(navigator.appVersion.indexOf("MSIE 10") != -1){
            10 <= versions && document.write(_html)
        }else if(window.navigator.userAgent.indexOf('Edge ') !== -1 || !!navigator.userAgent.match(/Trident\/7.0/)){
            11 <= versions && document.write(_html)
        }
        //用于测试页面 test
        versions == 'test' && document.write(_html)

    }

    function IEOLd(versions){
        checkIEVersion(versions)
    }


    if (typeof module !== 'undefined' && typeof exports === 'object') {
        module.exports = IEOLd;
    } else if (typeof define === 'function' && define.amd) {
        define(function() { return IEOLd; });
    } else {
        this.IEOLd = IEOLd;
    }



    // document.write(_html)

}).call(function() {
    return this || (typeof window !== 'undefined' ? window : global);
}());
