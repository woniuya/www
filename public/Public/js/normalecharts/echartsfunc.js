function _ismb() { if ((navigator.userAgent.match(/(phone|pad|pod|iPhone|iPod|iOS|iPad|Android|Mobile|BlackBerry|IEMobile|MQQBrowser|JUC|Fennec|wOSBrowser|BrowserNG|WebOS|Symbian|Windows Phone)/i))) { return true; } else { return false; } }
function _getreq(n) { var reg = new RegExp("(^|&)" + n + "=([^&]*)(&|$)", "i"); var r = window.location.search.substr(1).match(reg); if (r != null) { return unescape(r[2]) } return null };
function calculateMA(dayCount, data) { var result = []; for (var i = 0, len = data.length; i < len; i++) { if (i < dayCount) { result.push('-'); continue; } var sum = 0; for (var j = 0; j < dayCount; j++) { sum += data[i - j][1]; } result.push((sum / dayCount).toFixed(5)); } return result; }

Date.Parse = function (date) {
    return eval('new ' + date.replace('/', '', 'g').replace('/', '', 'g'));
};
Date.prototype.Format = function (format) {
    var o =
        {
            "M+": this.getMonth() + 1, //month
            "d+": this.getDate(),    //day
            "h+": this.getHours(),   //hour
            "m+": this.getMinutes(), //minute
            "s+": this.getSeconds(), //second
            "q+": Math.floor((this.getMonth() + 3) / 3),  //quarter
            "S": this.getMilliseconds() //millisecond
        }
    if (/(y+)/.test(format))
        format = format.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (var k in o)
        if (new RegExp("(" + k + ")").test(format))
            format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? o[k] : ("00" + o[k]).substr(("" + o[k]).length));
    return format;
};

var _setf = {
    bg: "#fff",
    l0: "#000",//深线
    l1: "#666",//浅线
    l2: "#999",//再浅
    up: "#ef232a",//红
    upl: "#ef232a",//红边
    dn: "#009432",//绿
    dnl: "#009432",//绿边
    up0: "black",//高亮
    dn0: "#444",//高亮0
    v: "#a4b0be",//量
    v0: "#140",//量高亮
    tpl: "#333",
    tpb: "rgba(0,0,0,0.7)",
    tpt: "#fff",
    h: 260,
    lp: 10,
    rp:50,
    h2: 40,
    frametop:40,
    cls: ['#c23531', '#2f4554', '#61a0a8', '#d48265', '#91c7ae', '#749f83', '#ca8622', '#bda29a', '#6e7074', '#546570', '#c4ccd3']
};
var _set0 = {
    bg: "#222",
    l0: "#fff",//深线
    l1: "#dedede",//浅线
    l2: "#ccc",//再浅
    up: "#ef232a",//红
    upl: "#ef232a",//红边
    dn: "#009432",//绿
    dnl: "#009432",//绿边
    up0: "white",//高亮
    dn0: "#444",//高亮0
    v: "#999",//量
    v0: "white",//量高亮
    tpl: "#333",
    tpb: "rgba(255,255,255,0.7)",
    tpt: "#000",
    h: 260,
    lp: 10,
    rp: 50,
    h2: 40,
    frametop: 40,
    cls: ['#C4E538', '#7efff5', '#ffda79', '#fed330', '#7efff5', '#fffa65', '#6e7074', '#546570', '#c4ccd3']
};

