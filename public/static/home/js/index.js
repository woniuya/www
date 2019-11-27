// 股票信息
(function () {
    var defaultStock = "600903";
    var searchContainer = $("#j-stock-search");

    fetchStockAndRender(defaultStock);
    // 搜索股票
    $("#change-stock-btn").on("click", function (e) {
        e.preventDefault();
        e.stopPropagation();
        searchContainer.show();
        $("#j-search-text").focus();
    });
    $(document).on("click", function (e) {
        if(!searchContainer.is(e.target) && searchContainer.has(e.target).length === 0 ){
            searchContainer.hide();
        }
    });
    searchContainer.on("click", ".drop-list li",function () {
        var stockCode = $(this).data("code");
        searchContainer.hide();
        fetchStockAndRender(stockCode.toString());
    });

    $("#j-search-text").on("keyup", _.debounce(function () {
        var searchText = $(this).val();
        if(searchText.length > 2 ){
            $.ajax({
                url: searchStockUrl,
                data: {
                    "key": searchText
                },
                dataType: "json",
                success: function (res) {
                    renderSearchList(res.data);
                }
            });
    }else{
            renderSearchList();
  }
    }, 300));
    $("#bannerSlider").fullWidthSlider();
    $(".j-tab").tab();
}());

//fetch stock info
function fetchStockAndRender(stockCode) {
    fetchStock(stockCode).then(function (data) {
        renderStockTmpl(data.data, stockCode);
    });
}

function renderSearchList(data) {
    if(!$.isEmptyObject(data)){
        $("#j-stock-search .drop-list").remove();
        var tmpl = "<ul class=\"drop-list\">";
        for(var i in data){
            tmpl += "<li class='clearfix' data-code='"+ data[i].code +"'><span class='name'>"+ data[i].name + "</span><span class='code'>"+ data[i].code + "</span></li>";
        }
        tmpl+= "</ul>";
        $(tmpl).appendTo($("#j-stock-search"))
    } else {
        $("#j-stock-search .drop-list").remove();
    }
}

function renderStockTmpl(data, code) {
    var priceRange = +data.current_price - data.yesterday_price;
    var highest = parseFloat(data.highest);
    var lowest = parseFloat(data.lowest);
    $("#btn-market").attr("href", "/market/index/index?c=" + code);
    $("#stock-code").html(data.code);
    $("#stock-name").html(data.name);
    $("#stock-price-range").html( priceRange.toFixed(2))
    $("#stock-range-rate").html( (priceRange / data.yesterday_price * 100).toFixed(2));
    $("#stock-price").html(data.current_price);
    $("#stock-highest").html(highest.toFixed(2));
    $("#stock-lowest").html(lowest.toFixed(2));
    var codePrefixer = "";
    switch(code.toString().substring(0,2)) {
        case "60":
            codePrefixer = "sh";
            break;
        case "00":
            codePrefixer = "sz";
            break;
        default:
            layer.msg("暂时只支持查询A股股票");
    }
    $("#chart-min").attr("src","http://image.sinajs.cn/newchart/min/n/" + codePrefixer + code +".gif");
    $("#chart-daily").attr("src","http://image.sinajs.cn/newchart/daily/n/"+ codePrefixer + code +".gif");
    $("#chart-weekly").attr("src","http://image.sinajs.cn/newchart/weekly/n/"+ codePrefixer + code +".gif");
    $("#chart-monthly").attr("src","http://image.sinajs.cn/newchart/monthly/n/"+ codePrefixer + code +".gif");

    if( priceRange > 0 ) {
        $("#stock-range-rate").parent().addClass("text-red").removeClass("text-green");
        $("#trend-mark").addClass("trend-mark-up").removeClass("trend-mark-down");
    }
    if( priceRange < 0 ) {
        $("#stock-range-rate").parent().addClass("text-green").removeClass("text-red");
        $("#trend-mark").addClass("trend-mark-down").removeClass("trend-mark-up")
    }

    compareColor(data.current_price, data.yesterday_price, $("#stock-price-range"));
    compareColor(data.current_price, data.yesterday_price, $("#stock-price"));
    compareColor(data.highest, data.yesterday_price, $("#stock-highest"));
    compareColor(data.lowest, data.yesterday_price, $("#stock-lowest"));

    compareColor(data.sell_five_price, data.yesterday_price, $("#sell-five-price"));
    compareColor(data.sell_four_price, data.yesterday_price, $("#sell-four-price"));
    compareColor(data.sell_three_price, data.yesterday_price, $("#sell-three-price"));
    compareColor(data.sell_two_price, data.yesterday_price, $("#sell-two-price"));
    compareColor(data.sell_one_price, data.yesterday_price, $("#sell-one-price"));

    compareColor(data.buy_five_price, data.yesterday_price, $("#buy-five-price"));
    compareColor(data.buy_four_price, data.yesterday_price, $("#buy-four-price"));
    compareColor(data.buy_three_price, data.yesterday_price, $("#buy-three-price"));
    compareColor(data.buy_two_price, data.yesterday_price, $("#buy-two-price"));
    compareColor(data.buy_one_price, data.yesterday_price, $("#buy-one-price"));


    $("#sell-five-amount").html(data.sell_five_amount);
    $("#sell-five-price").html(data.sell_five_price);
    $("#sell-four-amount").html(data.sell_four_amount);
    $("#sell-four-price").html(data.sell_four_price);
    $("#sell-three-amount").html(data.sell_three_amount);
    $("#sell-three-price").html(data.sell_three_price);
    $("#sell-two-amount").html(data.sell_two_amount);
    $("#sell-two-price").html(data.sell_two_price);
    $("#sell-one-amount").html(data.sell_one_amount);
    $("#sell-one-price").html(data.sell_one_price);

    $("#buy-five-amount").html(data.buy_five_amount);
    $("#buy-five-price").html(data.buy_five_price);
    $("#buy-four-amount").html(data.buy_four_amount);
    $("#buy-four-price").html(data.buy_four_price);
    $("#buy-three-amount").html(data.buy_three_amount);
    $("#buy-three-price").html(data.buy_three_price);
    $("#buy-two-amount").html(data.buy_two_amount);
    $("#buy-two-price").html(data.buy_two_price);
    $("#buy-one-amount").html(data.buy_one_amount);
    $("#buy-one-price").html(data.buy_one_price);
}

function compareColor(price, basePrice, $el) {
    if(+price - basePrice > 0) $el.addClass("text-red").removeClass("text-green");
    if(+price - basePrice < 0) $el.addClass("text-green").removeClass("text-red");
}

function fetchStock(code){
    return $.ajax({
        url: "/market/index/market",
        data: {
            code: code
        }
    })
}