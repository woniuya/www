"use strict";

$(function () {
    /**
     |--------------------------------------------------
     | 选择银行卡
     |--------------------------------------------------
     */
    var selectContainer = $(".fake-select");
    var bankLists = selectContainer.children(".card-select-list");
    var checkedBank = $(".fake-select .checked-bank");
    checkedBank.on("click", function (e) {
        e.stopPropagation();
        if (bankLists) {
            bankLists.stop().slideDown();
        }
    })
    bankLists.on("click", "li:not(.item-add-bank)", function (e) {
        checkedBank.html($(this).html());
        $("#bank_id").val($(this).attr('data-id'));
        bankLists.stop().slideUp('fast');
    })
    selectContainer.find(".btn-bank-more").on("click", function (e) {
        e.preventDefault();
        checkedBank.trigger("click");
    })

    $(".btn-pick-all").on("click", function () {
        $("#money").val(parseFloat($(this).data("max")));
    })

    $("#withdrawForm").validate({
        rules: {
            paywd: {
                required: true,
                minlength: 6,
                maxlength: 6
            },
            money: {
                required: true,
                number: true
            },
            captcha: {
                required: true,
                minlength: 4,
                maxlength: 4
            }
        },
        messages: {
            paywd: {
                required: "请填写支付密码",
                minlength: "支付密码有误",
                maxlength: "支付密码有误"
            },
            money: {
                required: "请填写提现金额",
                number: "提现金额只能是数字"
            },
            captcha: {
                required: "请填写验证码",
                minlength: "验证码有误",
                maxlength: "验证码有误"
            }
        },
        errorElement: "div",

        errorPlacement: function (error, element) {
            $(element).closest(".con").append(error);
        },
        submitHandler: function (form) {
            if( parseFloat($(".btn-pick-all").data("max")) < parseFloat($("#money").val()) ) {
                layer.msg("提现金额不能大于账户余额");
                return false;
            }
            $.ajax({
                url: $("#withdrawForm").attr("action"),
                type: "post",
                data: {
                    money: $("#money").val(),
                    paywd: $("#paywd").val(),
                    captcha: $("#captcha").val(),
                    bank_id: $("#bank_id").val(),
                },
                dataType: "json",
                success: function (data) {
                    if(data){
                        layer.msg(data.msg, function () {
                            $(".v-img").trigger("click")
                            if(data.url) window.location.href = data.url
                        })
                    }
                }
            })
        }

    })
})