function createOption(symbol, ltype, colorset, period, stp, edp, dates, datas, datals, vols, dataMA5, dataMA20, dataMA30)
{
    var labelFont = '12px Sans-serif';
    return {
        backgroundColor: colorset.bg,
        animation: false,
        color: colorset.cls,
        title: {
            show: false
        },
        legend: {
            top: 0,
            data: (ltype == "line" ? [symbol + "," + period] : [symbol + "," + period, 'MA5', 'MA20', 'MA30']),
            textStyle: {
                color: colorset.l1
            }
        },
        tooltip: {
            transitionDuration: 0,
            confine: true,
            bordeRadius: 4,
            borderWidth: 1,
            borderColor: colorset.tpl,
            backgroundColor: colorset.tpb,
            textStyle: {
                fontSize: 12,
                color: colorset.tpt
            },
            position: function (pos, params, el, elRect, size) {
                var obj = {
                    top: colorset.frametop
                };
                obj[['left', 'right'][+(pos[0] < size.viewSize[0] / 2)]] = 5;
                return obj;
            },
            formatter(param) {
                if (param[0] == undefined) { return ""; }
                if (ltype == "line") {
                    return [param[0].name, 'C: ' + param[0].data.toFixed(5)].join('<br/>');
                }
                else {
                    return [param[0].name,
                    'O: ' + param[0].data[1].toFixed(5),
                    'C: ' + param[0].data[2].toFixed(5),
                    'L: ' + param[0].data[3].toFixed(5),
                    'H: ' + param[0].data[4].toFixed(5),
                    'V: ' + param[0].data[5].toFixed(0)].join('<br/>');
                }
            }
        },
        axisPointer: {
            link: [{
                xAxisIndex: [0, 1]
            }]
        },
        dataZoom: [{
			type: 'slider',
			xAxisIndex: [0, 1],
                start: stp,
                end: edp
            },
            {
                type: 'inside',
                xAxisIndex: [0, 1],
                start: stp,
                end: edp
        }],
        xAxis: [{
            type: 'category',
            data: dates,
            boundaryGap: false,
            axisLine: { lineStyle: { color: colorset.l2, opacity: 0.1, width: 0.1 } },
            axisLabel: {
                formatter: function (value) {
                    return value;
                }
            },
            min: 'dataMin',
            max: 'dataMax',
            axisPointer: {
                show: true
            }
        }, {
            type: 'category',
            gridIndex: 1,
            data: dates,
            scale: true,
            boundaryGap: false,
            splitLine: { show: false },
            axisLabel: { show: false },
            axisTick: { show: false },
            axisLine: { lineStyle: { color: colorset.l2, opacity: 0.1, width: 0.1 } },
            splitNumber: 20,
            min: 'dataMin',
            max: 'dataMax',
            axisPointer: {
                type: 'shadow',
                label: { show: false },
                triggerTooltip: true
            }
        }],
        yAxis: [{
            scale: true,
            splitNumber: 2,
            axisLine: { lineStyle: { color: colorset.l2 } },
            splitLine: { show: true },
            axisTick: { show: false },
            axisLabel: {
                inside: true,
                formatter: '{value}\n'
            }
        }, {
            scale: true,
            gridIndex: 1,
            splitNumber: 2,
            axisLabel: { show: false },
            axisLine: { show: false },
            axisTick: { show: false },
            splitLine: { show: false }
        }],
        grid: [{
            left: colorset.lp,
            right: colorset.rp,
            top: colorset.frametop,
            height: colorset.h
        }, {
                left: colorset.lp,
                right: colorset.rp,
                height: colorset.h2,
            top: (colorset.frametop + colorset.h + 20)
        }],
        graphic: [{
            type: 'group',
            left: 'center',
            bounding: 'raw',
            children: [{
                id: 'MA5',
                type: 'text',
                style: { fill: colorset.cls[1], font: labelFont },
                left: 0
            }, {
                id: 'MA20',
                type: 'text',
                style: { fill: colorset.cls[2], font: labelFont },
                left: 'center'
            }, {
                id: 'MA30',
                type: 'text',
                style: { fill: colorset.cls[3], font: labelFont },
                right: 0
            }]
        }],
        series: [{
            name: '量',
            type: 'bar',
            xAxisIndex: 1,
            yAxisIndex: 1,
            itemStyle: {
                normal: {
                    color: colorset.v
                },
                emphasis: {
                    color: colorset.v0
                }
            },
            data: vols,
            tooltip: {
                formatter(param) {
                    return [param.name, 'V: ' + param.value].join('<br/>');
                }
            }
        },
        {
			type: ltype,
			symbol: "none",
            name: (symbol + "," + period),
            data: (ltype == "line" ? datals : datas),
            itemStyle: {
                normal: {
                    color: (ltype == "line" ? colorset.l2 : colorset.up),
                    color0: colorset.dn,
                    borderColor: (ltype == "line" ? colorset.l1 : colorset.up1),
                    borderColor0: colorset.dnl
                },
                emphasis: {
                    color: colorset.up0,
                    color0: colorset.dn0,
                    borderColor: colorset.up0,
                    borderColor0: colorset.dn0
                }
            },
            areaStyle: {
                normal: {
                    color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                        offset: 0,
                        color: "#B0C4DE"
                    }, {
                        offset: 1,
                        color: "#A9A9A9"
                    }])
                }
            },
            tooltip: {
                formatter(param) {
                    if (ltype == "line") {
                        return [param.name, 'C: ' + param.data.toFixed(5)].join('<br/>');
                    }
                    else {
                        return [param.name,
                            'O: ' + param.data[1].toFixed(5),
                            'C: ' + param.data[2].toFixed(5),
                            'L: ' + param.data[3].toFixed(5),
                            'H: ' + param.data[4].toFixed(5),
                            'V: ' + param.data[5].toFixed(0)].join('<br/>');
                    }
                }
            }, markLine: {
                symbol: "none",
                silent: true,
                label: {show:true},
                lineStyle: { opacity :0.5,width:0.3},
                data: [
                    {
                        //yAxis: (ltype == "line" ? (datals.length > 0 ? datals[datals.length - 1] : 0) : (datas.length > 0 ? datas[datas.length - 1][1] : 0))
                        yAxis: (datas.length > 0 ? datas[datas.length - 1][1].toFixed(5) : 0)
                    }
                ]
            }
        }, {
            name: 'MA5',
            type: 'line',
                data: (ltype == "line" ? null : dataMA5),
            smooth: true,
            showSymbol: false,
            lineStyle: {
                normal: {
                    width: 0.6,
                    opacity: 0.7
                }
            },
            tooltip: {
                formatter(param) {
                    return [param.name, 'MA5: ' + parseFloat(param.value).toFixed(2)].join('<br/>');
                }
            }
        }, {
            name: 'MA20',
            type: 'line',
                data: (ltype == "line" ? null : dataMA20),
            smooth: true,
            showSymbol: false,
            lineStyle: {
                normal: {
                    width: 0.6,
                    opacity: 0.7
                }
            },
            tooltip: {
                formatter(param) {
                    return [param.name, 'MA20: ' + parseFloat(param.value).toFixed(2)].join('<br/>');
                }
            }
        }, {
            name: 'MA30',
            type: 'line',
                data: (ltype=="line"?null:dataMA30),
            smooth: true,
            showSymbol: false,
            lineStyle: {
                normal: {
                    width: 0.6,
                    opacity: 0.7
                }
            },
            tooltip: {
                formatter(param) {
                    return [param.name, 'MA30: ' + parseFloat(param.value).toFixed(2)].join('<br/>');
                }
            }
        }]
    };
}

