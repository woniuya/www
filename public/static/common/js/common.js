var Util = {};

Math.round2 = function (number, precision) {
    return +(Math.round(number * Math.pow(10, precision)) / Math.pow(10, precision)).toFixed(precision);
};

(function($){
    /**
     * 选项卡插件
     * @param option
     */
    $.fn.tab = function (option) {
        var defaults = {
            active: 0,
            panel: ".tab-panel",
            triggerEvent: "click",
            tabItem: ".tab-item a",
            tabActiveItemClass: "active"
        };

        var setting = $.extend(defaults, option);
        var _this = this;
        _this.children(setting.panel).hide().eq(setting.active).show();

        _this.children(".tab-hd").on(setting.triggerEvent, setting.tabItem, function (e) {
            e.preventDefault();
            setting.active = $(this).parent().index();
            $(this).parent().addClass(setting.tabActiveItemClass).siblings().removeClass(setting.tabActiveItemClass);
            _this.children(setting.panel).hide().eq(setting.active).show();
        })
    };

    /**
     * switch btn plugin
     */

    $.fn.switchAutoRenewalBtn = function ( ) {
        var setting = {
            text: {
                open: "开",
                close: "关"
            }
        };
        return this.each(function () {
            var $this= $(this);
            $this.hasClass("active") ? $this.find("span").text(setting.text.open) : $this.find("span").text(setting.text.close);
            var disabled = false;
            $this.on("click", function () {
                if(disabled) return;
                disabled = true;
                postData($this, $this.data("id"), function () {
                    disabled = false;
                })
            });
            function postData($el, id, cb){
                $.post("/member/financing/autorenewal",{id: id},function (data) {
                    if(data.status === 1){
                        $el.toggleClass("active");
                        $el.hasClass("active") ? $el.find("span").text(setting.text.open) : $el.find("span").text(setting.text.close);
                    } else {
                        alert(data.msg)
                    }
                    cb && cb();

                },'json');
            }
        })
    };

    function Sendsms(option) {
        this.option = option;
        this.couldSend = true;
        this.url = option.url;
        this.timer = null;
        this.clock = option.duration || 60;
        this.bindEventListener();
    }

    Sendsms.prototype.bindEventListener = function () {
        this.option.btn.on("click", function () {
            this.send();
        }.bind(this))
    };

    Sendsms.prototype.send = function () {
        if (!this.couldSend) return false;
        this.couldSend = false; // 防止倒计时时重复点击

        $.ajax({
            url: this.url,
            data: this.option.data,
            success: function (data) {
                this.option.success && this.option.success(data);
            }.bind(this),
            dataType: "json"
        });
        this.option.btn.addClass("disabled");
        this.timer = setInterval(function () {
            this.clock--;
            this.option.btn.html(this.clock + 's');

            if( this.clock === 0 ) {
                clearTimeout(this.timer);
                this.couldSend = true;
                this.clock = this.option.duration;
                this.option.btn.removeClass("disabled").html("重新获取验证码");

            }
        }.bind(this), 1000)
    }
    window.Sendsms = Sendsms;

})(jQuery)


function Common_cbo(){
    $(".common_cbo").each(function(){
        $(this).click(function(){
            if($(this).find("input[type=checkbox]").prop("checked")){
                $(this).find("input[type=checkbox]").prop("checked",false);
                $(this).removeClass("common_cbo_checked");
                return false;
            }else{
                $(this).find("input[type=checkbox]").prop("checked",true);
                $(this).addClass("common_cbo_checked");
                return false;
            }
        });
    })
}

