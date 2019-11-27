!
function() {
    function s(a, b) {
        var c;
        a || (a = {});
        for (c in b) a[c] = b[c];
        return a
    }
    function x() {
        var a, b = arguments.length,
        c = {},
        d = function(a, b) {
            var c, h;
            "object" != typeof a && (a = {});
            for (h in b) b.hasOwnProperty(h) && (c = b[h], a[h] = c && "object" == typeof c && "[object Array]" !== Object.prototype.toString.call(c) && "number" != typeof c.nodeType ? d(a[h] || {},
            c) : b[h]);
            return a
        };
        for (a = 0; b > a; a++) c = d(c, arguments[a]);
        return c
    }
    function y(a, b) {
        return parseInt(a, b || 10)
    }
    function ea(a) {
        return "string" == typeof a
    }
    function T(a) {
        return "object" == typeof a
    }
    function Ia(a) {
        return "[object Array]" === Object.prototype.toString.call(a)
    }
    function ra(a) {
        return "number" == typeof a
    }
    function ma(a) {
        return R.log(a) / R.LN10
    }
    function fa(a) {
        return R.pow(10, a)
    }
    function ga(a, b) {
        for (var c = a.length; c--;) if (a[c] === b) {
            a.splice(c, 1);
            break
        }
    }
    function u(a) {
        return a !== v && null !== a
    }
    function w(a, b, c) {
        var d, e;
        if (ea(b)) u(c) ? a.setAttribute(b, c) : a && a.getAttribute && (e = a.getAttribute(b));
        else if (u(b) && T(b)) for (d in b) a.setAttribute(d, b[d]);
        return e
    }
    function ja(a) {
        return Ia(a) ? a: [a]
    }
    function o() {
        var b, c, a = arguments,
        d = a.length;
        for (b = 0; d > b; b++) if (c = a[b], "undefined" != typeof c && null !== c) return c
    }
    function I(a, b) {
        sa && b && b.opacity !== v && (b.filter = "alpha(opacity=" + 100 * b.opacity + ")"),
        s(a.style, b)
    }
    function U(a, b, c, d, e) {
        return a = z.createElement(a),
        b && s(a, b),
        e && I(a, {
            padding: 0,
            border: S,
            margin: 0
        }),
        c && I(a, c),
        d && d.appendChild(a),
        a
    }
    function ha(a, b) {
        var c = function() {};
        return c.prototype = new a,
        s(c.prototype, b),
        c
    }
    function Aa(a, b, c, d) {
        var e = L.lang,
        a = +a || 0,
        f = -1 === b ? (a.toString().split(".")[1] || "").length: isNaN(b = M(b)) ? 2 : b,
        b = void 0 === c ? e.decimalPoint: c,
        d = void 0 === d ? e.thousandsSep: d,
        e = 0 > a ? "-": "",
        c = String(y(a = M(a).toFixed(f))),
        g = c.length > 3 ? c.length % 3 : 0;
        return e + (g ? c.substr(0, g) + d: "") + c.substr(g).replace(/(\d{3})(?=\d)/g, "$1" + d) + (f ? b + M(a - c).toFixed(f).slice(2) : "")
    }
    function Ba(a, b) {
        return Array((b || 2) + 1 - String(a).length).join(0) + a
    }
    function mb(a, b, c) {
        var d = a[b];
        a[b] = function() {
            var a = Array.prototype.slice.call(arguments);
            return a.unshift(d),
            c.apply(this, a)
        }
    }
    function Ca(a, b) {
        for (var e, f, g, h, i, c = "{",
        d = !1,
        j = []; - 1 !== (c = a.indexOf(c));) {
            if (e = a.slice(0, c), d) {
                for (f = e.split(":"), g = f.shift().split("."), i = g.length, e = b, h = 0; i > h; h++) e = e[g[h]];
                f.length && (f = f.join(":"), g = /\.([0-9])/, h = L.lang, i = void 0, /f$/.test(f) ? (i = (i = f.match(g)) ? i[1] : -1, e = Aa(e, i, h.decimalPoint, f.indexOf(",") > -1 ? h.thousandsSep: "")) : e = Ya(f, e))
            }
            j.push(e),
            a = a.slice(c + 1),
            c = (d = !d) ? "}": "{"
        }
        return j.push(a),
        j.join("")
    }
    function nb(a) {
        return R.pow(10, P(R.log(a) / R.LN10))
    }
    function ob(a, b, c, d) {
        var e, c = o(c, 1);
        for (e = a / c, b || (b = [1, 2, 2.5, 5, 10], d && d.allowDecimals === !1 && (1 === c ? b = [1, 2, 5, 10] : .1 >= c && (b = [1 / c]))), d = 0; d < b.length && (a = b[d], !(e <= (b[d] + (b[d + 1] || b[d])) / 2)); d++);
        return a *= c
    }
    function Cb(a, b) {
        var g, c = b || [[Db, [1, 2, 5, 10, 20, 25, 50, 100, 200, 500]], [pb, [1, 2, 5, 10, 15, 30]], [Za, [1, 2, 5, 10, 15, 30]], [Qa, [1, 2, 3, 4, 6, 8, 12]], [ta, [1, 2]], [$a, [1, 2]], [Ra, [1, 2, 3, 4, 6]], [Da, null]],
        d = c[c.length - 1],
        e = D[d[0]],
        f = d[1];
        for (g = 0; g < c.length && (d = c[g], e = D[d[0]], f = d[1], !(c[g + 1] && a <= (e * f[f.length - 1] + D[c[g + 1][0]]) / 2)); g++);
        return e === D[Da] && 5 * e > a && (f = [1, 2, 5]),
        c = ob(a / e, f, d[0] === Da ? r(nb(a / e), 1) : 1),
        {
            unitRange: e,
            count: c,
            unitName: d[0]
        }
    }
    function Eb(a, b, c, d) {
        var h, e = [],
        f = {},
        g = L.global.useUTC,
        i = new Date(b),
        j = a.unitRange,
        k = a.count;
        if (u(b)) {
            j >= D[pb] && (i.setMilliseconds(0), i.setSeconds(j >= D[Za] ? 0 : k * P(i.getSeconds() / k))),
            j >= D[Za] && i[Fb](j >= D[Qa] ? 0 : k * P(i[qb]() / k)),
            j >= D[Qa] && i[Gb](j >= D[ta] ? 0 : k * P(i[rb]() / k)),
            j >= D[ta] && i[sb](j >= D[Ra] ? 1 : k * P(i[Sa]() / k)),
            j >= D[Ra] && (i[Hb](j >= D[Da] ? 0 : k * P(i[ab]() / k)), h = i[bb]()),
            j >= D[Da] && (h -= h % k, i[Ib](h)),
            j === D[$a] && i[sb](i[Sa]() - i[tb]() + o(d, 1)),
            b = 1,
            h = i[bb]();
            for (var d = i.getTime(), l = i[ab](), m = i[Sa](), p = g ? 0 : (864e5 + 6e4 * i.getTimezoneOffset()) % 864e5; c > d;) e.push(d),
            j === D[Da] ? d = cb(h + b * k, 0) : j === D[Ra] ? d = cb(h, l + b * k) : g || j !== D[ta] && j !== D[$a] ? d += j * k: d = cb(h, l, m + b * k * (j === D[ta] ? 1 : 7)),
            b++;
            e.push(d),
            n(ub(e,
            function(a) {
                return j <= D[Qa] && a % D[ta] === p
            }),
            function(a) {
                f[a] = ta
            })
        }
        return e.info = s(a, {
            higherRanks: f,
            totalRange: j * k
        }),
        e
    }
    function Jb() {
        this.symbol = this.color = 0
    }
    function Kb(a, b) {
        var d, e, c = a.length;
        for (e = 0; c > e; e++) a[e].ss_i = e;
        for (a.sort(function(a, c) {
            return d = b(a, c),
            0 === d ? a.ss_i - c.ss_i: d
        }), e = 0; c > e; e++) delete a[e].ss_i
    }
    function Ja(a) {
        for (var b = a.length,
        c = a[0]; b--;) a[b] < c && (c = a[b]);
        return c
    }
    function ua(a) {
        for (var b = a.length,
        c = a[0]; b--;) a[b] > c && (c = a[b]);
        return c
    }
    function Ka(a, b) {
        for (var c in a) a[c] && a[c] !== b && a[c].destroy && a[c].destroy(),
        delete a[c]
    }
    function Ta(a) {
        db || (db = U(Ea)),
        a && db.appendChild(a),
        db.innerHTML = ""
    }
    function ka(a, b) {
        var c = "Highcharts error #" + a + ": www.highcharts.com/errors/" + a;
        if (b) throw c;
        N.console && console.log(c)
    }
    function ia(a) {
        return parseFloat(a.toPrecision(14))
    }
    function La(a, b) {
        Fa = o(a, b.animation)
    }
    function Lb() {
        var a = L.global.useUTC,
        b = a ? "getUTC": "get",
        c = a ? "setUTC": "set";
        cb = a ? Date.UTC: function(a, b, c, g, h, i) {
            return new Date(a, b, o(c, 1), o(g, 0), o(h, 0), o(i, 0)).getTime()
        },
        qb = b + "Minutes",
        rb = b + "Hours",
        tb = b + "Day",
        Sa = b + "Date",
        ab = b + "Month",
        bb = b + "FullYear",
        Fb = c + "Minutes",
        Gb = c + "Hours",
        sb = c + "Date",
        Hb = c + "Month",
        Ib = c + "FullYear"
    }
    function va() {}
    function Ma(a, b, c, d) {
        this.axis = a,
        this.pos = b,
        this.type = c || "",
        this.isNew = !0,
        !c && !d && this.addLabel()
    }
    function vb(a, b) {
        this.axis = a,
        b && (this.options = b, this.id = b.id)
    }
    function Mb(a, b, c, d, e, f) {
        var g = a.chart.inverted;
        this.axis = a,
        this.isNegative = c,
        this.options = b,
        this.x = d,
        this.total = null,
        this.points = {},
        this.stack = e,
        this.percent = "percent" === f,
        this.alignOptions = {
            align: b.align || (g ? c ? "left": "right": "center"),
            verticalAlign: b.verticalAlign || (g ? "middle": c ? "bottom": "top"),
            y: o(b.y, g ? 4 : c ? 14 : -6),
            x: o(b.x, g ? c ? -6 : 6 : 0)
        },
        this.textAlign = b.textAlign || (g ? c ? "right": "left": "center")
    }
    function eb() {
        this.init.apply(this, arguments)
    }
    function wb() {
        this.init.apply(this, arguments)
    }
    function xb(a, b) {
        this.init(a, b)
    }
    function fb(a, b) {
        this.init(a, b)
    }
    function yb() {
        this.init.apply(this, arguments)
    }
    var v, Va, db, L, Ya, Fa, Ab, D, cb, qb, rb, tb, Sa, ab, bb, Fb, Gb, sb, Hb, Ib, z = document,
    N = window,
    R = Math,
    t = R.round,
    P = R.floor,
    wa = R.ceil,
    r = R.max,
    J = R.min,
    M = R.abs,
    V = R.cos,
    ba = R.sin,
    xa = R.PI,
    Ua = 2 * xa / 360,
    na = navigator.userAgent,
    Nb = N.opera,
    sa = /msie/i.test(na) && !Nb,
    gb = 8 === z.documentMode,
    hb = /AppleWebKit/.test(na),
    ib = /Firefox/.test(na),
    Ob = /(Mobile|Android|Windows Phone)/.test(na),
    ya = "http://www.w3.org/2000/svg",
    W = !!z.createElementNS && !!z.createElementNS(ya, "svg").createSVGRect,
    Ub = ib && parseInt(na.split("Firefox/")[1], 10) < 4,
    ca = !W && !sa && !!z.createElement("canvas").getContext,
    jb = z.documentElement.ontouchstart !== v,
    Pb = {},
    zb = 0,
    oa = function() {},
    Ga = [],
    Ea = "div",
    S = "none",
    Qb = "rgba(192,192,192," + (W ? 1e-4: .002) + ")",
    Db = "millisecond",
    pb = "second",
    Za = "minute",
    Qa = "hour",
    ta = "day",
    $a = "week",
    Ra = "month",
    Da = "year",
    Rb = "stroke-width",
    X = {};
    N.Highcharts = N.Highcharts ? ka(16, !0) : {},
    Ya = function(a, b, c) {
        if (!u(b) || isNaN(b)) return "Invalid date";
        var e, a = o(a, "%Y-%m-%d %H:%M:%S"),
        d = new Date(b),
        f = d[rb](),
        g = d[tb](),
        h = d[Sa](),
        i = d[ab](),
        j = d[bb](),
        k = L.lang,
        l = k.weekdays,
        d = s({
            a: l[g].substr(0, 3),
            A: l[g],
            d: Ba(h),
            e: h,
            b: k.shortMonths[i],
            B: k.months[i],
            m: Ba(i + 1),
            y: j.toString().substr(2, 2),
            Y: j,
            H: Ba(f),
            I: Ba(f % 12 || 12),
            l: f % 12 || 12,
            M: Ba(d[qb]()),
            p: 12 > f ? "AM": "PM",
            P: 12 > f ? "am": "pm",
            S: Ba(d.getSeconds()),
            L: Ba(t(b % 1e3), 3)
        },
        Highcharts.dateFormats);
        for (e in d) for (; - 1 !== a.indexOf("%" + e);) a = a.replace("%" + e, "function" == typeof d[e] ? d[e](b) : d[e]);
        return c ? a.substr(0, 1).toUpperCase() + a.substr(1) : a
    },
    Jb.prototype = {
        wrapColor: function(a) {
            this.color >= a && (this.color = 0)
        },
        wrapSymbol: function(a) {
            this.symbol >= a && (this.symbol = 0)
        }
    },
    D = function() {
        for (var a = 0,
        b = arguments,
        c = b.length,
        d = {}; c > a; a++) d[b[a++]] = b[a];
        return d
    } (Db, 1, pb, 1e3, Za, 6e4, Qa, 36e5, ta, 864e5, $a, 6048e5, Ra, 26784e5, Da, 31556952e3),
    Ab = {
        init: function(a, b, c) {
            var g, h, i, b = b || "",
            d = a.shift,
            e = b.indexOf("C") > -1,
            f = e ? 7 : 3,
            b = b.split(" "),
            c = [].concat(c),
            j = function(a) {
                for (g = a.length; g--;)"M" === a[g] && a.splice(g + 1, 0, a[g + 1], a[g + 2], a[g + 1], a[g + 2])
            };
            if (e && (j(b), j(c)), a.isArea && (h = b.splice(b.length - 6, 6), i = c.splice(c.length - 6, 6)), d <= c.length / f && b.length === c.length) for (; d--;) c = [].concat(c).splice(0, f).concat(c);
            if (a.shift = 0, b.length) for (a = c.length; b.length < a;) d = [].concat(b).splice(b.length - f, f),
            e && (d[f - 6] = d[f - 2], d[f - 5] = d[f - 1]),
            b = b.concat(d);
            return h && (b = b.concat(h), c = c.concat(i)),
            [b, c]
        },
        step: function(a, b, c, d) {
            var e = [],
            f = a.length;
            if (1 === c) e = d;
            else if (f === b.length && 1 > c) for (; f--;) d = parseFloat(a[f]),
            e[f] = isNaN(d) ? a[f] : c * parseFloat(b[f] - d) + d;
            else e = b;
            return e
        }
    },
    function(a) {
        N.HighchartsAdapter = N.HighchartsAdapter || a && {
            init: function(b) {
                var e, c = a.fx,
                d = c.step,
                f = a.Tween,
                g = f && f.propHooks;
                e = a.cssHooks.opacity,
                a.extend(a.easing, {
                    easeOutQuad: function(a, b, c, d, e) {
                        return - d * (b /= e) * (b - 2) + c
                    }
                }),
                a.each(["cur", "_default", "width", "height", "opacity"],
                function(a, b) {
                    var k, l, e = d;
                    "cur" === b ? e = c.prototype: "_default" === b && f && (e = g[b], b = "set"),
                    (k = e[b]) && (e[b] = function(c) {
                        return c = a ? c: this,
                        "align" !== c.prop ? (l = c.elem, l.attr ? l.attr(c.prop, "cur" === b ? v: c.now) : k.apply(this, arguments)) : void 0
                    })
                }),
                mb(e, "get",
                function(a, b, c) {
                    return b.attr ? b.opacity || 0 : a.call(this, b, c)
                }),
                e = function(a) {
                    var d, c = a.elem;
                    a.started || (d = b.init(c, c.d, c.toD), a.start = d[0], a.end = d[1], a.started = !0),
                    c.attr("d", b.step(a.start, a.end, a.pos, c.toD))
                },
                f ? g.d = {
                    set: e
                }: d.d = e,
                this.each = Array.prototype.forEach ?
                function(a, b) {
                    return Array.prototype.forEach.call(a, b)
                }: function(a, b) {
                    for (var c = 0,
                    d = a.length; d > c; c++) if (b.call(a[c], a[c], c, a) === !1) return c
                },
                a.fn.highcharts = function() {
                    var c, d, a = "Chart",
                    b = arguments;
                    return ea(b[0]) && (a = b[0], b = Array.prototype.slice.call(b, 1)),
                    c = b[0],
                    c !== v && (c.chart = c.chart || {},
                    c.chart.renderTo = this[0], new Highcharts[a](c, b[1]), d = this),
                    c === v && (d = Ga[w(this[0], "data-highcharts-chart")]),
                    d
                }
            },
            getScript: a.getScript,
            inArray: a.inArray,
            adapterRun: function(b, c) {
                return a(b)[c]()
            },
            grep: a.grep,
            map: function(a, c) {
                for (var d = [], e = 0, f = a.length; f > e; e++) d[e] = c.call(a[e], a[e], e, a);
                return d
            },
            offset: function(b) {
                return a(b).offset()
            },
            addEvent: function(b, c, d) {
                a(b).bind(c, d)
            },
            removeEvent: function(b, c, d) {
                var e = z.removeEventListener ? "removeEventListener": "detachEvent";
                z[e] && b && !b[e] && (b[e] = function() {}),
                a(b).unbind(c, d)
            },
            fireEvent: function(b, c, d, e) {
                var h, f = a.Event(c),
                g = "detached" + c; ! sa && d && (delete d.layerX, delete d.layerY),
                s(f, d),
                b[c] && (b[g] = b[c], b[c] = null),
                a.each(["preventDefault", "stopPropagation"],
                function(a, b) {
                    var c = f[b];
                    f[b] = function() {
                        try {
                            c.call(f)
                        } catch(a) {
                            "preventDefault" === b && (h = !0)
                        }
                    }
                }),
                a(b).trigger(f),
                b[g] && (b[c] = b[g], b[g] = null),
                e && !f.isDefaultPrevented() && !h && e(f)
            },
            washMouseEvent: function(a) {
                var c = a.originalEvent || a;
                return c.pageX === v && (c.pageX = a.pageX, c.pageY = a.pageY),
                c
            },
            animate: function(b, c, d) {
                var e = a(b);
                b.style || (b.style = {}),
                c.d && (b.toD = c.d, c.d = 1),
                e.stop(),
                c.opacity !== v && b.attr && (c.opacity += "px"),
                e.animate(c, d)
            },
            stop: function(b) {
                a(b).stop()
            }
        }
    } (N.jQuery);
    var Y = N.HighchartsAdapter,
    G = Y || {};
    Y && Y.init.call(Y, Ab);
    var kb = G.adapterRun,
    Vb = G.getScript,
    pa = G.inArray,
    n = G.each,
    ub = G.grep,
    Wb = G.offset,
    Na = G.map,
    K = G.addEvent,
    $ = G.removeEvent,
    A = G.fireEvent,
    Xb = G.washMouseEvent,
    Bb = G.animate,
    Wa = G.stop,
    G = {
        enabled: !0,
        x: 0,
        y: 15,
        style: {
            color: "#666",
            cursor: "default",
            fontSize: "11px",
            lineHeight: "14px"
        }
    };
    L = {
        colors: "#2f7ed8,#0d233a,#8bbc21,#910000,#1aadce,#492970,#f28f43,#77a1e5,#c42525,#a6c96a".split(","),
        symbols: ["circle", "diamond", "square", "triangle", "triangle-down"],
        lang: {
            loading: "Loading...",
            months: "January,February,March,April,May,June,July,August,September,October,November,December".split(","),
            shortMonths: "Jan,Feb,Mar,Apr,May,Jun,Jul,Aug,Sep,Oct,Nov,Dec".split(","),
            weekdays: "Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday".split(","),
            decimalPoint: ".",
            numericSymbols: "k,M,G,T,P,E".split(","),
            resetZoom: "Reset zoom",
            resetZoomTitle: "Reset zoom level 1:1",
            thousandsSep: ","
        },
        global: {
            useUTC: !0,
            canvasToolsURL: "http://code.highcharts.com/3.0.7/modules/canvas-tools.js",
            VMLRadialGradientURL: "http://code.highcharts.com/3.0.7/gfx/vml-radial-gradient.png"
        },
        chart: {
            borderColor: "#4572A7",
            borderRadius: 5,
            defaultSeriesType: "line",
            ignoreHiddenSeries: !0,
            spacing: [10, 10, 15, 10],
            style: {
                fontFamily: '"Lucida Grande", "Lucida Sans Unicode", Verdana, Arial, Helvetica, sans-serif',
                fontSize: "12px"
            },
            backgroundColor: "#FFFFFF",
            plotBorderColor: "#C0C0C0",
            resetZoomButton: {
                theme: {
                    zIndex: 20
                },
                position: {
                    align: "right",
                    x: -10,
                    y: 10
                }
            }
        },
        title: {
            text: "Chart title",
            align: "center",
            margin: 15,
            style: {
                color: "#274b6d",
                fontSize: "16px"
            }
        },
        subtitle: {
            text: "",
            align: "center",
            style: {
                color: "#4d759e"
            }
        },
        plotOptions: {
            line: {
                allowPointSelect: !1,
                showCheckbox: !1,
                animation: {
                    duration: 1e3
                },
                events: {},
                lineWidth: 2,
                marker: {
                    enabled: !0,
                    lineWidth: 0,
                    radius: 4,
                    lineColor: "#FFFFFF",
                    states: {
                        hover: {
                            enabled: !0
                        },
                        select: {
                            fillColor: "#FFFFFF",
                            lineColor: "#000000",
                            lineWidth: 2
                        }
                    }
                },
                point: {
                    events: {}
                },
                dataLabels: x(G, {
                    align: "center",
                    enabled: !1,
                    formatter: function() {
                        return null === this.y ? "": Aa(this.y, -1)
                    },
                    verticalAlign: "bottom",
                    y: 0
                }),
                cropThreshold: 300,
                pointRange: 0,
                states: {
                    hover: {
                        marker: {}
                    },
                    select: {
                        marker: {}
                    }
                },
                stickyTracking: !0
            }
        },
        labels: {
            style: {
                position: "absolute",
                color: "#3E576F"
            }
        },
        legend: {
            enabled: !0,
            align: "center",
            layout: "horizontal",
            labelFormatter: function() {
                return this.name
            },
            borderWidth: 1,
            borderColor: "#909090",
            borderRadius: 5,
            navigation: {
                activeColor: "#274b6d",
                inactiveColor: "#CCC"
            },
            shadow: !1,
            itemStyle: {
                cursor: "pointer",
                color: "#274b6d",
                fontSize: "12px"
            },
            itemHoverStyle: {
                color: "#000"
            },
            itemHiddenStyle: {
                color: "#CCC"
            },
            itemCheckboxStyle: {
                position: "absolute",
                width: "13px",
                height: "13px"
            },
            symbolWidth: 16,
            symbolPadding: 5,
            verticalAlign: "bottom",
            x: 0,
            y: 0,
            title: {
                style: {
                    fontWeight: "bold"
                }
            }
        },
        loading: {
            labelStyle: {
                fontWeight: "bold",
                position: "relative",
                top: "1em"
            },
            style: {
                position: "absolute",
                backgroundColor: "white",
                opacity: .5,
                textAlign: "center"
            }
        },
        tooltip: {
            enabled: !0,
            animation: W,
            backgroundColor: "rgba(255, 255, 255, .85)",
            borderWidth: 1,
            borderRadius: 3,
            dateTimeLabelFormats: {
                millisecond: "%A, %b %e, %H:%M:%S.%L",
                second: "%A, %b %e, %H:%M:%S",
                minute: "%A, %b %e, %H:%M",
                hour: "%A, %b %e, %H:%M",
                day: "%A, %b %e, %Y",
                week: "Week from %A, %b %e, %Y",
                month: "%B %Y",
                year: "%Y"
            },
            headerFormat: '<span style="font-size: 10px">{point.key}</span><br/>',
            pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b><br/>',
            shadow: !0,
            snap: Ob ? 25 : 10,
            style: {
                color: "#333333",
                cursor: "default",
                fontSize: "12px",
                padding: "8px",
                whiteSpace: "nowrap"
            }
        },
        credits: {
            enabled: !0,
            text: "Highcharts.com",
            href: "http://www.highcharts.com",
            position: {
                align: "right",
                x: -10,
                verticalAlign: "bottom",
                y: -5
            },
            style: {
                cursor: "pointer",
                color: "#909090",
                fontSize: "9px"
            }
        }
    };
    var Z = L.plotOptions,
    Y = Z.line;
    Lb();
    var Yb = /rgba\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]?(?:\.[0-9]+)?)\s*\)/,
    Zb = /#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})/,
    $b = /rgb\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*\)/,
    qa = function(a) {
        var c, d, b = [];
        return function(a) {
            a && a.stops ? d = Na(a.stops,
            function(a) {
                return qa(a[1])
            }) : (c = Yb.exec(a)) ? b = [y(c[1]), y(c[2]), y(c[3]), parseFloat(c[4], 10)] : (c = Zb.exec(a)) ? b = [y(c[1], 16), y(c[2], 16), y(c[3], 16), 1] : (c = $b.exec(a)) && (b = [y(c[1]), y(c[2]), y(c[3]), 1])
        } (a),
        {
            get: function(c) {
                var f;
                return d ? (f = x(a), f.stops = [].concat(f.stops), n(d,
                function(a, b) {
                    f.stops[b] = [f.stops[b][0], a.get(c)]
                })) : f = b && !isNaN(b[0]) ? "rgb" === c ? "rgb(" + b[0] + "," + b[1] + "," + b[2] + ")": "a" === c ? b[3] : "rgba(" + b.join(",") + ")": a,
                f
            },
            brighten: function(a) {
                if (d) n(d,
                function(b) {
                    b.brighten(a)
                });
                else if (ra(a) && 0 !== a) {
                    var c;
                    for (c = 0; 3 > c; c++) b[c] += y(255 * a),
                    b[c] < 0 && (b[c] = 0),
                    b[c] > 255 && (b[c] = 255)
                }
                return this
            },
            rgba: b,
            setOpacity: function(a) {
                return b[3] = a,
                this
            }
        }
    };
    va.prototype = {
        init: function(a, b) {
            this.element = "span" === b ? U(b) : z.createElementNS(ya, b),
            this.renderer = a,
            this.attrSetters = {}
        },
        opacity: 1,
        animate: function(a, b, c) {
            b = o(b, Fa, !0),
            Wa(this),
            b ? (b = x(b), c && (b.complete = c), Bb(this, a, b)) : (this.attr(a), c && c())
        },
        attr: function(a, b) {
            var c, d, e, f, j, m, p, g = this.element,
            h = g.nodeName.toLowerCase(),
            i = this.renderer,
            k = this.attrSetters,
            l = this.shadows,
            q = this;
            if (ea(a) && u(b) && (c = a, a = {},
            a[c] = b), ea(a)) c = a,
            "circle" === h ? c = {
                x: "cx",
                y: "cy"
            } [c] || c: "strokeWidth" === c && (c = "stroke-width"),
            q = w(g, c) || this[c] || 0,
            "d" !== c && "visibility" !== c && "fill" !== c && (q = parseFloat(q));
            else {
                for (c in a) if (j = !1, d = a[c], e = k[c] && k[c].call(this, d, c), e !== !1) {
                    if (e !== v && (d = e), "d" === c) d && d.join && (d = d.join(" ")),
                    /(NaN| {2}|^$)/.test(d) && (d = "M 0 0");
                    else if ("x" === c && "text" === h) for (e = 0; e < g.childNodes.length; e++) f = g.childNodes[e],
                    w(f, "x") === w(g, "x") && w(f, "x", d);
                    else if (!this.rotation || "x" !== c && "y" !== c) if ("fill" === c) d = i.color(d, g, c);
                    else if ("circle" !== h || "x" !== c && "y" !== c) if ("rect" === h && "r" === c) w(g, {
                        rx: d,
                        ry: d
                    }),
                    j = !0;
                    else if ("translateX" === c || "translateY" === c || "rotation" === c || "verticalAlign" === c || "scaleX" === c || "scaleY" === c) j = p = !0;
                    else if ("stroke" === c) d = i.color(d, g, c);
                    else if ("dashstyle" === c) {
                        if (c = "stroke-dasharray", d = d && d.toLowerCase(), "solid" === d) d = S;
                        else if (d) {
                            for (d = d.replace("shortdashdotdot", "3,1,1,1,1,1,").replace("shortdashdot", "3,1,1,1").replace("shortdot", "1,1,").replace("shortdash", "3,1,").replace("longdash", "8,3,").replace(/dot/g, "1,3,").replace("dash", "4,3,").replace(/,$/, "").split(","), e = d.length; e--;) d[e] = y(d[e]) * o(a["stroke-width"], this["stroke-width"]);
                            d = d.join(",")
                        }
                    } else "width" === c ? d = y(d) : "align" === c ? (c = "text-anchor", d = {
                        left: "start",
                        center: "middle",
                        right: "end"
                    } [d]) : "title" === c && (e = g.getElementsByTagName("title")[0], e || (e = z.createElementNS(ya, "title"), g.appendChild(e)), e.textContent = d);
                    else c = {
                        x: "cx",
                        y: "cy"
                    } [c] || c;
                    else p = !0;
                    if ("strokeWidth" === c && (c = "stroke-width"), ("stroke-width" === c || "stroke" === c) && (this[c] = d, this.stroke && this["stroke-width"] ? (w(g, "stroke", this.stroke), w(g, "stroke-width", this["stroke-width"]), this.hasStroke = !0) : "stroke-width" === c && 0 === d && this.hasStroke && (g.removeAttribute("stroke"), this.hasStroke = !1), j = !0), this.symbolName && /^(x|y|width|height|r|start|end|innerR|anchorX|anchorY)/.test(c) && (m || (this.symbolAttr(a), m = !0), j = !0), l && /^(width|height|visibility|x|y|d|transform|cx|cy|r)$/.test(c)) for (e = l.length; e--;) w(l[e], c, "height" === c ? r(d - (l[e].cutHeight || 0), 0) : d); ("width" === c || "height" === c) && "rect" === h && 0 > d && (d = 0),
                    this[c] = d,
                    "text" === c ? (d !== this.textStr && delete this.bBox, this.textStr = d, this.added && i.buildText(this)) : j || w(g, c, d)
                }
                p && this.updateTransform()
            }
            return q
        },
        addClass: function(a) {
            var b = this.element,
            c = w(b, "class") || "";
            return - 1 === c.indexOf(a) && w(b, "class", c + " " + a),
            this
        },
        symbolAttr: function(a) {
            var b = this;
            n("x,y,r,start,end,width,height,innerR,anchorX,anchorY".split(","),
            function(c) {
                b[c] = o(a[c], b[c])
            }),
            b.attr({
                d: b.renderer.symbols[b.symbolName](b.x, b.y, b.width, b.height, b)
            })
        },
        clip: function(a) {
            return this.attr("clip-path", a ? "url(" + this.renderer.url + "#" + a.id + ")": S)
        },
        crisp: function(a, b, c, d, e) {
            var f, i, g = {},
            h = {},
            a = a || this.strokeWidth || this.attr && this.attr("stroke-width") || 0;
            i = t(a) % 2 / 2,
            h.x = P(b || this.x || 0) + i,
            h.y = P(c || this.y || 0) + i,
            h.width = P((d || this.width || 0) - 2 * i),
            h.height = P((e || this.height || 0) - 2 * i),
            h.strokeWidth = a;
            for (f in h) this[f] !== h[f] && (this[f] = g[f] = h[f]);
            return g
        },
        css: function(a) {
            var d, b = this.element,
            c = this.textWidth = a && a.width && "text" === b.nodeName.toLowerCase() && y(a.width),
            e = "",
            f = function(a, b) {
                return "-" + b.toLowerCase()
            };
            if (a && a.color && (a.fill = a.color), this.styles = a = s(this.styles, a), c && delete a.width, sa && !W) I(this.element, a);
            else {
                for (d in a) e += d.replace(/([A-Z])/g, f) + ":" + a[d] + ";";
                w(b, "style", e)
            }
            return c && this.added && this.renderer.buildText(this),
            this
        },
        on: function(a, b) {
            var c = this,
            d = c.element;
            return jb && "click" === a ? (d.ontouchstart = function(a) {
                c.touchEventFired = Date.now(),
                a.preventDefault(),
                b.call(d, a)
            },
            d.onclick = function(a) { ( - 1 === na.indexOf("Android") || Date.now() - (c.touchEventFired || 0) > 1100) && b.call(d, a)
            }) : d["on" + a] = b,
            this
        },
        setRadialReference: function(a) {
            return this.element.radialReference = a,
            this
        },
        translate: function(a, b) {
            return this.attr({
                translateX: a,
                translateY: b
            })
        },
        invert: function() {
            return this.inverted = !0,
            this.updateTransform(),
            this
        },
        htmlCss: function(a) {
            var b = this.element;
            return (b = a && "SPAN" === b.tagName && a.width) && (delete a.width, this.textWidth = b, this.updateTransform()),
            this.styles = s(this.styles, a),
            I(this.element, a),
            this
        },
        htmlGetBBox: function() {
            var a = this.element,
            b = this.bBox;
            return b || ("text" === a.nodeName && (a.style.position = "absolute"), b = this.bBox = {
                x: a.offsetLeft,
                y: a.offsetTop,
                width: a.offsetWidth,
                height: a.offsetHeight
            }),
            b
        },
        htmlUpdateTransform: function() {
            if (this.added) {
                var a = this.renderer,
                b = this.element,
                c = this.translateX || 0,
                d = this.translateY || 0,
                e = this.x || 0,
                f = this.y || 0,
                g = this.textAlign || "left",
                h = {
                    left: 0,
                    center: .5,
                    right: 1
                } [g],
                i = g && "left" !== g,
                j = this.shadows;
                if (I(b, {
                    marginLeft: c,
                    marginTop: d
                }), j && n(j,
                function(a) {
                    I(a, {
                        marginLeft: c + 1,
                        marginTop: d + 1
                    })
                }), this.inverted && n(b.childNodes,
                function(c) {
                    a.invertChild(c, b)
                }), "SPAN" === b.tagName) {
                    var k, l, m, j = this.rotation;
                    k = 0;
                    var aa, p = 1,
                    q = 0;
                    m = y(this.textWidth);
                    var B = this.xCorr || 0,
                    O = this.yCorr || 0,
                    Sb = [j, g, b.innerHTML, this.textWidth].join(",");
                    Sb !== this.cTT && (u(j) && (k = j * Ua, p = V(k), q = ba(k), this.setSpanRotation(j, q, p)), k = o(this.elemWidth, b.offsetWidth), l = o(this.elemHeight, b.offsetHeight), k > m && /[ \-]/.test(b.textContent || b.innerText) && (I(b, {
                        width: m + "px",
                        display: "block",
                        whiteSpace: "normal"
                    }), k = m), m = a.fontMetrics(b.style.fontSize).b, B = 0 > p && -k, O = 0 > q && -l, aa = 0 > p * q, B += q * m * (aa ? 1 - h: h), O -= p * m * (j ? aa ? h: 1 - h: 1), i && (B -= k * h * (0 > p ? -1 : 1), j && (O -= l * h * (0 > q ? -1 : 1)), I(b, {
                        textAlign: g
                    })), this.xCorr = B, this.yCorr = O),
                    I(b, {
                        left: e + B + "px",
                        top: f + O + "px"
                    }),
                    hb && (l = b.offsetHeight),
                    this.cTT = Sb
                }
            } else this.alignOnAdd = !0
        },
        setSpanRotation: function(a) {
            var b = {};
            b[sa ? "-ms-transform": hb ? "-webkit-transform": ib ? "MozTransform": Nb ? "-o-transform": ""] = b.transform = "rotate(" + a + "deg)",
            I(this.element, b)
        },
        updateTransform: function() {
            var a = this.translateX || 0,
            b = this.translateY || 0,
            c = this.scaleX,
            d = this.scaleY,
            e = this.inverted,
            f = this.rotation;
            e && (a += this.attr("width"), b += this.attr("height")),
            a = ["translate(" + a + "," + b + ")"],
            e ? a.push("rotate(90) scale(-1,1)") : f && a.push("rotate(" + f + " " + (this.x || 0) + " " + (this.y || 0) + ")"),
            (u(c) || u(d)) && a.push("scale(" + o(c, 1) + " " + o(d, 1) + ")"),
            a.length && w(this.element, "transform", a.join(" "))
        },
        toFront: function() {
            var a = this.element;
            return a.parentNode.appendChild(a),
            this
        },
        align: function(a, b, c) {
            var d, e, f, g, h = {};
            return e = this.renderer,
            f = e.alignedObjects,
            a ? (this.alignOptions = a, this.alignByTranslate = b, (!c || ea(c)) && (this.alignTo = d = c || "renderer", ga(f, this), f.push(this), c = null)) : (a = this.alignOptions, b = this.alignByTranslate, d = this.alignTo),
            c = o(c, e[d], e),
            d = a.align,
            e = a.verticalAlign,
            f = (c.x || 0) + (a.x || 0),
            g = (c.y || 0) + (a.y || 0),
            ("right" === d || "center" === d) && (f += (c.width - (a.width || 0)) / {
                right: 1,
                center: 2
            } [d]),
            h[b ? "translateX": "x"] = t(f),
            ("bottom" === e || "middle" === e) && (g += (c.height - (a.height || 0)) / ({
                bottom: 1,
                middle: 2
            } [e] || 1)),
            h[b ? "translateY": "y"] = t(g),
            this[this.placed ? "animate": "attr"](h),
            this.placed = !0,
            this.alignAttr = h,
            this
        },
        getBBox: function() {
            var c, a = this.bBox,
            b = this.renderer,
            d = this.rotation;
            c = this.element;
            var e = this.styles,
            f = d * Ua;
            if (!a) {
                if (c.namespaceURI === ya || b.forExport) {
                    try {
                        a = c.getBBox ? s({},
                        c.getBBox()) : {
                            width: c.offsetWidth,
                            height: c.offsetHeight
                        }
                    } catch(g) {} (!a || a.width < 0) && (a = {
                        width: 0,
                        height: 0
                    })
                } else a = this.htmlGetBBox();
                b.isSVG && (b = a.width, c = a.height, sa && e && "11px" === e.fontSize && "22.7" === c.toPrecision(3) && (a.height = c = 14), d && (a.width = M(c * ba(f)) + M(b * V(f)), a.height = M(c * V(f)) + M(b * ba(f)))),
                this.bBox = a
            }
            return a
        },
        show: function() {
            return this.attr({
                visibility: "visible"
            })
        },
        hide: function() {
            return this.attr({
                visibility: "hidden"
            })
        },
        fadeOut: function(a) {
            var b = this;
            b.animate({
                opacity: 0
            },
            {
                duration: a || 150,
                complete: function() {
                    b.hide()
                }
            })
        },
        add: function(a) {
            var h, b = this.renderer,
            c = a || b,
            d = c.element || b.box,
            e = d.childNodes,
            f = this.element,
            g = w(f, "zIndex");
            if (a && (this.parentGroup = a), this.parentInverted = a && a.inverted, void 0 !== this.textStr && b.buildText(this), g && (c.handleZ = !0, g = y(g)), c.handleZ) for (c = 0; c < e.length; c++) if (a = e[c], b = w(a, "zIndex"), a !== f && (y(b) > g || !u(g) && u(b))) {
                d.insertBefore(f, a),
                h = !0;
                break
            }
            return h || d.appendChild(f),
            this.added = !0,
            A(this, "add"),
            this
        },
        safeRemoveChild: function(a) {
            var b = a.parentNode;
            b && b.removeChild(a)
        },
        destroy: function() {
            var e, f, a = this,
            b = a.element || {},
            c = a.shadows,
            d = a.renderer.isSVG && "SPAN" === b.nodeName && a.parentGroup;
            if (b.onclick = b.onmouseout = b.onmouseover = b.onmousemove = b.point = null, Wa(a), a.clipPath && (a.clipPath = a.clipPath.destroy()), a.stops) {
                for (f = 0; f < a.stops.length; f++) a.stops[f] = a.stops[f].destroy();
                a.stops = null
            }
            for (a.safeRemoveChild(b), c && n(c,
            function(b) {
                a.safeRemoveChild(b)
            }); d && 0 === d.div.childNodes.length;) b = d.parentGroup,
            a.safeRemoveChild(d.div),
            delete d.div,
            d = b;
            a.alignTo && ga(a.renderer.alignedObjects, a);
            for (e in a) delete a[e];
            return null
        },
        shadow: function(a, b, c) {
            var e, f, h, i, j, k, d = [],
            g = this.element;
            if (a) {
                for (i = o(a.width, 3), j = (a.opacity || .15) / i, k = this.parentInverted ? "(-1,-1)": "(" + o(a.offsetX, 1) + ", " + o(a.offsetY, 1) + ")", e = 1; i >= e; e++) f = g.cloneNode(0),
                h = 2 * i + 1 - 2 * e,
                w(f, {
                    isShadow: "true",
                    stroke: a.color || "black",
                    "stroke-opacity": j * e,
                    "stroke-width": h,
                    transform: "translate" + k,
                    fill: S
                }),
                c && (w(f, "height", r(w(f, "height") - h, 0)), f.cutHeight = h),
                b ? b.element.appendChild(f) : g.parentNode.insertBefore(f, g),
                d.push(f);
                this.shadows = d
            }
            return this
        }
    };
    var za = function() {
        this.init.apply(this, arguments)
    };
    za.prototype = {
        Element: va,
        init: function(a, b, c, d) {
            var f, g, e = location;
            f = this.createElement("svg").attr({
                version: "1.1"
            }),
            g = f.element,
            a.appendChild(g),
            -1 === a.innerHTML.indexOf("xmlns") && w(g, "xmlns", ya),
            this.isSVG = !0,
            this.box = g,
            this.boxWrapper = f,
            this.alignedObjects = [],
            this.url = (ib || hb) && z.getElementsByTagName("base").length ? e.href.replace(/#.*?$/, "").replace(/([\('\)])/g, "\\$1").replace(/ /g, "%20") : "",
            this.createElement("desc").add().element.appendChild(z.createTextNode("Created with Highcharts 3.0.7")),
            this.defs = this.createElement("defs").add(),
            this.forExport = d,
            this.gradients = {},
            this.setSize(b, c, !1);
            var h;
            ib && a.getBoundingClientRect && (this.subPixelFix = b = function() {
                I(a, {
                    left: 0,
                    top: 0
                }),
                h = a.getBoundingClientRect(),
                I(a, {
                    left: wa(h.left) - h.left + "px",
                    top: wa(h.top) - h.top + "px"
                })
            },
            b(), K(N, "resize", b))
        },
        isHidden: function() {
            return ! this.boxWrapper.getBBox().width
        },
        destroy: function() {
            var a = this.defs;
            return this.box = null,
            this.boxWrapper = this.boxWrapper.destroy(),
            Ka(this.gradients || {}),
            this.gradients = null,
            a && (this.defs = a.destroy()),
            this.subPixelFix && $(N, "resize", this.subPixelFix),
            this.alignedObjects = null
        },
        createElement: function(a) {
            var b = new this.Element;
            return b.init(this, a),
            b
        },
        draw: function() {},
        buildText: function(a) {
            for (var b = a.element,
            c = this,
            d = c.forExport,
            e = o(a.textStr, "").toString().replace(/<(b|strong)>/g, '<span style="font-weight:bold">').replace(/<(i|em)>/g, '<span style="font-style:italic">').replace(/<a/g, "<span").replace(/<\/(b|strong|i|em|a)>/g, "</span>").split(/<br.*?>/g), f = b.childNodes, g = /style="([^"]+)"/, h = /href="(http[^"]+)"/, i = w(b, "x"), j = a.styles, k = a.textWidth, l = j && j.lineHeight, m = f.length; m--;) b.removeChild(f[m]);
            k && !a.added && this.box.appendChild(b),
            "" === e[e.length - 1] && e.pop(),
            n(e,
            function(e, f) {
                var m, o = 0,
                e = e.replace(/<span/g, "|||<span").replace(/<\/span>/g, "</span>|||");
                m = e.split("|||"),
                n(m,
                function(e) {
                    if ("" !== e || 1 === m.length) {
                        var r, p = {},
                        n = z.createElementNS(ya, "tspan");
                        if (g.test(e) && (r = e.match(g)[1].replace(/(;| |^)color([ :])/, "$1fill$2"), w(n, "style", r)), h.test(e) && !d && (w(n, "onclick", 'location.href="' + e.match(h)[1] + '"'), I(n, {
                            cursor: "pointer"
                        })), e = (e.replace(/<(.|\n)*?>/g, "") || " ").replace(/&lt;/g, "<").replace(/&gt;/g, ">"), " " !== e && (n.appendChild(z.createTextNode(e)), o ? p.dx = 0 : p.x = i, w(n, p), !o && f && (!W && d && I(n, {
                            display: "block"
                        }), w(n, "dy", l || c.fontMetrics(/px$/.test(n.style.fontSize) ? n.style.fontSize: j.fontSize).h, hb && n.offsetHeight)), b.appendChild(n), o++, k)) for (var u, t, e = e.replace(/([^\^])-/g, "$1- ").split(" "), p = a._clipHeight, E = [], v = y(l || 16), s = 1; e.length || E.length;) delete a.bBox,
                        u = a.getBBox(),
                        t = u.width,
                        !W && c.forExport && (t = c.measureSpanWidth(n.firstChild.data, a.styles)),
                        u = t > k,
                        u && 1 !== e.length ? (n.removeChild(n.firstChild), E.unshift(e.pop())) : (e = E, E = [], e.length && (s++, p && s * v > p ? (e = ["..."], a.attr("title", a.textStr)) : (n = z.createElementNS(ya, "tspan"), w(n, {
                            dy: v,
                            x: i
                        }), r && w(n, "style", r), b.appendChild(n), t > k && (k = t)))),
                        e.length && n.appendChild(z.createTextNode(e.join(" ").replace(/- /g, "-")))
                    }
                })
            })
        },
        button: function(a, b, c, d, e, f, g, h, i) {
            var l, m, p, q, n, o, j = this.label(a, b, c, i, null, null, null, null, "button"),
            k = 0,
            a = {
                x1: 0,
                y1: 0,
                x2: 0,
                y2: 1
            },
            e = x({
                "stroke-width": 1,
                stroke: "#CCCCCC",
                fill: {
                    linearGradient: a,
                    stops: [[0, "#FEFEFE"], [1, "#F6F6F6"]]
                },
                r: 2,
                padding: 5,
                style: {
                    color: "black"
                }
            },
            e);
            return p = e.style,
            delete e.style,
            f = x(e, {
                stroke: "#68A",
                fill: {
                    linearGradient: a,
                    stops: [[0, "#FFF"], [1, "#ACF"]]
                }
            },
            f),
            q = f.style,
            delete f.style,
            g = x(e, {
                stroke: "#68A",
                fill: {
                    linearGradient: a,
                    stops: [[0, "#9BD"], [1, "#CDF"]]
                }
            },
            g),
            n = g.style,
            delete g.style,
            h = x(e, {
                style: {
                    color: "#CCC"
                }
            },
            h),
            o = h.style,
            delete h.style,
            K(j.element, sa ? "mouseover": "mouseenter",
            function() {
                3 !== k && j.attr(f).css(q)
            }),
            K(j.element, sa ? "mouseout": "mouseleave",
            function() {
                3 !== k && (l = [e, f, g][k], m = [p, q, n][k], j.attr(l).css(m))
            }),
            j.setState = function(a) { (j.state = k = a) ? 2 === a ? j.attr(g).css(n) : 3 === a && j.attr(h).css(o) : j.attr(e).css(p)
            },
            j.on("click",
            function() {
                3 !== k && d.call(j)
            }).attr(e).css(s({
                cursor: "default"
            },
            p))
        },
        crispLine: function(a, b) {
            return a[1] === a[4] && (a[1] = a[4] = t(a[1]) - b % 2 / 2),
            a[2] === a[5] && (a[2] = a[5] = t(a[2]) + b % 2 / 2),
            a
        },
        path: function(a) {
            var b = {
                fill: S
            };
            return Ia(a) ? b.d = a: T(a) && s(b, a),
            this.createElement("path").attr(b)
        },
        circle: function(a, b, c) {
            return a = T(a) ? a: {
                x: a,
                y: b,
                r: c
            },
            this.createElement("circle").attr(a)
        },
        arc: function(a, b, c, d, e, f) {
            return T(a) && (b = a.y, c = a.r, d = a.innerR, e = a.start, f = a.end, a = a.x),
            a = this.symbol("arc", a || 0, b || 0, c || 0, c || 0, {
                innerR: d || 0,
                start: e || 0,
                end: f || 0
            }),
            a.r = c,
            a
        },
        rect: function(a, b, c, d, e, f) {
            return e = T(a) ? a.r: e,
            e = this.createElement("rect").attr({
                rx: e,
                ry: e,
                fill: S
            }),
            e.attr(T(a) ? a: e.crisp(f, a, b, r(c, 0), r(d, 0)))
        },
        setSize: function(a, b, c) {
            var d = this.alignedObjects,
            e = d.length;
            for (this.width = a, this.height = b, this.boxWrapper[o(c, !0) ? "animate": "attr"]({
                width: a,
                height: b
            }); e--;) d[e].align()
        },
        g: function(a) {
            var b = this.createElement("g");
            return u(a) ? b.attr({
                "class": "highcharts-" + a
            }) : b
        },
        image: function(a, b, c, d, e) {
            var f = {
                preserveAspectRatio: S
            };
            return arguments.length > 1 && s(f, {
                x: b,
                y: c,
                width: d,
                height: e
            }),
            f = this.createElement("image").attr(f),
            f.element.setAttributeNS ? f.element.setAttributeNS("http://www.w3.org/1999/xlink", "href", a) : f.element.setAttribute("hc-svg-href", a),
            f
        },
        symbol: function(a, b, c, d, e, f) {
            var g, j, k, h = this.symbols[a],
            h = h && h(t(b), t(c), d, e, f),
            i = /^url\((.*?)\)$/;
            return h ? (g = this.path(h), s(g, {
                symbolName: a,
                x: b,
                y: c,
                width: d,
                height: e
            }), f && s(g, f)) : i.test(a) && (k = function(a, b) {
                a.element && (a.attr({
                    width: b[0],
                    height: b[1]
                }), a.alignByTranslate || a.translate(t((d - b[0]) / 2), t((e - b[1]) / 2)))
            },
            j = a.match(i)[1], a = Pb[j], g = this.image(j).attr({
                x: b,
                y: c
            }), g.isImg = !0, a ? k(g, a) : (g.attr({
                width: 0,
                height: 0
            }), U("img", {
                onload: function() {
                    k(g, Pb[j] = [this.width, this.height])
                },
                src: j
            }))),
            g
        },
        symbols: {
            circle: function(a, b, c, d) {
                var e = .166 * c;
                return ["M", a + c / 2, b, "C", a + c + e, b, a + c + e, b + d, a + c / 2, b + d, "C", a - e, b + d, a - e, b, a + c / 2, b, "Z"]
            },
            square: function(a, b, c, d) {
                return ["M", a, b, "L", a + c, b, a + c, b + d, a, b + d, "Z"]
            },
            triangle: function(a, b, c, d) {
                return ["M", a + c / 2, b, "L", a + c, b + d, a, b + d, "Z"]
            },
            "triangle-down": function(a, b, c, d) {
                return ["M", a, b, "L", a + c, b, a + c / 2, b + d, "Z"]
            },
            diamond: function(a, b, c, d) {
                return ["M", a + c / 2, b, "L", a + c, b + d / 2, a + c / 2, b + d, a, b + d / 2, "Z"]
            },
            arc: function(a, b, c, d, e) {
                var f = e.start,
                c = e.r || c || d,
                g = e.end - .001,
                d = e.innerR,
                h = e.open,
                i = V(f),
                j = ba(f),
                k = V(g),
                g = ba(g),
                e = e.end - f < xa ? 0 : 1;
                return ["M", a + c * i, b + c * j, "A", c, c, 0, e, 1, a + c * k, b + c * g, h ? "M": "L", a + d * k, b + d * g, "A", d, d, 0, e, 0, a + d * i, b + d * j, h ? "": "Z"]
            }
        },
        clipRect: function(a, b, c, d) {
            var e = "highcharts-" + zb++,
            f = this.createElement("clipPath").attr({
                id: e
            }).add(this.defs),
            a = this.rect(a, b, c, d, 0).add(f);
            return a.id = e,
            a.clipPath = f,
            a
        },
        color: function(a, b, c) {
            var e, g, h, i, j, k, l, m, d = this,
            f = /^rgba/,
            p = [];
            if (a && a.linearGradient ? g = "linearGradient": a && a.radialGradient && (g = "radialGradient"), g) {
                c = a[g],
                h = d.gradients,
                j = a.stops,
                b = b.radialReference,
                Ia(c) && (a[g] = c = {
                    x1: c[0],
                    y1: c[1],
                    x2: c[2],
                    y2: c[3],
                    gradientUnits: "userSpaceOnUse"
                }),
                "radialGradient" === g && b && !u(c.gradientUnits) && (c = x(c, {
                    cx: b[0] - b[2] / 2 + c.cx * b[2],
                    cy: b[1] - b[2] / 2 + c.cy * b[2],
                    r: c.r * b[2],
                    gradientUnits: "userSpaceOnUse"
                }));
                for (m in c)"id" !== m && p.push(m, c[m]);
                for (m in j) p.push(j[m]);
                return p = p.join(","),
                h[p] ? a = h[p].id: (c.id = a = "highcharts-" + zb++, h[p] = i = d.createElement(g).attr(c).add(d.defs), i.stops = [], n(j,
                function(a) {
                    f.test(a[1]) ? (e = qa(a[1]), k = e.get("rgb"), l = e.get("a")) : (k = a[1], l = 1),
                    a = d.createElement("stop").attr({
                        offset: a[0],
                        "stop-color": k,
                        "stop-opacity": l
                    }).add(i),
                    i.stops.push(a)
                })),
                "url(" + d.url + "#" + a + ")"
            }
            return f.test(a) ? (e = qa(a), w(b, c + "-opacity", e.get("a")), e.get("rgb")) : (b.removeAttribute(c + "-opacity"), a)
        },
        text: function(a, b, c, d) {
            var e = L.chart.style,
            f = ca || !W && this.forExport;
            return d && !this.forExport ? this.html(a, b, c) : (b = t(o(b, 0)), c = t(o(c, 0)), a = this.createElement("text").attr({
                x: b,
                y: c,
                text: a
            }).css({
                fontFamily: e.fontFamily,
                fontSize: e.fontSize
            }), f && a.css({
                position: "absolute"
            }), a.x = b, a.y = c, a)
        },
        html: function(a, b, c) {
            var d = L.chart.style,
            e = this.createElement("span"),
            f = e.attrSetters,
            g = e.element,
            h = e.renderer;
            return f.text = function(a) {
                return a !== g.innerHTML && delete this.bBox,
                g.innerHTML = a,
                !1
            },
            f.x = f.y = f.align = function(a, b) {
                return "align" === b && (b = "textAlign"),
                e[b] = a,
                e.htmlUpdateTransform(),
                !1
            },
            e.attr({
                text: a,
                x: t(b),
                y: t(c)
            }).css({
                position: "absolute",
                whiteSpace: "nowrap",
                fontFamily: d.fontFamily,
                fontSize: d.fontSize
            }),
            e.css = e.htmlCss,
            h.isSVG && (e.add = function(a) {
                var b, c = h.box.parentNode,
                d = [];
                if (this.parentGroup = a) {
                    if (b = a.div, !b) {
                        for (; a;) d.push(a),
                        a = a.parentGroup;
                        n(d.reverse(),
                        function(a) {
                            var d;
                            b = a.div = a.div || U(Ea, {
                                className: w(a.element, "class")
                            },
                            {
                                position: "absolute",
                                left: (a.translateX || 0) + "px",
                                top: (a.translateY || 0) + "px"
                            },
                            b || c),
                            d = b.style,
                            s(a.attrSetters, {
                                translateX: function(a) {
                                    d.left = a + "px"
                                },
                                translateY: function(a) {
                                    d.top = a + "px"
                                },
                                visibility: function(a, b) {
                                    d[b] = a
                                }
                            })
                        })
                    }
                } else b = c;
                return b.appendChild(g),
                e.added = !0,
                e.alignOnAdd && e.htmlUpdateTransform(),
                e
            }),
            e
        },
        fontMetrics: function(a) {
            var a = y(a || 11),
            a = 24 > a ? a + 4 : t(1.2 * a),
            b = t(.8 * a);
            return {
                h: a,
                b: b
            }
        },
        label: function(a, b, c, d, e, f, g, h, i) {
            function j() {
                var a, b;
                a = o.element.style,
                O = (void 0 === Oa || void 0 === Ha || q.styles.textAlign) && o.getBBox(),
                q.width = (Oa || O.width || 0) + 2 * da + lb,
                q.height = (Ha || O.height || 0) + 2 * da,
                w = da + p.fontMetrics(a && a.fontSize).b,
                y && (B || (a = t( - r * da), b = h ? -w: 0, q.box = B = d ? p.symbol(d, a, b, q.width, q.height, Xa) : p.rect(a, b, q.width, q.height, 0, Xa[Rb]), B.add(q)), B.isImg || B.attr(x({
                    width: q.width,
                    height: q.height
                },
                Xa)), Xa = null)
            }
            function k() {
                var c, a = q.styles,
                a = a && a.textAlign,
                b = lb + da * (1 - r);
                c = h ? 0 : w,
                !u(Oa) || "center" !== a && "right" !== a || (b += {
                    center: .5,
                    right: 1
                } [a] * (Oa - O.width)),
                (b !== o.x || c !== o.y) && o.attr({
                    x: b,
                    y: c
                }),
                o.x = b,
                o.y = c
            }
            function l(a, b) {
                B ? B.attr(a, b) : Xa[a] = b
            }
            function m() {
                o.add(q),
                q.attr({
                    text: a,
                    x: b,
                    y: c
                }),
                B && u(e) && q.attr({
                    anchorX: e,
                    anchorY: f
                })
            }
            var B, O, Oa, Ha, E, H, w, y, p = this,
            q = p.g(i),
            o = p.text("", 0, 0, g).attr({
                zIndex: 1
            }),
            r = 0,
            da = 3,
            lb = 0,
            C = 0,
            Xa = {},
            g = q.attrSetters;
            K(q, "add", m),
            g.width = function(a) {
                return Oa = a,
                !1
            },
            g.height = function(a) {
                return Ha = a,
                !1
            },
            g.padding = function(a) {
                return u(a) && a !== da && (da = a, k()),
                !1
            },
            g.paddingLeft = function(a) {
                return u(a) && a !== lb && (lb = a, k()),
                !1
            },
            g.align = function(a) {
                return r = {
                    left: 0,
                    center: .5,
                    right: 1
                } [a],
                !1
            },
            g.text = function(a, b) {
                return o.attr(b, a),
                j(),
                k(),
                !1
            },
            g[Rb] = function(a, b) {
                return y = !0,
                C = a % 2 / 2,
                l(b, a),
                !1
            },
            g.stroke = g.fill = g.r = function(a, b) {
                return "fill" === b && (y = !0),
                l(b, a),
                !1
            },
            g.anchorX = function(a, b) {
                return e = a,
                l(b, a + C - E),
                !1
            },
            g.anchorY = function(a, b) {
                return f = a,
                l(b, a - H),
                !1
            },
            g.x = function(a) {
                return q.x = a,
                a -= r * ((Oa || O.width) + da),
                E = t(a),
                q.attr("translateX", E),
                !1
            },
            g.y = function(a) {
                return H = q.y = t(a),
                q.attr("translateY", H),
                !1
            };
            var z = q.css;
            return s(q, {
                css: function(a) {
                    if (a) {
                        var b = {},
                        a = x(a);
                        n("fontSize,fontWeight,fontFamily,color,lineHeight,width,textDecoration,textShadow".split(","),
                        function(c) {
                            a[c] !== v && (b[c] = a[c], delete a[c])
                        }),
                        o.css(b)
                    }
                    return z.call(q, a)
                },
                getBBox: function() {
                    return {
                        width: O.width + 2 * da,
                        height: O.height + 2 * da,
                        x: O.x - da,
                        y: O.y - da
                    }
                },
                shadow: function(a) {
                    return B && B.shadow(a),
                    q
                },
                destroy: function() {
                    $(q, "add", m),
                    $(q.element, "mouseenter"),
                    $(q.element, "mouseleave"),
                    o && (o = o.destroy()),
                    B && (B = B.destroy()),
                    va.prototype.destroy.call(q),
                    q = p = j = k = l = m = null
                }
            })
        }
    },
    Va = za;
    var F;
    if (!W && !ca) {
        Highcharts.VMLElement = F = {
            init: function(a, b) {
                var c = ["<", b, ' filled="f" stroked="f"'],
                d = ["position: ", "absolute", ";"],
                e = b === Ea; ("shape" === b || e) && d.push("left:0;top:0;width:1px;height:1px;"),
                d.push("visibility: ", e ? "hidden": "visible"),
                c.push(' style="', d.join(""), '"/>'),
                b && (c = e || "span" === b || "img" === b ? c.join("") : a.prepVML(c), this.element = U(c)),
                this.renderer = a,
                this.attrSetters = {}
            },
            add: function(a) {
                var b = this.renderer,
                c = this.element,
                d = b.box,
                d = a ? a.element || a: d;
                return a && a.inverted && b.invertChild(c, d),
                d.appendChild(c),
                this.added = !0,
                this.alignOnAdd && !this.deferUpdateTransform && this.updateTransform(),
                A(this, "add"),
                this
            },
            updateTransform: va.prototype.htmlUpdateTransform,
            setSpanRotation: function(a, b, c) {
                I(this.element, {
                    filter: a ? ["progid:DXImageTransform.Microsoft.Matrix(M11=", c, ", M12=", -b, ", M21=", b, ", M22=", c, ", sizingMethod='auto expand')"].join("") : S
                })
            },
            pathToVML: function(a) {
                for (var d, b = a.length,
                c = []; b--;) ra(a[b]) ? c[b] = t(10 * a[b]) - 5 : "Z" === a[b] ? c[b] = "x": (c[b] = a[b], !a.isArc || "wa" !== a[b] && "at" !== a[b] || (d = "wa" === a[b] ? 1 : -1, c[b + 5] === c[b + 7] && (c[b + 7] -= d), c[b + 6] === c[b + 8] && (c[b + 8] -= d)));
                return c.join(" ") || "x"
            },
            attr: function(a, b) {
                var c, d, e, k, m, f = this.element || {},
                g = f.style,
                h = f.nodeName,
                i = this.renderer,
                j = this.symbolName,
                l = this.shadows,
                p = this.attrSetters,
                q = this;
                if (ea(a) && u(b) && (c = a, a = {},
                a[c] = b), ea(a)) c = a,
                q = "strokeWidth" === c || "stroke-width" === c ? this.strokeweight: this[c];
                else for (c in a) if (d = a[c], m = !1, e = p[c] && p[c].call(this, d, c), e !== !1 && null !== d) {
                    if (e !== v && (d = e), j && /^(x|y|r|start|end|width|height|innerR|anchorX|anchorY)/.test(c)) k || (this.symbolAttr(a), k = !0),
                    m = !0;
                    else if ("d" === c) {
                        if (d = d || [], this.d = d.join(" "), f.path = d = this.pathToVML(d), l) for (e = l.length; e--;) l[e].path = l[e].cutOff ? this.cutOffPath(d, l[e].cutOff) : d;
                        m = !0
                    } else if ("visibility" === c) {
                        if (l) for (e = l.length; e--;) l[e].style[c] = d;
                        "DIV" === h && (d = "hidden" === d ? "-999em": 0, gb || (g[c] = d ? "visible": "hidden"), c = "top"),
                        g[c] = d,
                        m = !0
                    } else "zIndex" === c ? (d && (g[c] = d), m = !0) : -1 !== pa(c, ["x", "y", "width", "height"]) ? (this[c] = d, "x" === c || "y" === c ? c = {
                        x: "left",
                        y: "top"
                    } [c] : d = r(0, d), this.updateClipping ? (this[c] = d, this.updateClipping()) : g[c] = d, m = !0) : "class" === c && "DIV" === h ? f.className = d: "stroke" === c ? (d = i.color(d, f, c), c = "strokecolor") : "stroke-width" === c || "strokeWidth" === c ? (f.stroked = d ? !0 : !1, c = "strokeweight", this[c] = d, ra(d) && (d += "px")) : "dashstyle" === c ? ((f.getElementsByTagName("stroke")[0] || U(i.prepVML(["<stroke/>"]), null, null, f))[c] = d || "solid", this.dashstyle = d, m = !0) : "fill" === c ? "SPAN" === h ? g.color = d: "IMG" !== h && (f.filled = d !== S ? !0 : !1, d = i.color(d, f, c, this), c = "fillcolor") : "opacity" === c ? m = !0 : "shape" === h && "rotation" === c ? (this[c] = f.style[c] = d, f.style.left = -t(ba(d * Ua) + 1) + "px", f.style.top = t(V(d * Ua)) + "px") : "translateX" === c || "translateY" === c || "rotation" === c ? (this[c] = d, this.updateTransform(), m = !0) : "text" === c && (this.bBox = null, f.innerHTML = d, m = !0);
                    m || (gb ? f[c] = d: w(f, c, d))
                }
                return q
            },
            clip: function(a) {
                var c, b = this;
                return a ? (c = a.members, ga(c, b), c.push(b), b.destroyClip = function() {
                    ga(c, b)
                },
                a = a.getCSS(b)) : (b.destroyClip && b.destroyClip(), a = {
                    clip: gb ? "inherit": "rect(auto)"
                }),
                b.css(a)
            },
            css: va.prototype.htmlCss,
            safeRemoveChild: function(a) {
                a.parentNode && Ta(a)
            },
            destroy: function() {
                return this.destroyClip && this.destroyClip(),
                va.prototype.destroy.apply(this)
            },
            on: function(a, b) {
                return this.element["on" + a] = function() {
                    var a = N.event;
                    a.target = a.srcElement,
                    b(a)
                },
                this
            },
            cutOffPath: function(a, b) {
                var c, a = a.split(/[ ,]/);
                return c = a.length,
                (9 === c || 11 === c) && (a[c - 4] = a[c - 2] = y(a[c - 2]) - 10 * b),
                a.join(" ")
            },
            shadow: function(a, b, c) {
                var e, h, j, l, m, p, q, d = [],
                f = this.element,
                g = this.renderer,
                i = f.style,
                k = f.path;
                if (k && "string" != typeof k.value && (k = "x"), m = k, a) {
                    for (p = o(a.width, 3), q = (a.opacity || .15) / p, e = 1; 3 >= e; e++) l = 2 * p + 1 - 2 * e,
                    c && (m = this.cutOffPath(k.value, l + .5)),
                    j = ['<shape isShadow="true" strokeweight="', l, '" filled="false" path="', m, '" coordsize="10 10" style="', f.style.cssText, '" />'],
                    h = U(g.prepVML(j), null, {
                        left: y(i.left) + o(a.offsetX, 1),
                        top: y(i.top) + o(a.offsetY, 1)
                    }),
                    c && (h.cutOff = l + 1),
                    j = ['<stroke color="', a.color || "black", '" opacity="', q * e, '"/>'],
                    U(g.prepVML(j), null, null, h),
                    b ? b.element.appendChild(h) : f.parentNode.insertBefore(h, f),
                    d.push(h);
                    this.shadows = d
                }
                return this
            }
        },
        F = ha(va, F);
        var la = {
            Element: F,
            isIE8: na.indexOf("MSIE 8.0") > -1,
            init: function(a, b, c) {
                var d, e;
                if (this.alignedObjects = [], d = this.createElement(Ea), e = d.element, e.style.position = "relative", a.appendChild(d.element), this.isVML = !0, this.box = e, this.boxWrapper = d, this.setSize(b, c, !1), !z.namespaces.hcv) {
                    z.namespaces.add("hcv", "urn:schemas-microsoft-com:vml");
                    try {
                        z.createStyleSheet().cssText = "hcv\\:fill, hcv\\:path, hcv\\:shape, hcv\\:stroke{ behavior:url(#default#VML); display: inline-block; } "
                    } catch(f) {
                        z.styleSheets[0].cssText += "hcv\\:fill, hcv\\:path, hcv\\:shape, hcv\\:stroke{ behavior:url(#default#VML); display: inline-block; } "
                    }
                }
            },
            isHidden: function() {
                return ! this.box.offsetWidth
            },
            clipRect: function(a, b, c, d) {
                var e = this.createElement(),
                f = T(a);
                return s(e, {
                    members: [],
                    left: (f ? a.x: a) + 1,
                    top: (f ? a.y: b) + 1,
                    width: (f ? a.width: c) - 1,
                    height: (f ? a.height: d) - 1,
                    getCSS: function(a) {
                        var b = a.element,
                        c = b.nodeName,
                        a = a.inverted,
                        d = this.top - ("shape" === c ? b.offsetTop: 0),
                        e = this.left,
                        b = e + this.width,
                        f = d + this.height,
                        d = {
                            clip: "rect(" + t(a ? e: d) + "px," + t(a ? f: b) + "px," + t(a ? b: f) + "px," + t(a ? d: e) + "px)"
                        };
                        return ! a && gb && "DIV" === c && s(d, {
                            width: b + "px",
                            height: f + "px"
                        }),
                        d
                    },
                    updateClipping: function() {
                        n(e.members,
                        function(a) {
                            a.css(e.getCSS(a))
                        })
                    }
                })
            },
            color: function(a, b, c, d) {
                var f, h, i, e = this,
                g = /^rgba/,
                j = S;
                if (a && a.linearGradient ? i = "gradient": a && a.radialGradient && (i = "pattern"), i) {
                    var k, l, p, q, o, B, O, u, m = a.linearGradient || a.radialGradient,
                    r = "",
                    a = a.stops,
                    t = [],
                    v = function() {
                        h = ['<fill colors="' + t.join(",") + '" opacity="', o, '" o:opacity2="', q, '" type="', i, '" ', r, 'focus="100%" method="any" />'],
                        U(e.prepVML(h), null, null, b)
                    };
                    if (p = a[0], u = a[a.length - 1], p[0] > 0 && a.unshift([0, p[1]]), u[0] < 1 && a.push([1, u[1]]), n(a,
                    function(a, b) {
                        g.test(a[1]) ? (f = qa(a[1]), k = f.get("rgb"), l = f.get("a")) : (k = a[1], l = 1),
                        t.push(100 * a[0] + "% " + k),
                        b ? (o = l, B = k) : (q = l, O = k)
                    }), "fill" === c) if ("gradient" === i) c = m.x1 || m[0] || 0,
                    a = m.y1 || m[1] || 0,
                    p = m.x2 || m[2] || 0,
                    m = m.y2 || m[3] || 0,
                    r = 'angle="' + (90 - 180 * R.atan((m - a) / (p - c)) / xa) + '"',
                    v();
                    else {
                        var w, j = m.r,
                        s = 2 * j,
                        E = 2 * j,
                        H = m.cx,
                        C = m.cy,
                        x = b.radialReference,
                        j = function() {
                            x && (w = d.getBBox(), H += (x[0] - w.x) / w.width - .5, C += (x[1] - w.y) / w.height - .5, s *= x[2] / w.width, E *= x[2] / w.height),
                            r = 'src="' + L.global.VMLRadialGradientURL + '" size="' + s + "," + E + '" origin="0.5,0.5" position="' + H + "," + C + '" color2="' + O + '" ',
                            v()
                        };
                        d.added ? j() : K(d, "add", j),
                        j = B
                    } else j = k
                } else g.test(a) && "IMG" !== b.tagName ? (f = qa(a), h = ["<", c, ' opacity="', f.get("a"), '"/>'], U(this.prepVML(h), null, null, b), j = f.get("rgb")) : (j = b.getElementsByTagName(c), j.length && (j[0].opacity = 1, j[0].type = "solid"), j = a);
                return j
            },
            prepVML: function(a) {
                var b = this.isIE8,
                a = a.join("");
                return b ? (a = a.replace("/>", ' xmlns="urn:schemas-microsoft-com:vml" />'), a = -1 === a.indexOf('style="') ? a.replace("/>", ' style="display:inline-block;behavior:url(#default#VML);" />') : a.replace('style="', 'style="display:inline-block;behavior:url(#default#VML);')) : a = a.replace("<", "<hcv:"),
                a
            },
            text: za.prototype.html,
            path: function(a) {
                var b = {
                    coordsize: "10 10"
                };
                return Ia(a) ? b.d = a: T(a) && s(b, a),
                this.createElement("shape").attr(b)
            },
            circle: function(a, b, c) {
                var d = this.symbol("circle");
                return T(a) && (c = a.r, b = a.y, a = a.x),
                d.isCircle = !0,
                d.r = c,
                d.attr({
                    x: a,
                    y: b
                })
            },
            g: function(a) {
                var b;
                return a && (b = {
                    className: "highcharts-" + a,
                    "class": "highcharts-" + a
                }),
                this.createElement(Ea).attr(b)
            },
            image: function(a, b, c, d, e) {
                var f = this.createElement("img").attr({
                    src: a
                });
                return arguments.length > 1 && f.attr({
                    x: b,
                    y: c,
                    width: d,
                    height: e
                }),
                f
            },
            rect: function(a, b, c, d, e, f) {
                var g = this.symbol("rect");
                return g.r = T(a) ? a.r: e,
                g.attr(T(a) ? a: g.crisp(f, a, b, r(c, 0), r(d, 0)))
            },
            invertChild: function(a, b) {
                var c = b.style;
                I(a, {
                    flip: "x",
                    left: y(c.width) - 1,
                    top: y(c.height) - 1,
                    rotation: -90
                })
            },
            symbols: {
                arc: function(a, b, c, d, e) {
                    var f = e.start,
                    g = e.end,
                    h = e.r || c || d,
                    c = e.innerR,
                    d = V(f),
                    i = ba(f),
                    j = V(g),
                    k = ba(g);
                    return 0 === g - f ? ["x"] : (f = ["wa", a - h, b - h, a + h, b + h, a + h * d, b + h * i, a + h * j, b + h * k], e.open && !c && f.push("e", "M", a, b), f.push("at", a - c, b - c, a + c, b + c, a + c * j, b + c * k, a + c * d, b + c * i, "x", "e"), f.isArc = !0, f)
                },
                circle: function(a, b, c, d, e) {
                    return e && (c = d = 2 * e.r),
                    e && e.isCircle && (a -= c / 2, b -= d / 2),
                    ["wa", a, b, a + c, b + d, a + c, b + d / 2, a + c, b + d / 2, "e"]
                },
                rect: function(a, b, c, d, e) {
                    var h, f = a + c,
                    g = b + d;
                    return u(e) && e.r ? (h = J(e.r, c, d), f = ["M", a + h, b, "L", f - h, b, "wa", f - 2 * h, b, f, b + 2 * h, f - h, b, f, b + h, "L", f, g - h, "wa", f - 2 * h, g - 2 * h, f, g, f, g - h, f - h, g, "L", a + h, g, "wa", a, g - 2 * h, a + 2 * h, g, a + h, g, a, g - h, "L", a, b + h, "wa", a, b, a + 2 * h, b + 2 * h, a, b + h, a + h, b, "x", "e"]) : f = za.prototype.symbols.square.apply(0, arguments),
                    f
                }
            }
        };
        Highcharts.VMLRenderer = F = function() {
            this.init.apply(this, arguments)
        },
        F.prototype = x(za.prototype, la),
        Va = F
    }
    za.prototype.measureSpanWidth = function(a, b) {
        var c = z.createElement("span"),
        d = z.createTextNode(a);
        return c.appendChild(d),
        I(c, b),
        this.box.appendChild(c),
        c.offsetWidth
    };
    var Tb;
    ca && (Highcharts.CanVGRenderer = F = function() {
        ya = "http://www.w3.org/1999/xhtml"
    },
    F.prototype.symbols = {},
    Tb = function() {
        function a() {
            var d, a = b.length;
            for (d = 0; a > d; d++) b[d]();
            b = []
        }
        var b = [];
        return {
            push: function(c, d) {
                0 === b.length && Vb(d, a),
                b.push(c)
            }
        }
    } (), Va = F),
    Ma.prototype = {
        addLabel: function() {
            var l, a = this.axis,
            b = a.options,
            c = a.chart,
            d = a.horiz,
            e = a.categories,
            f = a.names,
            g = this.pos,
            h = b.labels,
            i = a.tickPositions,
            d = d && e && !h.step && !h.staggerLines && !h.rotation && c.plotWidth / i.length || !d && (c.margin[3] || .33 * c.chartWidth),
            j = g === i[0],
            k = g === i[i.length - 1],
            f = e ? o(e[g], f[g], g) : g,
            e = this.label,
            m = i.info;
            a.isDatetimeAxis && m && (l = b.dateTimeLabelFormats[m.higherRanks[g] || m.unitName]),
            this.isFirst = j,
            this.isLast = k,
            b = a.labelFormatter.call({
                axis: a,
                chart: c,
                isFirst: j,
                isLast: k,
                dateTimeLabelFormat: l,
                value: a.isLog ? ia(fa(f)) : f
            }),
            g = d && {
                width: r(1, t(d - 2 * (h.padding || 10))) + "px"
            },
            g = s(g, h.style),
            u(e) ? e && e.attr({
                text: b
            }).css(g) : (l = {
                align: a.labelAlign
            },
            ra(h.rotation) && (l.rotation = h.rotation), d && h.ellipsis && (l._clipHeight = a.len / i.length), this.label = u(b) && h.enabled ? c.renderer.text(b, 0, 0, h.useHTML).attr(l).css(g).add(a.labelGroup) : null)
        },
        getLabelSize: function() {
            var a = this.label,
            b = this.axis;
            return a ? (this.labelBBox = a.getBBox())[b.horiz ? "height": "width"] : 0
        },
        getLabelSides: function() {
            var a = this.axis,
            b = this.labelBBox.width,
            a = b * {
                left: 0,
                center: .5,
                right: 1
            } [a.labelAlign] - a.options.labels.x;
            return [ - a, b - a]
        },
        handleOverflow: function(a, b) {
            var c = !0,
            d = this.axis,
            e = d.chart,
            f = this.isFirst,
            g = this.isLast,
            h = b.x,
            i = d.reversed,
            j = d.tickPositions;
            if (f || g) {
                var k = this.getLabelSides(),
                l = k[0],
                k = k[1],
                e = e.plotLeft,
                m = e + d.len,
                j = (d = d.ticks[j[a + (f ? 1 : -1)]]) && d.label.xy && d.label.xy.x + d.getLabelSides()[f ? 0 : 1];
                f && !i || g && i ? e > h + l && (h = e - l, d && h + k > j && (c = !1)) : h + k > m && (h = m - k, d && j > h + l && (c = !1)),
                b.x = h
            }
            return c
        },
        getPosition: function(a, b, c, d) {
            var e = this.axis,
            f = e.chart,
            g = d && f.oldChartHeight || f.chartHeight;
            return {
                x: a ? e.translate(b + c, null, null, d) + e.transB: e.left + e.offset + (e.opposite ? (d && f.oldChartWidth || f.chartWidth) - e.right - e.left: 0),
                y: a ? g - e.bottom + e.offset - (e.opposite ? e.height: 0) : g - e.translate(b + c, null, null, d) - e.transB
            }
        },
        getLabelPosition: function(a, b, c, d, e, f, g, h) {
            var i = this.axis,
            j = i.transA,
            k = i.reversed,
            l = i.staggerLines,
            m = i.chart.renderer.fontMetrics(e.style.fontSize).b,
            p = e.rotation,
            a = a + e.x - (f && d ? f * j * (k ? -1 : 1) : 0),
            b = b + e.y - (f && !d ? f * j * (k ? 1 : -1) : 0);
            return p && 2 === i.side && (b -= m - m * V(p * Ua)),
            !u(e.y) && !p && (b += m - c.getBBox().height / 2),
            l && (b += g / (h || 1) % l * (i.labelOffset / l)),
            {
                x: a,
                y: b
            }
        },
        getMarkPath: function(a, b, c, d, e, f) {
            return f.crispLine(["M", a, b, "L", a + (e ? 0 : -c), b + (e ? c: 0)], d)
        },
        render: function(a, b, c) {
            var d = this.axis,
            e = d.options,
            f = d.chart.renderer,
            g = d.horiz,
            h = this.type,
            i = this.label,
            j = this.pos,
            k = e.labels,
            l = this.gridLine,
            m = h ? h + "Grid": "grid",
            p = h ? h + "Tick": "tick",
            q = e[m + "LineWidth"],
            n = e[m + "LineColor"],
            B = e[m + "LineDashStyle"],
            r = e[p + "Length"],
            m = e[p + "Width"] || 0,
            u = e[p + "Color"],
            t = e[p + "Position"],
            p = this.mark,
            s = k.step,
            w = !0,
            x = d.tickmarkOffset,
            E = this.getPosition(g, j, x, b),
            H = E.x,
            E = E.y,
            C = g && H === d.pos + d.len || !g && E === d.pos ? -1 : 1,
            y = d.staggerLines;
            this.isActive = !0,
            q && (j = d.getPlotLinePath(j + x, q * C, b, !0), l === v && (l = {
                stroke: n,
                "stroke-width": q
            },
            B && (l.dashstyle = B), h || (l.zIndex = 1), b && (l.opacity = 0), this.gridLine = l = q ? f.path(j).attr(l).add(d.gridGroup) : null), !b && l && j && l[this.isNew ? "attr": "animate"]({
                d: j,
                opacity: c
            })),
            m && r && ("inside" === t && (r = -r), d.opposite && (r = -r), b = this.getMarkPath(H, E, r, m * C, g, f), p ? p.animate({
                d: b,
                opacity: c
            }) : this.mark = f.path(b).attr({
                stroke: u,
                "stroke-width": m,
                opacity: c
            }).add(d.axisGroup)),
            i && !isNaN(H) && (i.xy = E = this.getLabelPosition(H, E, i, g, k, x, a, s), this.isFirst && !this.isLast && !o(e.showFirstLabel, 1) || this.isLast && !this.isFirst && !o(e.showLastLabel, 1) ? w = !1 : !y && g && "justify" === k.overflow && !this.handleOverflow(a, E) && (w = !1), s && a % s && (w = !1), w && !isNaN(E.y) ? (E.opacity = c, i[this.isNew ? "attr": "animate"](E), this.isNew = !1) : i.attr("y", -9999))
        },
        destroy: function() {
            Ka(this, this.axis)
        }
    },
    vb.prototype = {
        render: function() {
            var n, a = this,
            b = a.axis,
            c = b.horiz,
            d = (b.pointRange || 0) / 2,
            e = a.options,
            f = e.label,
            g = a.label,
            h = e.width,
            i = e.to,
            j = e.from,
            k = u(j) && u(i),
            l = e.value,
            m = e.dashStyle,
            p = a.svgElem,
            q = [],
            B = e.color,
            O = e.zIndex,
            t = e.events,
            v = b.chart.renderer;
            if (b.isLog && (j = ma(j), i = ma(i), l = ma(l)), h) q = b.getPlotLinePath(l, h),
            d = {
                stroke: B,
                "stroke-width": h
            },
            m && (d.dashstyle = m);
            else {
                if (!k) return;
                j = r(j, b.min - d),
                i = J(i, b.max + d),
                q = b.getPlotBandPath(j, i, e),
                d = {
                    fill: B
                },
                e.borderWidth && (d.stroke = e.borderColor, d["stroke-width"] = e.borderWidth)
            }
            if (u(O) && (d.zIndex = O), p) q ? p.animate({
                d: q
            },
            null, p.onGetPath) : (p.hide(), p.onGetPath = function() {
                p.show()
            },
            g && (a.label = g = g.destroy()));
            else if (q && q.length && (a.svgElem = p = v.path(q).attr(d).add(), t)) for (n in e = function(b) {
                p.on(b,
                function(c) {
                    t[b].apply(a, [c])
                })
            },
            t) e(n);
            return f && u(f.text) && q && q.length && b.width > 0 && b.height > 0 ? (f = x({
                align: c && k && "center",
                x: c ? !k && 4 : 10,
                verticalAlign: !c && k && "middle",
                y: c ? k ? 16 : 10 : k ? 6 : -4,
                rotation: c && !k && 90
            },
            f), g || (a.label = g = v.text(f.text, 0, 0, f.useHTML).attr({
                align: f.textAlign || f.align,
                rotation: f.rotation,
                zIndex: O
            }).css(f.style).add()), b = [q[1], q[4], o(q[6], q[1])], q = [q[2], q[5], o(q[7], q[2])], c = Ja(b), k = Ja(q), g.align(f, !1, {
                x: c,
                y: k,
                width: ua(b) - c,
                height: ua(q) - k
            }), g.show()) : g && g.hide(),
            a
        },
        destroy: function() {
            ga(this.axis.plotLinesAndBands, this),
            delete this.axis,
            Ka(this)
        }
    },
    Mb.prototype = {
        destroy: function() {
            Ka(this, this.axis)
        },
        render: function(a) {
            var b = this.options,
            c = b.format,
            c = c ? Ca(c, this) : b.formatter.call(this);
            this.label ? this.label.attr({
                text: c,
                visibility: "hidden"
            }) : this.label = this.axis.chart.renderer.text(c, 0, 0, b.useHTML).css(b.style).attr({
                align: this.textAlign,
                rotation: b.rotation,
                visibility: "hidden"
            }).add(a)
        },
        setOffset: function(a, b) {
            var c = this.axis,
            d = c.chart,
            e = d.inverted,
            f = this.isNegative,
            g = c.translate(this.percent ? 100 : this.total, 0, 0, 0, 1),
            c = c.translate(0),
            c = M(g - c),
            h = d.xAxis[0].translate(this.x) + a,
            i = d.plotHeight,
            f = {
                x: e ? f ? g: g - c: h,
                y: e ? i - h - b: f ? i - g - c: i - g,
                width: e ? c: b,
                height: e ? b: c
            }; (e = this.label) && (e.align(this.alignOptions, null, f), f = e.alignAttr, e.attr({
                visibility: this.options.crop === !1 || d.isInsidePlot(f.x, f.y) ? W ? "inherit": "visible": "hidden"
            }))
        }
    },
    eb.prototype = {
        defaultOptions: {
            dateTimeLabelFormats: {
                millisecond: "%H:%M:%S.%L",
                second: "%H:%M:%S",
                minute: "%H:%M",
                hour: "%H:%M",
                day: "%e. %b",
                week: "%e. %b",
                month: "%b '%y",
                year: "%Y"
            },
            endOnTick: !1,
            gridLineColor: "#C0C0C0",
            labels: G,
            lineColor: "#C0D0E0",
            lineWidth: 1,
            minPadding: .01,
            maxPadding: .01,
            minorGridLineColor: "#E0E0E0",
            minorGridLineWidth: 1,
            minorTickColor: "#A0A0A0",
            minorTickLength: 2,
            minorTickPosition: "outside",
            startOfWeek: 1,
            startOnTick: !1,
            tickColor: "#C0D0E0",
            tickLength: 5,
            tickmarkPlacement: "between",
            tickPixelInterval: 100,
            tickPosition: "outside",
            tickWidth: 1,
            title: {
                align: "middle",
                style: {
                    color: "#4d759e",
                    fontWeight: "bold"
                }
            },
            type: "linear"
        },
        defaultYAxisOptions: {
            endOnTick: !0,
            gridLineWidth: 1,
            tickPixelInterval: 72,
            showLastLabel: !0,
            labels: {
                x: -8,
                y: 3
            },
            lineWidth: 0,
            maxPadding: .05,
            minPadding: .05,
            startOnTick: !0,
            tickWidth: 0,
            title: {
                rotation: 270,
                text: "Values"
            },
            stackLabels: {
                enabled: !1,
                formatter: function() {
                    return Aa(this.total, -1)
                },
                style: G.style
            }
        },
        defaultLeftAxisOptions: {
            labels: {
                x: -8,
                y: null
            },
            title: {
                rotation: 270
            }
        },
        defaultRightAxisOptions: {
            labels: {
                x: 8,
                y: null
            },
            title: {
                rotation: 90
            }
        },
        defaultBottomAxisOptions: {
            labels: {
                x: 0,
                y: 14
            },
            title: {
                rotation: 0
            }
        },
        defaultTopAxisOptions: {
            labels: {
                x: 0,
                y: -5
            },
            title: {
                rotation: 0
            }
        },
        init: function(a, b) {
            var c = b.isX;
            this.horiz = a.inverted ? !c: c,
            this.xOrY = (this.isXAxis = c) ? "x": "y",
            this.opposite = b.opposite,
            this.side = this.horiz ? this.opposite ? 0 : 2 : this.opposite ? 1 : 3,
            this.setOptions(b);
            var d = this.options,
            e = d.type;
            this.labelFormatter = d.labels.formatter || this.defaultLabelFormatter,
            this.userOptions = b,
            this.minPixelPadding = 0,
            this.chart = a,
            this.reversed = d.reversed,
            this.zoomEnabled = d.zoomEnabled !== !1,
            this.categories = d.categories || "category" === e,
            this.names = [],
            this.isLog = "logarithmic" === e,
            this.isDatetimeAxis = "datetime" === e,
            this.isLinked = u(d.linkedTo),
            this.tickmarkOffset = this.categories && "between" === d.tickmarkPlacement ? .5 : 0,
            this.ticks = {},
            this.minorTicks = {},
            this.plotLinesAndBands = [],
            this.alternateBands = {},
            this.len = 0,
            this.minRange = this.userMinRange = d.minRange || d.maxZoom,
            this.range = d.range,
            this.offset = d.offset || 0,
            this.stacks = {},
            this.oldStacks = {},
            this.stackExtremes = {},
            this.min = this.max = null;
            var f, d = this.options.events; - 1 === pa(this, a.axes) && (a.axes.push(this), a[c ? "xAxis": "yAxis"].push(this)),
            this.series = this.series || [],
            a.inverted && c && this.reversed === v && (this.reversed = !0),
            this.removePlotLine = this.removePlotBand = this.removePlotBandOrLine;
            for (f in d) K(this, f, d[f]);
            this.isLog && (this.val2lin = ma, this.lin2val = fa)
        },
        setOptions: function(a) {
            this.options = x(this.defaultOptions, this.isXAxis ? {}: this.defaultYAxisOptions, [this.defaultTopAxisOptions, this.defaultRightAxisOptions, this.defaultBottomAxisOptions, this.defaultLeftAxisOptions][this.side], x(L[this.isXAxis ? "xAxis": "yAxis"], a))
        },
        update: function(a, b) {
            var c = this.chart,
            a = c.options[this.xOrY + "Axis"][this.options.index] = x(this.userOptions, a);
            this.destroy(!0),
            this._addedPlotLB = this.userMin = this.userMax = v,
            this.init(c, s(a, {
                events: v
            })),
            c.isDirtyBox = !0,
            o(b, !0) && c.redraw()
        },
        remove: function(a) {
            var b = this.chart,
            c = this.xOrY + "Axis";
            n(this.series,
            function(a) {
                a.remove(!1)
            }),
            ga(b.axes, this),
            ga(b[c], this),
            b.options[c].splice(this.options.index, 1),
            n(b[c],
            function(a, b) {
                a.options.index = b
            }),
            this.destroy(),
            b.isDirtyBox = !0,
            o(a, !0) && b.redraw()
        },
        defaultLabelFormatter: function() {
            var g, a = this.axis,
            b = this.value,
            c = a.categories,
            d = this.dateTimeLabelFormat,
            e = L.lang.numericSymbols,
            f = e && e.length,
            h = a.options.labels.format,
            a = a.isLog ? b: a.tickInterval;
            if (h) g = Ca(h, this);
            else if (c) g = b;
            else if (d) g = Ya(d, b);
            else if (f && a >= 1e3) for (; f--&&g === v;) c = Math.pow(1e3, f + 1),
            a >= c && null !== e[f] && (g = Aa(b / c, -1) + e[f]);
            return g === v && (g = b >= 1e3 ? Aa(b, 0) : Aa(b, -1)),
            g
        },
        getSeriesExtremes: function() {
            var a = this,
            b = a.chart;
            a.hasVisibleSeries = !1,
            a.dataMin = a.dataMax = null,
            a.stackExtremes = {},
            a.buildStacks(),
            n(a.series,
            function(c) {
                if (c.visible || !b.options.chart.ignoreHiddenSeries) {
                    var d;
                    d = c.options.threshold;
                    var e;
                    a.hasVisibleSeries = !0,
                    a.isLog && 0 >= d && (d = null),
                    a.isXAxis ? (d = c.xData, d.length && (a.dataMin = J(o(a.dataMin, d[0]), Ja(d)), a.dataMax = r(o(a.dataMax, d[0]), ua(d)))) : (c.getExtremes(), e = c.dataMax, c = c.dataMin, u(c) && u(e) && (a.dataMin = J(o(a.dataMin, c), c), a.dataMax = r(o(a.dataMax, e), e)), u(d) && (a.dataMin >= d ? (a.dataMin = d, a.ignoreMinPadding = !0) : a.dataMax < d && (a.dataMax = d, a.ignoreMaxPadding = !0)))
                }
            })
        },
        translate: function(a, b, c, d, e, f) {
            var g = this.len,
            h = 1,
            i = 0,
            j = d ? this.oldTransA: this.transA,
            d = d ? this.oldMin: this.min,
            k = this.minPixelPadding,
            e = (this.options.ordinal || this.isLog && e) && this.lin2val;
            return j || (j = this.transA),
            c && (h *= -1, i = g),
            this.reversed && (h *= -1, i -= h * g),
            b ? (a = a * h + i, a -= k, a = a / j + d, e && (a = this.lin2val(a))) : (e && (a = this.val2lin(a)), "between" === f && (f = .5), a = h * (a - d) * j + i + h * k + (ra(f) ? j * f * this.pointRange: 0)),
            a
        },
        toPixels: function(a, b) {
            return this.translate(a, !1, !this.horiz, null, !0) + (b ? 0 : this.pos)
        },
        toValue: function(a, b) {
            return this.translate(a - (b ? 0 : this.pos), !0, !this.horiz, null, !0)
        },
        getPlotLinePath: function(a, b, c, d) {
            var h, i, j, m, e = this.chart,
            f = this.left,
            g = this.top,
            a = this.translate(a, null, null, c),
            k = c && e.oldChartHeight || e.chartHeight,
            l = c && e.oldChartWidth || e.chartWidth;
            return h = this.transB,
            c = i = t(a + h),
            h = j = t(k - a - h),
            isNaN(a) ? m = !0 : this.horiz ? (h = g, j = k - this.bottom, (f > c || c > f + this.width) && (m = !0)) : (c = f, i = l - this.right, (g > h || h > g + this.height) && (m = !0)),
            m && !d ? null: e.renderer.crispLine(["M", c, h, "L", i, j], b || 0)
        },
        getPlotBandPath: function(a, b) {
            var c = this.getPlotLinePath(b),
            d = this.getPlotLinePath(a);
            return d && c ? d.push(c[4], c[5], c[1], c[2]) : d = null,
            d
        },
        getLinearTickPositions: function(a, b, c) {
            for (var d, b = ia(P(b / a) * a), c = ia(wa(c / a) * a), e = []; c >= b && (e.push(b), b = ia(b + a), b !== d);) d = b;
            return e
        },
        getLogTickPositions: function(a, b, c, d) {
            var e = this.options,
            f = this.len,
            g = [];
            if (d || (this._minorAutoInterval = null), a >= .5) a = t(a),
            g = this.getLinearTickPositions(a, b, c);
            else if (a >= .08) for (var h, i, j, k, l, f = P(b), e = a > .3 ? [1, 2, 4] : a > .15 ? [1, 2, 4, 6, 8] : [1, 2, 3, 4, 5, 6, 7, 8, 9]; c + 1 > f && !l; f++) for (i = e.length, h = 0; i > h && !l; h++) j = ma(fa(f) * e[h]),
            j > b && (!d || c >= k) && g.push(k),
            k > c && (l = !0),
            k = j;
            else b = fa(b),
            c = fa(c),
            a = e[d ? "minorTickInterval": "tickInterval"],
            a = o("auto" === a ? null: a, this._minorAutoInterval, (c - b) * (e.tickPixelInterval / (d ? 5 : 1)) / ((d ? f / this.tickPositions.length: f) || 1)),
            a = ob(a, null, nb(a)),
            g = Na(this.getLinearTickPositions(a, b, c), ma),
            d || (this._minorAutoInterval = a / 5);
            return d || (this.tickInterval = a),
            g
        },
        getMinorTickPositions: function() {
            var e, a = this.options,
            b = this.tickPositions,
            c = this.minorTickInterval,
            d = [];
            if (this.isLog) for (e = b.length, a = 1; e > a; a++) d = d.concat(this.getLogTickPositions(c, b[a - 1], b[a], !0));
            else if (this.isDatetimeAxis && "auto" === a.minorTickInterval) d = d.concat(Eb(Cb(c), this.min, this.max, a.startOfWeek)),
            d[0] < this.min && d.shift();
            else for (b = this.min + (b[0] - this.min) % c; b <= this.max; b += c) d.push(b);
            return d
        },
        adjustForMinRange: function() {
            var d, f, g, h, i, j, a = this.options,
            b = this.min,
            c = this.max,
            e = this.dataMax - this.dataMin >= this.minRange;
            if (this.isXAxis && this.minRange === v && !this.isLog && (u(a.min) || u(a.max) ? this.minRange = null: (n(this.series,
            function(a) {
                for (i = a.xData, g = j = a.xIncrement ? 1 : i.length - 1; g > 0; g--) h = i[g] - i[g - 1],
                (f === v || f > h) && (f = h)
            }), this.minRange = J(5 * f, this.dataMax - this.dataMin))), c - b < this.minRange) {
                var k = this.minRange;
                d = (k - c + b) / 2,
                d = [b - d, o(a.min, b - d)],
                e && (d[2] = this.dataMin),
                b = ua(d),
                c = [b + k, o(a.max, b + k)],
                e && (c[2] = this.dataMax),
                c = Ja(c),
                k > c - b && (d[0] = c - k, d[1] = o(a.min, c - k), b = ua(d))
            }
            this.min = b,
            this.max = c
        },
        setAxisTranslation: function(a) {
            var d, b = this.max - this.min,
            c = 0,
            e = 0,
            f = 0,
            g = this.linkedParent,
            h = this.transA;
            this.isXAxis && (g ? (e = g.minPointOffset, f = g.pointRangePadding) : n(this.series,
            function(a) {
                var g = a.pointRange,
                h = a.options.pointPlacement,
                l = a.closestPointRange;
                g > b && (g = 0),
                c = r(c, g),
                e = r(e, ea(h) ? 0 : g / 2),
                f = r(f, "on" === h ? 0 : g),
                !a.noSharedTooltip && u(l) && (d = u(d) ? J(d, l) : l)
            }), g = this.ordinalSlope && d ? this.ordinalSlope / d: 1, this.minPointOffset = e *= g, this.pointRangePadding = f *= g, this.pointRange = J(c, b), this.closestPointRange = d),
            a && (this.oldTransA = h),
            this.translationSlope = this.transA = h = this.len / (b + f || 1),
            this.transB = this.horiz ? this.left: this.bottom,
            this.minPixelPadding = h * e
        },
        setTickPositions: function(a) {
            var q, b = this,
            c = b.chart,
            d = b.options,
            e = b.isLog,
            f = b.isDatetimeAxis,
            g = b.isXAxis,
            h = b.isLinked,
            i = b.options.tickPositioner,
            j = d.maxPadding,
            k = d.minPadding,
            l = d.tickInterval,
            m = d.minTickInterval,
            p = d.tickPixelInterval,
            aa = b.categories;
            h ? (b.linkedParent = c[g ? "xAxis": "yAxis"][d.linkedTo], c = b.linkedParent.getExtremes(), b.min = o(c.min, c.dataMin), b.max = o(c.max, c.dataMax), d.type !== b.linkedParent.options.type && ka(11, 1)) : (b.min = o(b.userMin, d.min, b.dataMin), b.max = o(b.userMax, d.max, b.dataMax)),
            e && (!a && J(b.min, o(b.dataMin, b.min)) <= 0 && ka(10, 1), b.min = ia(ma(b.min)), b.max = ia(ma(b.max))),
            b.range && (b.userMin = b.min = r(b.min, b.max - b.range), b.userMax = b.max, a) && (b.range = null),
            b.beforePadding && b.beforePadding(),
            b.adjustForMinRange(),
            !aa && !b.usePercentage && !h && u(b.min) && u(b.max) && (c = b.max - b.min) && (u(d.min) || u(b.userMin) || !k || !(b.dataMin < 0) && b.ignoreMinPadding || (b.min -= c * k), u(d.max) || u(b.userMax) || !j || !(b.dataMax > 0) && b.ignoreMaxPadding || (b.max += c * j)),
            b.min === b.max || void 0 === b.min || void 0 === b.max ? b.tickInterval = 1 : h && !l && p === b.linkedParent.options.tickPixelInterval ? b.tickInterval = b.linkedParent.tickInterval: (b.tickInterval = o(l, aa ? 1 : (b.max - b.min) * p / r(b.len, p)), !u(l) && b.len < p && !this.isRadial && (q = !0, b.tickInterval /= 4)),
            g && !a && n(b.series,
            function(a) {
                a.processData(b.min !== b.oldMin || b.max !== b.oldMax)
            }),
            b.setAxisTranslation(!0),
            b.beforeSetTickPositions && b.beforeSetTickPositions(),
            b.postProcessTickInterval && (b.tickInterval = b.postProcessTickInterval(b.tickInterval)),
            b.pointRange && (b.tickInterval = r(b.pointRange, b.tickInterval)),
            !l && b.tickInterval < m && (b.tickInterval = m),
            f || e || l || (b.tickInterval = ob(b.tickInterval, null, nb(b.tickInterval), d)),
            b.minorTickInterval = "auto" === d.minorTickInterval && b.tickInterval ? b.tickInterval / 5 : d.minorTickInterval,
            b.tickPositions = a = d.tickPositions ? [].concat(d.tickPositions) : i && i.apply(b, [b.min, b.max]),
            a || (!b.ordinalPositions && (b.max - b.min) / b.tickInterval > r(2 * b.len, 200) && ka(19, !0), a = f ? (b.getNonLinearTimeTicks || Eb)(Cb(b.tickInterval, d.units), b.min, b.max, d.startOfWeek, b.ordinalPositions, b.closestPointRange, !0) : e ? b.getLogTickPositions(b.tickInterval, b.min, b.max) : b.getLinearTickPositions(b.tickInterval, b.min, b.max), q && a.splice(1, a.length - 2), b.tickPositions = a),
            h || (e = a[0], f = a[a.length - 1], h = b.minPointOffset || 0, d.startOnTick ? b.min = e: b.min - h > e && a.shift(), d.endOnTick ? b.max = f: b.max + h < f && a.pop(), 1 === a.length && (b.min -= .001, b.max += .001))
        },
        setMaxTicks: function() {
            var a = this.chart,
            b = a.maxTicks || {},
            c = this.tickPositions,
            d = this._maxTicksKey = [this.xOrY, this.pos, this.len].join("-"); ! this.isLinked && !this.isDatetimeAxis && c && c.length > (b[d] || 0) && this.options.alignTicks !== !1 && (b[d] = c.length),
            a.maxTicks = b
        },
        adjustTickAmount: function() {
            var a = this._maxTicksKey,
            b = this.tickPositions,
            c = this.chart.maxTicks;
            if (c && c[a] && !this.isDatetimeAxis && !this.categories && !this.isLinked && this.options.alignTicks !== !1) {
                var d = this.tickAmount,
                e = b.length;
                if (this.tickAmount = a = c[a], a > e) {
                    for (; b.length < a;) b.push(ia(b[b.length - 1] + this.tickInterval));
                    this.transA *= (e - 1) / (a - 1),
                    this.max = b[b.length - 1]
                }
                u(d) && a !== d && (this.isDirty = !0)
            }
        },
        setScale: function() {
            var b, c, d, e, a = this.stacks;
            if (this.oldMin = this.min, this.oldMax = this.max, this.oldAxisLength = this.len, this.setAxisSize(), e = this.len !== this.oldAxisLength, n(this.series,
            function(a) { (a.isDirtyData || a.isDirty || a.xAxis.isDirty) && (d = !0)
            }), e || d || this.isLinked || this.forceRedraw || this.userMin !== this.oldUserMin || this.userMax !== this.oldUserMax) {
                if (!this.isXAxis) for (b in a) for (c in a[b]) a[b][c].total = null,
                a[b][c].cum = 0;
                this.forceRedraw = !1,
                this.getSeriesExtremes(),
                this.setTickPositions(),
                this.oldUserMin = this.userMin,
                this.oldUserMax = this.userMax,
                this.isDirty || (this.isDirty = e || this.min !== this.oldMin || this.max !== this.oldMax)
            } else if (!this.isXAxis) {
                this.oldStacks && (a = this.stacks = this.oldStacks);
                for (b in a) for (c in a[b]) a[b][c].cum = a[b][c].total
            }
            this.setMaxTicks()
        },
        setExtremes: function(a, b, c, d, e) {
            var f = this,
            g = f.chart,
            c = o(c, !0),
            e = s(e, {
                min: a,
                max: b
            });
            A(f, "setExtremes", e,
            function() {
                f.userMin = a,
                f.userMax = b,
                f.eventArgs = e,
                f.isDirtyExtremes = !0,
                c && g.redraw(d)
            })
        },
        zoom: function(a, b) {
            return this.allowZoomOutside || (u(this.dataMin) && a <= this.dataMin && (a = v), u(this.dataMax) && b >= this.dataMax && (b = v)),
            this.displayBtn = a !== v || b !== v,
            this.setExtremes(a, b, !1, v, {
                trigger: "zoom"
            }),
            !0
        },
        setAxisSize: function() {
            var f, g, a = this.chart,
            b = this.options,
            c = b.offsetLeft || 0,
            d = b.offsetRight || 0,
            e = this.horiz;
            this.left = g = o(b.left, a.plotLeft + c),
            this.top = f = o(b.top, a.plotTop),
            this.width = c = o(b.width, a.plotWidth - c + d),
            this.height = b = o(b.height, a.plotHeight),
            this.bottom = a.chartHeight - b - f,
            this.right = a.chartWidth - c - g,
            this.len = r(e ? c: b, 0),
            this.pos = e ? g: f
        },
        getExtremes: function() {
            var a = this.isLog;
            return {
                min: a ? ia(fa(this.min)) : this.min,
                max: a ? ia(fa(this.max)) : this.max,
                dataMin: this.dataMin,
                dataMax: this.dataMax,
                userMin: this.userMin,
                userMax: this.userMax
            }
        },
        getThreshold: function(a) {
            var b = this.isLog,
            c = b ? fa(this.min) : this.min,
            b = b ? fa(this.max) : this.max;
            return c > a || null === a ? a = c: a > b && (a = b),
            this.translate(a, 0, 1, 0, 1)
        },
        addPlotBand: function(a) {
            this.addPlotBandOrLine(a, "plotBands")
        },
        addPlotLine: function(a) {
            this.addPlotBandOrLine(a, "plotLines")
        },
        addPlotBandOrLine: function(a, b) {
            var c = new vb(this, a).render(),
            d = this.userOptions;
            return c && (b && (d[b] = d[b] || [], d[b].push(a)), this.plotLinesAndBands.push(c)),
            c
        },
        autoLabelAlign: function(a) {
            return a = (o(a, 0) - 90 * this.side + 720) % 360,
            a > 15 && 165 > a ? "right": a > 195 && 345 > a ? "left": "center"
        },
        getOffset: function() {
            var j, l, w, Ha, E, H, C, a = this,
            b = a.chart,
            c = b.renderer,
            d = a.options,
            e = a.tickPositions,
            f = a.ticks,
            g = a.horiz,
            h = a.side,
            i = b.inverted ? [1, 0, 3, 2][h] : h,
            k = 0,
            m = 0,
            p = d.title,
            q = d.labels,
            aa = 0,
            B = b.axisOffset,
            t = b.clipOffset,
            s = [ - 1, 1, 1, -1][h],
            x = 1,
            y = o(q.maxStaggerLines, 5);
            if (a.hasData = j = a.hasVisibleSeries || u(a.min) && u(a.max) && !!e, a.showAxis = b = j || o(d.showEmpty, !0), a.staggerLines = a.horiz && q.staggerLines, a.axisGroup || (a.gridGroup = c.g("grid").attr({
                zIndex: d.gridZIndex || 1
            }).add(), a.axisGroup = c.g("axis").attr({
                zIndex: d.zIndex || 2
            }).add(), a.labelGroup = c.g("axis-labels").attr({
                zIndex: q.zIndex || 7
            }).add()), j || a.isLinked) {
                if (a.labelAlign = o(q.align || a.autoLabelAlign(q.rotation)), n(e,
                function(b) {
                    f[b] ? f[b].addLabel() : f[b] = new Ma(a, b)
                }), a.horiz && !a.staggerLines && y && !q.rotation) {
                    for (w = a.reversed ? [].concat(e).reverse() : e; y > x;) {
                        for (j = [], Ha = !1, q = 0; q < w.length; q++) E = w[q],
                        H = (H = f[E].label && f[E].label.getBBox()) ? H.width: 0,
                        C = q % x,
                        H && (E = a.translate(E), j[C] !== v && E < j[C] && (Ha = !0), j[C] = E + H);
                        if (!Ha) break;
                        x++
                    }
                    x > 1 && (a.staggerLines = x)
                }
                n(e,
                function(b) { (0 === h || 2 === h || {
                        1 : "left",
                        3 : "right"
                    } [h] === a.labelAlign) && (aa = r(f[b].getLabelSize(), aa))
                }),
                a.staggerLines && (aa *= a.staggerLines, a.labelOffset = aa)
            } else for (w in f) f[w].destroy(),
            delete f[w];
            p && p.text && p.enabled !== !1 && (a.axisTitle || (a.axisTitle = c.text(p.text, 0, 0, p.useHTML).attr({
                zIndex: 7,
                rotation: p.rotation || 0,
                align: p.textAlign || {
                    low: "left",
                    middle: "center",
                    high: "right"
                } [p.align]
            }).css(p.style).add(a.axisGroup), a.axisTitle.isNew = !0), b && (k = a.axisTitle.getBBox()[g ? "height": "width"], m = o(p.margin, g ? 5 : 10), l = p.offset), a.axisTitle[b ? "show": "hide"]()),
            a.offset = s * o(d.offset, B[h]),
            a.axisTitleMargin = o(l, aa + m + (2 !== h && aa && s * d.labels[g ? "y": "x"])),
            B[h] = r(B[h], a.axisTitleMargin + k + s * a.offset),
            t[i] = r(t[i], 2 * P(d.lineWidth / 2))
        },
        getLinePath: function(a) {
            var b = this.chart,
            c = this.opposite,
            d = this.offset,
            e = this.horiz,
            f = this.left + (c ? this.width: 0) + d,
            d = b.chartHeight - this.bottom - (c ? this.height: 0) + d;
            return c && (a *= -1),
            b.renderer.crispLine(["M", e ? this.left: f, e ? d: this.top, "L", e ? b.chartWidth - this.right: f, e ? d: b.chartHeight - this.bottom], a)
        },
        getTitlePosition: function() {
            var a = this.horiz,
            b = this.left,
            c = this.top,
            d = this.len,
            e = this.options.title,
            f = a ? b: c,
            g = this.opposite,
            h = this.offset,
            i = y(e.style.fontSize || 12),
            d = {
                low: f + (a ? 0 : d),
                middle: f + d / 2,
                high: f + (a ? d: 0)
            } [e.align],
            b = (a ? c + this.height: b) + (a ? 1 : -1) * (g ? -1 : 1) * this.axisTitleMargin + (2 === this.side ? i: 0);
            return {
                x: a ? d: b + (g ? this.width: 0) + h + (e.x || 0),
                y: a ? b - (g ? this.height: 0) + h: d + (e.y || 0)
            }
        },
        render: function() {
            var B, a = this,
            b = a.chart,
            c = b.renderer,
            d = a.options,
            e = a.isLog,
            f = a.isLinked,
            g = a.tickPositions,
            h = a.axisTitle,
            i = a.stacks,
            j = a.ticks,
            k = a.minorTicks,
            l = a.alternateBands,
            m = d.stackLabels,
            p = d.alternateGridColor,
            q = a.tickmarkOffset,
            o = d.lineWidth,
            r = b.hasRendered && u(a.oldMin) && !isNaN(a.oldMin);
            B = a.hasData;
            var s, w, t = a.showAxis;
            if (n([j, k, l],
            function(a) {
                for (var b in a) a[b].isActive = !1
            }), (B || f) && (a.minorTickInterval && !a.categories && n(a.getMinorTickPositions(),
            function(b) {
                k[b] || (k[b] = new Ma(a, b, "minor")),
                r && k[b].isNew && k[b].render(null, !0),
                k[b].render(null, !1, 1)
            }), g.length && (n(g.slice(1).concat([g[0]]),
            function(b, c) {
                c = c === g.length - 1 ? 0 : c + 1,
                (!f || b >= a.min && b <= a.max) && (j[b] || (j[b] = new Ma(a, b)), r && j[b].isNew && j[b].render(c, !0), j[b].render(c, !1, 1))
            }), q && 0 === a.min && (j[ - 1] || (j[ - 1] = new Ma(a, -1, null, !0)), j[ - 1].render( - 1))), p && n(g,
            function(b, c) {
                0 === c % 2 && b < a.max && (l[b] || (l[b] = new vb(a)), s = b + q, w = g[c + 1] !== v ? g[c + 1] + q: a.max, l[b].options = {
                    from: e ? fa(s) : s,
                    to: e ? fa(w) : w,
                    color: p
                },
                l[b].render(), l[b].isActive = !0)
            }), a._addedPlotLB || (n((d.plotLines || []).concat(d.plotBands || []),
            function(b) {
                a.addPlotBandOrLine(b)
            }), a._addedPlotLB = !0)), n([j, k, l],
            function(a) {
                var c, d, e = [],
                f = Fa ? Fa.duration || 500 : 0,
                g = function() {
                    for (d = e.length; d--;) a[e[d]] && !a[e[d]].isActive && (a[e[d]].destroy(), delete a[e[d]])
                };
                for (c in a) a[c].isActive || (a[c].render(c, !1, 0), a[c].isActive = !1, e.push(c));
                a !== l && b.hasRendered && f ? f && setTimeout(g, f) : g()
            }), o && (B = a.getLinePath(o), a.axisLine ? a.axisLine.animate({
                d: B
            }) : a.axisLine = c.path(B).attr({
                stroke: d.lineColor,
                "stroke-width": o,
                zIndex: 7
            }).add(a.axisGroup), a.axisLine[t ? "show": "hide"]()), h && t && (h[h.isNew ? "attr": "animate"](a.getTitlePosition()), h.isNew = !1), m && m.enabled) {
                var x, y, d = a.stackTotalGroup;
                d || (a.stackTotalGroup = d = c.g("stack-labels").attr({
                    visibility: "visible",
                    zIndex: 6
                }).add()),
                d.translate(b.plotLeft, b.plotTop);
                for (x in i) for (y in c = i[x]) c[y].render(d)
            }
            a.isDirty = !1
        },
        removePlotBandOrLine: function(a) {
            for (var b = this.plotLinesAndBands,
            c = this.options,
            d = this.userOptions,
            e = b.length; e--;) b[e].id === a && b[e].destroy();
            n([c.plotLines || [], d.plotLines || [], c.plotBands || [], d.plotBands || []],
            function(b) {
                for (e = b.length; e--;) b[e].id === a && ga(b, b[e])
            })
        },
        setTitle: function(a, b) {
            this.update({
                title: a
            },
            b)
        },
        redraw: function() {
            var a = this.chart.pointer;
            a.reset && a.reset(!0),
            this.render(),
            n(this.plotLinesAndBands,
            function(a) {
                a.render()
            }),
            n(this.series,
            function(a) {
                a.isDirty = !0
            })
        },
        buildStacks: function() {
            var a = this.series,
            b = a.length;
            if (!this.isXAxis) {
                for (; b--;) a[b].setStackedPoints();
                if (this.usePercentage) for (b = 0; b < a.length; b++) a[b].setPercentStacks()
            }
        },
        setCategories: function(a, b) {
            this.update({
                categories: a
            },
            b)
        },
        destroy: function(a) {
            var d, b = this,
            c = b.stacks,
            e = b.plotLinesAndBands;
            a || $(b);
            for (d in c) Ka(c[d]),
            c[d] = null;
            for (n([b.ticks, b.minorTicks, b.alternateBands],
            function(a) {
                Ka(a)
            }), a = e.length; a--;) e[a].destroy();
            n("stackTotalGroup,axisLine,axisGroup,gridGroup,labelGroup,axisTitle".split(","),
            function(a) {
                b[a] && (b[a] = b[a].destroy())
            })
        }
    },
    wb.prototype = {
        init: function(a, b) {
            var c = b.borderWidth,
            d = b.style,
            e = y(d.padding);
            this.chart = a,
            this.options = b,
            this.crosshairs = [],
            this.now = {
                x: 0,
                y: 0
            },
            this.isHidden = !0,
            this.label = a.renderer.label("", 0, 0, b.shape, null, null, b.useHTML, null, "tooltip").attr({
                padding: e,
                fill: b.backgroundColor,
                "stroke-width": c,
                r: b.borderRadius,
                zIndex: 8
            }).css(d).css({
                padding: 0
            }).add().attr({
                y: -999
            }),
            ca || this.label.shadow(b.shadow),
            this.shared = b.shared
        },
        destroy: function() {
            n(this.crosshairs,
            function(a) {
                a && a.destroy()
            }),
            this.label && (this.label = this.label.destroy()),
            clearTimeout(this.hideTimer),
            clearTimeout(this.tooltipTimeout)
        },
        move: function(a, b, c, d) {
            var e = this,
            f = e.now,
            g = e.options.animation !== !1 && !e.isHidden;
            s(f, {
                x: g ? (2 * f.x + a) / 3 : a,
                y: g ? (f.y + b) / 2 : b,
                anchorX: g ? (2 * f.anchorX + c) / 3 : c,
                anchorY: g ? (f.anchorY + d) / 2 : d
            }),
            e.label.attr(f),
            g && (M(a - f.x) > 1 || M(b - f.y) > 1) && (clearTimeout(this.tooltipTimeout), this.tooltipTimeout = setTimeout(function() {
                e && e.move(a, b, c, d)
            },
            32))
        },
        hide: function() {
            var b, a = this;
            clearTimeout(this.hideTimer),
            this.isHidden || (b = this.chart.hoverPoints, this.hideTimer = setTimeout(function() {
                a.label.fadeOut(),
                a.isHidden = !0
            },
            o(this.options.hideDelay, 500)), b && n(b,
            function(a) {
                a.setState()
            }), this.chart.hoverPoints = null)
        },
        hideCrosshairs: function() {
            n(this.crosshairs,
            function(a) {
                a && a.hide()
            })
        },
        getAnchor: function(a, b) {
            var c, i, d = this.chart,
            e = d.inverted,
            f = d.plotTop,
            g = 0,
            h = 0,
            a = ja(a);
            return c = a[0].tooltipPos,
            this.followPointer && b && (b.chartX === v && (b = d.pointer.normalize(b)), c = [b.chartX - d.plotLeft, b.chartY - f]),
            c || (n(a,
            function(a) {
                i = a.series.yAxis,
                g += a.plotX,
                h += (a.plotLow ? (a.plotLow + a.plotHigh) / 2 : a.plotY) + (!e && i ? i.top - f: 0)
            }), g /= a.length, h /= a.length, c = [e ? d.plotWidth - h: g, this.shared && !e && a.length > 1 && b ? b.chartY - f: e ? d.plotHeight - g: h]),
            Na(c, t)
        },
        getPosition: function(a, b, c) {
            var l, d = this.chart,
            e = d.plotLeft,
            f = d.plotTop,
            g = d.plotWidth,
            h = d.plotHeight,
            i = o(this.options.distance, 12),
            j = c.plotX,
            c = c.plotY,
            d = j + e + (d.inverted ? i: -a - i),
            k = c - b + f + 15;
            return 7 > d && (d = e + r(j, 0) + i),
            d + a > e + g && (d -= d + a - (e + g), k = c - b + f - i, l = !0),
            f + 5 > k && (k = f + 5, l && c >= k && k + b >= c && (k = c + f + i)),
            k + b > f + h && (k = r(f, f + h - b - i)),
            {
                x: d,
                y: k
            }
        },
        defaultFormatter: function(a) {
            var d, b = this.points || ja(this),
            c = b[0].series;
            return d = [c.tooltipHeaderFormatter(b[0])],
            n(b,
            function(a) {
                c = a.series,
                d.push(c.tooltipFormatter && c.tooltipFormatter(a) || a.point.tooltipFormatter(c.tooltipOptions.pointFormat))
            }),
            d.push(a.options.footerFormat || ""),
            d.join("")
        },
        refresh: function(a, b) {
            var f, g, i, c = this.chart,
            d = this.label,
            e = this.options,
            h = {},
            j = [];
            i = e.formatter || this.defaultFormatter;
            var k, h = c.hoverPoints,
            l = e.crosshairs,
            m = this.shared;
            if (clearTimeout(this.hideTimer), this.followPointer = ja(a)[0].series.tooltipOptions.followPointer, g = this.getAnchor(a, b), f = g[0], g = g[1], !m || a.series && a.series.noSharedTooltip ? h = a.getLabelConfig() : (c.hoverPoints = a, h && n(h,
            function(a) {
                a.setState()
            }), n(a,
            function(a) {
                a.setState("hover"),
                j.push(a.getLabelConfig())
            }), h = {
                x: a[0].category,
                y: a[0].y
            },
            h.points = j, a = a[0]), i = i.call(h, this), h = a.series, i === !1 ? this.hide() : (this.isHidden && (Wa(d), d.attr("opacity", 1).show()), d.attr({
                text: i
            }), k = e.borderColor || a.color || h.color || "#606060", d.attr({
                stroke: k
            }), this.updatePosition({
                plotX: f,
                plotY: g
            }), this.isHidden = !1), l) for (l = ja(l), d = l.length; d--;) m = a.series,
            e = m[d ? "yAxis": "xAxis"],
            l[d] && e && (h = d ? o(a.stackY, a.y) : a.x, e.isLog && (h = ma(h)), 1 === d && m.modifyValue && (h = m.modifyValue(h)), e = e.getPlotLinePath(h, 1), this.crosshairs[d] ? this.crosshairs[d].attr({
                d: e,
                visibility: "visible"
            }) : (h = {
                "stroke-width": l[d].width || 1,
                stroke: l[d].color || "#C0C0C0",
                zIndex: l[d].zIndex || 2
            },
            l[d].dashStyle && (h.dashstyle = l[d].dashStyle), this.crosshairs[d] = c.renderer.path(e).attr(h).add()));
            A(c, "tooltipRefresh", {
                text: i,
                x: f + c.plotLeft,
                y: g + c.plotTop,
                borderColor: k
            })
        },
        updatePosition: function(a) {
            var b = this.chart,
            c = this.label,
            c = (this.options.positioner || this.getPosition).call(this, c.width, c.height, a);
            this.move(t(c.x), t(c.y), a.plotX + b.plotLeft, a.plotY + b.plotTop)
        }
    },
    xb.prototype = {
        init: function(a, b) {
            var f, c = b.chart,
            d = c.events,
            e = ca ? "": c.zoomType,
            c = a.inverted;
            this.options = b,
            this.chart = a,
            this.zoomX = f = /x/.test(e),
            this.zoomY = e = /y/.test(e),
            this.zoomHor = f && !c || e && c,
            this.zoomVert = e && !c || f && c,
            this.runChartClick = d && !!d.click,
            this.pinchDown = [],
            this.lastValidTouch = {},
            b.tooltip.enabled && (a.tooltip = new wb(a, b.tooltip)),
            this.setDOMEvents()
        },
        normalize: function(a, b) {
            var c, d, a = a || N.event;
            return a.target || (a.target = a.srcElement),
            a = Xb(a),
            d = a.touches ? a.touches.item(0) : a,
            b || (this.chartPosition = b = Wb(this.chart.container)),
            d.pageX === v ? (c = r(a.x, a.clientX - b.left), d = a.y) : (c = d.pageX - b.left, d = d.pageY - b.top),
            s(a, {
                chartX: t(c),
                chartY: t(d)
            })
        },
        getCoordinates: function(a) {
            var b = {
                xAxis: [],
                yAxis: []
            };
            return n(this.chart.axes,
            function(c) {
                b[c.isXAxis ? "xAxis": "yAxis"].push({
                    axis: c,
                    value: c.toValue(a[c.horiz ? "chartX": "chartY"])
                })
            }),
            b
        },
        getIndex: function(a) {
            var b = this.chart;
            return b.inverted ? b.plotHeight + b.plotTop - a.chartY: a.chartX - b.plotLeft
        },
        runPointActions: function(a) {
            var e, h, i, b = this.chart,
            c = b.series,
            d = b.tooltip,
            f = b.hoverPoint,
            g = b.hoverSeries,
            j = b.chartWidth,
            k = this.getIndex(a);
            if (d && this.options.tooltip.shared && (!g || !g.noSharedTooltip)) {
                for (e = [], h = c.length, i = 0; h > i; i++) c[i].visible && c[i].options.enableMouseTracking !== !1 && !c[i].noSharedTooltip && c[i].tooltipPoints.length && (b = c[i].tooltipPoints[k]) && b.series && (b._dist = M(k - b.clientX), j = J(j, b._dist), e.push(b));
                for (h = e.length; h--;) e[h]._dist > j && e.splice(h, 1);
                e.length && e[0].clientX !== this.hoverX && (d.refresh(e, a), this.hoverX = e[0].clientX)
            }
            g && g.tracker ? (b = g.tooltipPoints[k]) && b !== f && b.onMouseOver(a) : d && d.followPointer && !d.isHidden && (a = d.getAnchor([{}], a), d.updatePosition({
                plotX: a[0],
                plotY: a[1]
            }))
        },
        reset: function(a) {
            var b = this.chart,
            c = b.hoverSeries,
            d = b.hoverPoint,
            e = b.tooltip,
            b = e && e.shared ? b.hoverPoints: d; (a = a && e && b) && ja(b)[0].plotX === v && (a = !1),
            a ? e.refresh(b) : (d && d.onMouseOut(), c && c.onMouseOut(), e && (e.hide(), e.hideCrosshairs()), this.hoverX = null)
        },
        scaleGroups: function(a, b) {
            var d, c = this.chart;
            n(c.series,
            function(e) {
                d = a || e.getPlotBox(),
                e.xAxis && e.xAxis.zoomEnabled && (e.group.attr(d), e.markerGroup && (e.markerGroup.attr(d), e.markerGroup.clip(b ? c.clipRect: null)), e.dataLabelsGroup && e.dataLabelsGroup.attr(d))
            }),
            c.clipRect.attr(b || c.clipBox)
        },
        pinchTranslate: function(a, b, c, d, e, f, g, h) {
            a && this.pinchTranslateDirection(!0, c, d, e, f, g, h),
            b && this.pinchTranslateDirection(!1, c, d, e, f, g, h)
        },
        pinchTranslateDirection: function(a, b, c, d, e, f, g, h) {
            var q, o, y, i = this.chart,
            j = a ? "x": "y",
            k = a ? "X": "Y",
            l = "chart" + k,
            m = a ? "width": "height",
            p = i["plot" + (a ? "Left": "Top")],
            n = h || 1,
            r = i.inverted,
            t = i.bounds[a ? "h": "v"],
            u = 1 === b.length,
            s = b[0][l],
            v = c[0][l],
            w = !u && b[1][l],
            x = !u && c[1][l],
            c = function() { ! u && M(s - w) > 20 && (n = h || M(v - x) / M(s - w)),
                o = (p - v) / n + s,
                q = i["plot" + (a ? "Width": "Height")] / n
            };
            c(),
            b = o,
            b < t.min ? (b = t.min, y = !0) : b + q > t.max && (b = t.max - q, y = !0),
            y ? (v -= .8 * (v - g[j][0]), u || (x -= .8 * (x - g[j][1])), c()) : g[j] = [v, x],
            r || (f[j] = o - p, f[m] = q),
            f = r ? 1 / n: n,
            e[m] = q,
            e[j] = b,
            d[r ? a ? "scaleY": "scaleX": "scale" + k] = n,
            d["translate" + k] = f * p + (v - f * s)
        },
        pinch: function(a) {
            var b = this,
            c = b.chart,
            d = b.pinchDown,
            e = c.tooltip && c.tooltip.options.followTouchMove,
            f = a.touches,
            g = f.length,
            h = b.lastValidTouch,
            i = b.zoomHor || b.pinchHor,
            j = b.zoomVert || b.pinchVert,
            k = i || j,
            l = b.selectionMarker,
            m = {},
            p = 1 === g && (b.inClass(a.target, "highcharts-tracker") && c.runTrackerClick || c.runChartClick),
            q = {}; (k || e) && !p && a.preventDefault(),
            Na(f,
            function(a) {
                return b.normalize(a)
            }),
            "touchstart" === a.type ? (n(f,
            function(a, b) {
                d[b] = {
                    chartX: a.chartX,
                    chartY: a.chartY
                }
            }), h.x = [d[0].chartX, d[1] && d[1].chartX], h.y = [d[0].chartY, d[1] && d[1].chartY], n(c.axes,
            function(a) {
                if (a.zoomEnabled) {
                    var b = c.bounds[a.horiz ? "h": "v"],
                    d = a.minPixelPadding,
                    e = a.toPixels(a.dataMin),
                    f = a.toPixels(a.dataMax),
                    g = J(e, f),
                    e = r(e, f);
                    b.min = J(a.pos, g - d),
                    b.max = r(a.pos + a.len, e + d)
                }
            })) : d.length && (l || (b.selectionMarker = l = s({
                destroy: oa
            },
            c.plotBox)), b.pinchTranslate(i, j, d, f, m, l, q, h), b.hasPinched = k, b.scaleGroups(m, q), !k && e && 1 === g && this.runPointActions(b.normalize(a)))
        },
        dragStart: function(a) {
            var b = this.chart;
            b.mouseIsDown = a.type,
            b.cancelClick = !1,
            b.mouseDownX = this.mouseDownX = a.chartX,
            b.mouseDownY = this.mouseDownY = a.chartY
        },
        drag: function(a) {
            var l, b = this.chart,
            c = b.options.chart,
            d = a.chartX,
            e = a.chartY,
            f = this.zoomHor,
            g = this.zoomVert,
            h = b.plotLeft,
            i = b.plotTop,
            j = b.plotWidth,
            k = b.plotHeight,
            m = this.mouseDownX,
            p = this.mouseDownY;
            h > d ? d = h: d > h + j && (d = h + j),
            i > e ? e = i: e > i + k && (e = i + k),
            this.hasDragged = Math.sqrt(Math.pow(m - d, 2) + Math.pow(p - e, 2)),
            this.hasDragged > 10 && (l = b.isInsidePlot(m - h, p - i), b.hasCartesianSeries && (this.zoomX || this.zoomY) && l && !this.selectionMarker && (this.selectionMarker = b.renderer.rect(h, i, f ? 1 : j, g ? 1 : k, 0).attr({
                fill: c.selectionMarkerFill || "rgba(69,114,167,0.25)",
                zIndex: 7
            }).add()), this.selectionMarker && f && (d -= m, this.selectionMarker.attr({
                width: M(d),
                x: (d > 0 ? 0 : d) + m
            })), this.selectionMarker && g && (d = e - p, this.selectionMarker.attr({
                height: M(d),
                y: (d > 0 ? 0 : d) + p
            })), l && !this.selectionMarker && c.panning && b.pan(a, c.panning))
        },
        drop: function(a) {
            var b = this.chart,
            c = this.hasPinched;
            if (this.selectionMarker) {
                var h, d = {
                    xAxis: [],
                    yAxis: [],
                    originalEvent: a.originalEvent || a
                },
                e = this.selectionMarker,
                f = e.x,
                g = e.y; (this.hasDragged || c) && (n(b.axes,
                function(a) {
                    if (a.zoomEnabled) {
                        var b = a.horiz,
                        c = a.toValue(b ? f: g),
                        b = a.toValue(b ? f + e.width: g + e.height); ! isNaN(c) && !isNaN(b) && (d[a.xOrY + "Axis"].push({
                            axis: a,
                            min: J(c, b),
                            max: r(c, b)
                        }), h = !0)
                    }
                }), h && A(b, "selection", d,
                function(a) {
                    b.zoom(s(a, c ? {
                        animation: !1
                    }: null))
                })),
                this.selectionMarker = this.selectionMarker.destroy(),
                c && this.scaleGroups()
            }
            b && (I(b.container, {
                cursor: b._cursor
            }), b.cancelClick = this.hasDragged > 10, b.mouseIsDown = this.hasDragged = this.hasPinched = !1, this.pinchDown = [])
        },
        onContainerMouseDown: function(a) {
            a = this.normalize(a),
            a.preventDefault && a.preventDefault(),
            this.dragStart(a)
        },
        onDocumentMouseUp: function(a) {
            this.drop(a)
        },
        onDocumentMouseMove: function(a) {
            var b = this.chart,
            c = this.chartPosition,
            d = b.hoverSeries,
            a = this.normalize(a, c);
            c && d && !this.inClass(a.target, "highcharts-tracker") && !b.isInsidePlot(a.chartX - b.plotLeft, a.chartY - b.plotTop) && this.reset()
        },
        onContainerMouseLeave: function() {
            this.reset(),
            this.chartPosition = null
        },
        onContainerMouseMove: function(a) {
            var b = this.chart,
            a = this.normalize(a);
            a.returnValue = !1,
            "mousedown" === b.mouseIsDown && this.drag(a),
            (this.inClass(a.target, "highcharts-tracker") || b.isInsidePlot(a.chartX - b.plotLeft, a.chartY - b.plotTop)) && !b.openMenu && this.runPointActions(a)
        },
        inClass: function(a, b) {
            for (var c; a;) {
                if (c = w(a, "class")) {
                    if ( - 1 !== c.indexOf(b)) return ! 0;
                    if ( - 1 !== c.indexOf("highcharts-container")) return ! 1
                }
                a = a.parentNode
            }
        },
        onTrackerMouseOut: function(a) {
            var b = this.chart.hoverSeries; ! b || b.options.stickyTracking || this.inClass(a.toElement || a.relatedTarget, "highcharts-tooltip") || b.onMouseOut()
        },
        onContainerClick: function(a) {
            var g, h, i, b = this.chart,
            c = b.hoverPoint,
            d = b.plotLeft,
            e = b.plotTop,
            f = b.inverted,
            a = this.normalize(a);
            a.cancelBubble = !0,
            b.cancelClick || (c && this.inClass(a.target, "highcharts-tracker") ? (g = this.chartPosition, h = c.plotX, i = c.plotY, s(c, {
                pageX: g.left + d + (f ? b.plotWidth - i: h),
                pageY: g.top + e + (f ? b.plotHeight - h: i)
            }), A(c.series, "click", s(a, {
                point: c
            })), b.hoverPoint && c.firePointEvent("click", a)) : (s(a, this.getCoordinates(a)), b.isInsidePlot(a.chartX - d, a.chartY - e) && A(b, "click", a)))
        },
        onContainerTouchStart: function(a) {
            var b = this.chart;
            1 === a.touches.length ? (a = this.normalize(a), b.isInsidePlot(a.chartX - b.plotLeft, a.chartY - b.plotTop) ? (this.runPointActions(a), this.pinch(a)) : this.reset()) : 2 === a.touches.length && this.pinch(a)
        },
        onContainerTouchMove: function(a) { (1 === a.touches.length || 2 === a.touches.length) && this.pinch(a)
        },
        onDocumentTouchEnd: function(a) {
            this.drop(a)
        },
        setDOMEvents: function() {
            var c, a = this,
            b = a.chart.container;
            this._events = c = [[b, "onmousedown", "onContainerMouseDown"], [b, "onmousemove", "onContainerMouseMove"], [b, "onclick", "onContainerClick"], [b, "mouseleave", "onContainerMouseLeave"], [z, "mousemove", "onDocumentMouseMove"], [z, "mouseup", "onDocumentMouseUp"]],
            jb && c.push([b, "ontouchstart", "onContainerTouchStart"], [b, "ontouchmove", "onContainerTouchMove"], [z, "touchend", "onDocumentTouchEnd"]),
            n(c,
            function(b) {
                a["_" + b[2]] = function(c) {
                    a[b[2]](c)
                },
                0 === b[1].indexOf("on") ? b[0][b[1]] = a["_" + b[2]] : K(b[0], b[1], a["_" + b[2]])
            })
        },
        destroy: function() {
            var a = this;
            n(a._events,
            function(b) {
                0 === b[1].indexOf("on") ? b[0][b[1]] = null: $(b[0], b[1], a["_" + b[2]])
            }),
            delete a._events,
            clearInterval(a.tooltipTimeout)
        }
    },
    fb.prototype = {
        init: function(a, b) {
            var c = this,
            d = b.itemStyle,
            e = o(b.padding, 8),
            f = b.itemMarginTop || 0;
            this.options = b,
            b.enabled && (c.baseline = y(d.fontSize) + 3 + f, c.itemStyle = d, c.itemHiddenStyle = x(d, b.itemHiddenStyle), c.itemMarginTop = f, c.padding = e, c.initialItemX = e, c.initialItemY = e - 5, c.maxItemWidth = 0, c.chart = a, c.itemHeight = 0, c.lastLineHeight = 0, c.render(), K(c.chart, "endResize",
            function() {
                c.positionCheckboxes()
            }))
        },
        colorizeItem: function(a, b) {
            var j, c = this.options,
            d = a.legendItem,
            e = a.legendLine,
            f = a.legendSymbol,
            g = this.itemHiddenStyle.color,
            c = b ? c.itemStyle.color: g,
            h = b ? a.color: g,
            g = a.options && a.options.marker,
            i = {
                stroke: h,
                fill: h
            };
            if (d && d.css({
                fill: c,
                color: c
            }), e && e.attr({
                stroke: h
            }), f) {
                if (g && f.isMarker) for (j in g = a.convertAttribs(g)) d = g[j],
                d !== v && (i[j] = d);
                f.attr(i)
            }
        },
        positionItem: function(a) {
            var b = this.options,
            c = b.symbolPadding,
            b = !b.rtl,
            d = a._legendItemPos,
            e = d[0],
            d = d[1],
            f = a.checkbox;
            a.legendGroup && a.legendGroup.translate(b ? e: this.legendWidth - e - 2 * c - 4, d),
            f && (f.x = e, f.y = d)
        },
        destroyItem: function(a) {
            var b = a.checkbox;
            n(["legendItem", "legendLine", "legendSymbol", "legendGroup"],
            function(b) {
                a[b] && (a[b] = a[b].destroy())
            }),
            b && Ta(a.checkbox)
        },
        destroy: function() {
            var a = this.group,
            b = this.box;
            b && (this.box = b.destroy()),
            a && (this.group = a.destroy())
        },
        positionCheckboxes: function(a) {
            var c, b = this.group.alignAttr,
            d = this.clipHeight || this.legendHeight;
            b && (c = b.translateY, n(this.allItems,
            function(e) {
                var g, f = e.checkbox;
                f && (g = c + f.y + (a || 0) + 3, I(f, {
                    left: b.translateX + e.legendItemWidth + f.x - 20 + "px",
                    top: g + "px",
                    display: g > c - 6 && c + d - 6 > g ? "": S
                }))
            }))
        },
        renderTitle: function() {
            var a = this.padding,
            b = this.options.title,
            c = 0;
            b.text && (this.title || (this.title = this.chart.renderer.label(b.text, a - 3, a - 4, null, null, null, null, null, "legend-title").attr({
                zIndex: 1
            }).css(b.style).add(this.group)), a = this.title.getBBox(), c = a.height, this.offsetWidth = a.width, this.contentGroup.attr({
                translateY: c
            })),
            this.titleHeight = c
        },
        renderItem: function(a) {
            var C, b = this,
            c = b.chart,
            d = c.renderer,
            e = b.options,
            f = "horizontal" === e.layout,
            g = e.symbolWidth,
            h = e.symbolPadding,
            i = b.itemStyle,
            j = b.itemHiddenStyle,
            k = b.padding,
            l = f ? o(e.itemDistance, 8) : 0,
            m = !e.rtl,
            p = e.width,
            q = e.itemMarginBottom || 0,
            n = b.itemMarginTop,
            B = b.initialItemX,
            t = a.legendItem,
            u = a.series || a,
            s = u.options,
            v = s.showCheckbox,
            w = e.useHTML; ! t && (a.legendGroup = d.g("legend-item").attr({
                zIndex: 1
            }).add(b.scrollGroup), u.drawLegendSymbol(b, a), a.legendItem = t = d.text(e.labelFormat ? Ca(e.labelFormat, a) : e.labelFormatter.call(a), m ? g + h: -h, b.baseline, w).css(x(a.visible ? i: j)).attr({
                align: m ? "left": "right",
                zIndex: 2
            }).add(a.legendGroup), (w ? t: a.legendGroup).on("mouseover",
            function() {
                a.setState("hover"),
                t.css(b.options.itemHoverStyle)
            }).on("mouseout",
            function() {
                t.css(a.visible ? i: j),
                a.setState()
            }).on("click",
            function(b) {
                var c = function() {
                    a.setVisible()
                },
                b = {
                    browserEvent: b
                };
                a.firePointEvent ? a.firePointEvent("legendItemClick", b, c) : A(a, "legendItemClick", b, c)
            }), b.colorizeItem(a, a.visible), s && v) && (a.checkbox = U("input", {
                type: "checkbox",
                checked: a.selected,
                defaultChecked: a.selected
            },
            e.itemCheckboxStyle, c.container), K(a.checkbox, "click",
            function(b) {
                A(a, "checkboxClick", {
                    checked: b.target.checked
                },
                function() {
                    a.select()
                })
            })),
            d = t.getBBox(),
            C = a.legendItemWidth = e.itemWidth || g + h + d.width + l + (v ? 20 : 0),
            e = C,
            b.itemHeight = g = d.height,
            f && b.itemX - B + e > (p || c.chartWidth - 2 * k - B) && (b.itemX = B, b.itemY += n + b.lastLineHeight + q, b.lastLineHeight = 0),
            b.maxItemWidth = r(b.maxItemWidth, e),
            b.lastItemY = n + b.itemY + q,
            b.lastLineHeight = r(g, b.lastLineHeight),
            a._legendItemPos = [b.itemX, b.itemY],
            f ? b.itemX += e: (b.itemY += n + g + q, b.lastLineHeight = g),
            b.offsetWidth = p || r((f ? b.itemX - B - l: e) + k, b.offsetWidth)
        },
        render: function() {
            var e, f, g, h, a = this,
            b = a.chart,
            c = b.renderer,
            d = a.group,
            i = a.box,
            j = a.options,
            k = a.padding,
            l = j.borderWidth,
            m = j.backgroundColor;
            a.itemX = a.initialItemX,
            a.itemY = a.initialItemY,
            a.offsetWidth = 0,
            a.lastItemY = 0,
            d || (a.group = d = c.g("legend").attr({
                zIndex: 7
            }).add(), a.contentGroup = c.g().attr({
                zIndex: 1
            }).add(d), a.scrollGroup = c.g().add(a.contentGroup)),
            a.renderTitle(),
            e = [],
            n(b.series,
            function(a) {
                var b = a.options;
                o(b.showInLegend, b.linkedTo === v ? v: !1, !0) && (e = e.concat(a.legendItems || ("point" === b.legendType ? a.data: a)))
            }),
            Kb(e,
            function(a, b) {
                return (a.options && a.options.legendIndex || 0) - (b.options && b.options.legendIndex || 0)
            }),
            j.reversed && e.reverse(),
            a.allItems = e,
            a.display = f = !!e.length,
            n(e,
            function(b) {
                a.renderItem(b)
            }),
            g = j.width || a.offsetWidth,
            h = a.lastItemY + a.lastLineHeight + a.titleHeight,
            h = a.handleOverflow(h),
            (l || m) && (g += k, h += k, i ? g > 0 && h > 0 && (i[i.isNew ? "attr": "animate"](i.crisp(null, null, null, g, h)), i.isNew = !1) : (a.box = i = c.rect(0, 0, g, h, j.borderRadius, l || 0).attr({
                stroke: j.borderColor,
                "stroke-width": l || 0,
                fill: m || S
            }).add(d).shadow(j.shadow), i.isNew = !0), i[f ? "show": "hide"]()),
            a.legendWidth = g,
            a.legendHeight = h,
            n(e,
            function(b) {
                a.positionItem(b)
            }),
            f && d.align(s({
                width: g,
                height: h
            },
            j), !0, "spacingBox"),
            b.isResizing || this.positionCheckboxes()
        },
        handleOverflow: function(a) {
            var b = this,
            c = this.chart,
            d = c.renderer,
            e = this.options,
            f = e.y,
            f = c.spacingBox.height + ("top" === e.verticalAlign ? -f: f) - this.padding,
            g = e.maxHeight,
            h = this.clipRect,
            i = e.navigation,
            j = o(i.animation, !0),
            k = i.arrowSize || 12,
            l = this.nav;
            return "horizontal" === e.layout && (f /= 2),
            g && (f = J(f, g)),
            a > f && !e.useHTML ? (this.clipHeight = c = f - 20 - this.titleHeight, this.pageCount = wa(a / c), this.currentPage = o(this.currentPage, 1), this.fullHeight = a, h || (h = b.clipRect = d.clipRect(0, 0, 9999, 0), b.contentGroup.clip(h)), h.attr({
                height: c
            }), l || (this.nav = l = d.g().attr({
                zIndex: 1
            }).add(this.group), this.up = d.symbol("triangle", 0, 0, k, k).on("click",
            function() {
                b.scroll( - 1, j)
            }).add(l), this.pager = d.text("", 15, 10).css(i.style).add(l), this.down = d.symbol("triangle-down", 0, 0, k, k).on("click",
            function() {
                b.scroll(1, j)
            }).add(l)), b.scroll(0), a = f) : l && (h.attr({
                height: c.chartHeight
            }), l.hide(), this.scrollGroup.attr({
                translateY: 1
            }), this.clipHeight = 0),
            a
        },
        scroll: function(a, b) {
            var c = this.pageCount,
            d = this.currentPage + a,
            e = this.clipHeight,
            f = this.options.navigation,
            g = f.activeColor,
            h = f.inactiveColor,
            f = this.pager,
            i = this.padding;
            d > c && (d = c),
            d > 0 && (b !== v && La(b, this.chart), this.nav.attr({
                translateX: i,
                translateY: e + 7 + this.titleHeight,
                visibility: "visible"
            }), this.up.attr({
                fill: 1 === d ? h: g
            }).css({
                cursor: 1 === d ? "default": "pointer"
            }), f.attr({
                text: d + "/" + this.pageCount
            }), this.down.attr({
                x: 18 + this.pager.getBBox().width,
                fill: d === c ? h: g
            }).css({
                cursor: d === c ? "default": "pointer"
            }), e = -J(e * (d - 1), this.fullHeight - e + i) + 1, this.scrollGroup.animate({
                translateY: e
            }), f.attr({
                text: d + "/" + c
            }), this.currentPage = d, this.positionCheckboxes(e))
        }
    },
    /Trident\/7\.0/.test(na) && mb(fb.prototype, "positionItem",
    function(a, b) {
        var c = this,
        d = function() {
            a.call(c, b)
        };
        c.chart.renderer.forExport ? d() : setTimeout(d)
    }),
    yb.prototype = {
        init: function(a, b) {
           var c, d = a.series;
            a.series = null,
            c = x(L, a),
            c.series = a.series = d,
            d = c.chart,
            this.margin = this.splashArray("margin", d),
            this.spacing = this.splashArray("spacing", d);
            var e = d.events;
            this.bounds = {
                h: {},
                v: {}
            },
            this.callback = b,
            this.isResizing = 0,
            this.options = c,
            this.axes = [],
            this.series = [],
            this.hasCartesianSeries = d.showAxes;
            var g, f = this;
            if (f.index = Ga.length, Ga.push(f), d.reflow !== !1 && K(f, "load",
            function() {
                f.initReflow()


            }), e) for (g in e) K(f, g, e[g]);
            f.xAxis = [],
            f.yAxis = [],
            f.animation = ca ? !1 : o(d.animation, !0),
            f.pointCount = 0,
            f.counters = new Jb,
            f.firstRender()
        },
        initSeries: function(a) {
            var b = this.options.chart;
            return (b = X[a.type || b.type || b.defaultSeriesType]) || ka(17, !0),
            b = new b,
            b.init(this, a),
            b
        },
        addSeries: function(a, b, c) {
            var d, e = this;
            return a && (b = o(b, !0), A(e, "addSeries", {
                options: a
            },
            function() {
                d = e.initSeries(a),
                e.isDirtyLegend = !0,
                e.linkSeries(),
                b && e.redraw(c)
            })),
            d
        },
        addAxis: function(a, b, c, d) {
            var e = b ? "xAxis": "yAxis",
            f = this.options;
            new eb(this, x(a, {
                index: this[e].length,
                isX: b
            })),
            f[e] = ja(f[e] || {}),
            f[e].push(a),
            o(c, !0) && this.redraw(d)
        },
        isInsidePlot: function(a, b, c) {
            var d = c ? b: a,
            a = c ? a: b;
            return d >= 0 && d <= this.plotWidth && a >= 0 && a <= this.plotHeight
        },
        adjustTickAmounts: function() {
            this.options.chart.alignTicks !== !1 && n(this.axes,
            function(a) {
                a.adjustTickAmount()
            }),
            this.maxTicks = null
        },
        redraw: function(a) {
            var g, h, b = this.axes,
            c = this.series,
            d = this.pointer,
            e = this.legend,
            f = this.isDirtyLegend,
            i = this.isDirtyBox,
            j = c.length,
            k = j,
            l = this.renderer,
            m = l.isHidden(),
            p = [];
            for (La(a, this), m && this.cloneRenderTo(), this.layOutTitles(); k--;) if (a = c[k], a.options.stacking && (g = !0, a.isDirty)) {
                h = !0;
                break
            }
            if (h) for (k = j; k--;) a = c[k],
            a.options.stacking && (a.isDirty = !0);
            n(c,
            function(a) {
                a.isDirty && "point" === a.options.legendType && (f = !0)
            }),
            f && e.options.enabled && (e.render(), this.isDirtyLegend = !1),
            g && this.getStacks(),
            this.hasCartesianSeries && (this.isResizing || (this.maxTicks = null, n(b,
            function(a) {
                a.setScale()
            })), this.adjustTickAmounts(), this.getMargins(), n(b,
            function(a) {
                a.isDirty && (i = !0)
            }), n(b,
            function(a) {
                a.isDirtyExtremes && (a.isDirtyExtremes = !1, p.push(function() {
                    A(a, "afterSetExtremes", s(a.eventArgs, a.getExtremes())),
                    delete a.eventArgs
                })),
                (i || g) && a.redraw()
            })),
            i && this.drawChartBox(),
            n(c,
            function(a) {
                a.isDirty && a.visible && (!a.isCartesian || a.xAxis) && a.redraw()
            }),
            d && d.reset && d.reset(!0),
            l.draw(),
            A(this, "redraw"),
            m && this.cloneRenderTo(!0),
            n(p,
            function(a) {
                a.call()
            })
        },
        showLoading: function(a) {
            var b = this.options,
            c = this.loadingDiv,
            d = b.loading;
            c || (this.loadingDiv = c = U(Ea, {
                className: "highcharts-loading"
            },
            s(d.style, {
                zIndex: 10,
                display: S
            }), this.container), this.loadingSpan = U("span", null, d.labelStyle, c)),
            this.loadingSpan.innerHTML = a || b.lang.loading,
            this.loadingShown || (I(c, {
                opacity: 0,
                display: "",
                left: this.plotLeft + "px",
                top: this.plotTop + "px",
                width: this.plotWidth + "px",
                height: this.plotHeight + "px"
            }), Bb(c, {
                opacity: d.style.opacity
            },
            {
                duration: d.showDuration || 0
            }), this.loadingShown = !0)
        },
        hideLoading: function() {
            var a = this.options,
            b = this.loadingDiv;
            b && Bb(b, {
                opacity: 0
            },
            {
                duration: a.loading.hideDuration || 100,
                complete: function() {
                    I(b, {
                        display: S
                    })
                }
            }),
            this.loadingShown = !1
        },
        get: function(a) {
            var d, e, b = this.axes,
            c = this.series;
            for (d = 0; d < b.length; d++) if (b[d].options.id === a) return b[d];
            for (d = 0; d < c.length; d++) if (c[d].options.id === a) return c[d];
            for (d = 0; d < c.length; d++) for (e = c[d].points || [], b = 0; b < e.length; b++) if (e[b].id === a) return e[b];
            return null
        },
        getAxes: function() {
            var a = this,
            b = this.options,
            c = b.xAxis = ja(b.xAxis || {}),
            b = b.yAxis = ja(b.yAxis || {});
            n(c,
            function(a, b) {
                a.index = b,
                a.isX = !0
            }),
            n(b,
            function(a, b) {
                a.index = b
            }),
            c = c.concat(b),
            n(c,
            function(b) {
                new eb(a, b)
            }),
            a.adjustTickAmounts()
        },
        getSelectedPoints: function() {
            var a = [];
            return n(this.series,
            function(b) {
                a = a.concat(ub(b.points || [],
                function(a) {
                    return a.selected
                }))
            }),
            a
        },
        getSelectedSeries: function() {
            return ub(this.series,
            function(a) {
                return a.selected
            })
        },
        getStacks: function() {
            var a = this;
            n(a.yAxis,
            function(a) {
                a.stacks && a.hasVisibleSeries && (a.oldStacks = a.stacks)
            }),
            n(a.series,
            function(b) { ! b.options.stacking || b.visible !== !0 && a.options.chart.ignoreHiddenSeries !== !1 || (b.stackKey = b.type + o(b.options.stack, ""))
            })
        },
        showResetZoom: function() {
            var a = this,
            b = L.lang,
            c = a.options.chart.resetZoomButton,
            d = c.theme,
            e = d.states,
            f = "chart" === c.relativeTo ? null: "plotBox";
            this.resetZoomButton = a.renderer.button(b.resetZoom, null, null,
            function() {
                a.zoomOut()
            },
            d, e && e.hover).attr({
                align: c.position.align,
                title: b.resetZoomTitle
            }).add().align(c.position, !1, f)
        },
        zoomOut: function() {
            var a = this;
            A(a, "selection", {
                resetSelection: !0
            },
            function() {
                a.zoom()
            })
        },
        zoom: function(a) {
            var b, e, c = this.pointer,
            d = !1; ! a || a.resetSelection ? n(this.axes,
            function(a) {
                b = a.zoom()
            }) : n(a.xAxis.concat(a.yAxis),
            function(a) {
                var e = a.axis,
                h = e.isXAxis; (c[h ? "zoomX": "zoomY"] || c[h ? "pinchX": "pinchY"]) && (b = e.zoom(a.min, a.max), e.displayBtn && (d = !0))
            }),
            e = this.resetZoomButton,
            d && !e ? this.showResetZoom() : !d && T(e) && (this.resetZoomButton = e.destroy()),
            b && this.redraw(o(this.options.chart.animation, a && a.animation, this.pointCount < 100))
        },
        pan: function(a, b) {
            var e, c = this,
            d = c.hoverPoints;
            d && n(d,
            function(a) {
                a.setState()
            }),
            n("xy" === b ? [1, 0] : [1],
            function(b) {
                var d = a[b ? "chartX": "chartY"],
                h = c[b ? "xAxis": "yAxis"][0],
                i = c[b ? "mouseDownX": "mouseDownY"],
                j = (h.pointRange || 0) / 2,
                k = h.getExtremes(),
                l = h.toValue(i - d, !0) + j,
                i = h.toValue(i + c[b ? "plotWidth": "plotHeight"] - d, !0) - j;
                h.series.length && l > J(k.dataMin, k.min) && i < r(k.dataMax, k.max) && (h.setExtremes(l, i, !1, !1, {
                    trigger: "pan"
                }), e = !0),
                c[b ? "mouseDownX": "mouseDownY"] = d
            }),
            e && c.redraw(!1),
            I(c.container, {
                cursor: "move"
            })
        },
        setTitle: function(a, b) {
            var f, e, c = this,
            d = c.options;
            e = d.title = x(d.title, a),
            f = d.subtitle = x(d.subtitle, b),
            d = f,
            n([["title", a, e], ["subtitle", b, d]],
            function(a) {
                var b = a[0],
                d = c[b],
                e = a[1],
                a = a[2];
                d && e && (c[b] = d = d.destroy()),
                a && a.text && !d && (c[b] = c.renderer.text(a.text, 0, 0, a.useHTML).attr({
                    align: a.align,
                    "class": "highcharts-" + b,
                    zIndex: a.zIndex || 4
                }).css(a.style).add())
            }),
            c.layOutTitles()
        },
        layOutTitles: function() {
            var a = 0,
            b = this.title,
            c = this.subtitle,
            d = this.options,
            e = d.title,
            d = d.subtitle,
            f = this.spacingBox.width - 44; ! b || (b.css({
                width: (e.width || f) + "px"
            }).align(s({
                y: 15
            },
            e), !1, "spacingBox"), e.floating || e.verticalAlign) || (a = b.getBBox().height, a >= 18 && 25 >= a && (a = 15)),
            c && (c.css({
                width: (d.width || f) + "px"
            }).align(s({
                y: a + e.margin
            },
            d), !1, "spacingBox"), !d.floating && !d.verticalAlign && (a = wa(a + c.getBBox().height))),
            this.titleOffset = a
        },
        getChartSize: function() {
			//alert(Object);

           var a = this.options.chart,
            b = this.renderToClone || this.renderTo;
            this.containerWidth = kb(b, "width"),
            this.containerHeight = kb(b, "height"),
            this.chartWidth = r(0, a.width || this.containerWidth || 600),
            this.chartHeight = r(0, o(a.height, this.containerHeight > 19 ? this.containerHeight: 400))
        },
        cloneRenderTo: function(a) {
            var b = this.renderToClone,
            c = this.container;
            a ? b && (this.renderTo.appendChild(c), Ta(b), delete this.renderToClone) : (c && c.parentNode === this.renderTo && this.renderTo.removeChild(c), this.renderToClone = b = this.renderTo.cloneNode(0), I(b, {
                position: "absolute",
                top: "-9999px",
                display: "block"
            }), z.body.appendChild(b), c && b.appendChild(c))
        },
        getContainer: function() {
            var a, c, d, e, b = this.options.chart;
            this.renderTo = a = b.renderTo,
            e = "highcharts-" + zb++,
            ea(a) && (this.renderTo = a = z.getElementById(a)),
            a || ka(13, !0),
            c = y(w(a, "data-highcharts-chart")),
            !isNaN(c) && Ga[c] && Ga[c].destroy(),
            w(a, "data-highcharts-chart", this.index),
            a.innerHTML = "",
            a.offsetWidth || this.cloneRenderTo(),
            this.getChartSize(),
            c = this.chartWidth,
            d = this.chartHeight,
            this.container = a = U(Ea, {
                className: "highcharts-container" + (b.className ? " " + b.className: ""),
                id: e
            },
            s({
                position: "relative",
                overflow: "hidden",
                width: c + "px",
                height: d + "px",
                textAlign: "left",
                lineHeight: "normal",
                zIndex: 0,
                "-webkit-tap-highlight-color": "rgba(0,0,0,0)"
            },
            b.style), this.renderToClone || a),
            this._cursor = a.style.cursor,
            this.renderer = b.forExport ? new za(a, c, d, !0) : new Va(a, c, d),
            ca && this.renderer.create(this, a, c, d)
        },
        getMargins: function() {
            var b, a = this.spacing,
            c = this.legend,
            d = this.margin,
            e = this.options.legend,
            f = o(e.margin, 10),
            g = e.x,
            h = e.y,
            i = e.align,
            j = e.verticalAlign,
            k = this.titleOffset;
            this.resetMargins(),
            b = this.axisOffset,
            k && !u(d[0]) && (this.plotTop = r(this.plotTop, k + this.options.title.margin + a[0])),
            c.display && !e.floating && ("right" === i ? u(d[1]) || (this.marginRight = r(this.marginRight, c.legendWidth - g + f + a[1])) : "left" === i ? u(d[3]) || (this.plotLeft = r(this.plotLeft, c.legendWidth + g + f + a[3])) : "top" === j ? u(d[0]) || (this.plotTop = r(this.plotTop, c.legendHeight + h + f + a[0])) : "bottom" !== j || u(d[2]) || (this.marginBottom = r(this.marginBottom, c.legendHeight - h + f + a[2]))),
            this.extraBottomMargin && (this.marginBottom += this.extraBottomMargin),
            this.extraTopMargin && (this.plotTop += this.extraTopMargin),
            this.hasCartesianSeries && n(this.axes,
            function(a) {
                a.getOffset()
            }),
            u(d[3]) || (this.plotLeft += b[3]),
            u(d[0]) || (this.plotTop += b[0]),
            u(d[2]) || (this.marginBottom += b[2]),
            u(d[1]) || (this.marginRight += b[1]),
            this.setChartSize()
        },
        initReflow: function() {
            function a(a) {
                var g = c.width || kb(d, "width"),
                h = c.height || kb(d, "height"),
                a = a ? a.target: N;
                b.hasUserSize || !g || !h || a !== N && a !== z || ((g !== b.containerWidth || h !== b.containerHeight) && (clearTimeout(e), b.reflowTimeout = e = setTimeout(function() {
                    b.container && (b.setSize(g, h, !1), b.hasUserSize = null)
                },
                100)), b.containerWidth = g, b.containerHeight = h)
            }
            var e, b = this,
            c = b.options.chart,
            d = b.renderTo;
            b.reflow = a,
            K(N, "resize", a),
            K(b, "destroy",
            function() {
                $(N, "resize", a)
            })
        },
        setSize: function(a, b, c) {
            var e, f, g, d = this;
            d.isResizing += 1,
            g = function() {
                d && A(d, "endResize", null,
                function() {
                    d.isResizing -= 1
                })
            },
            La(c, d),
            d.oldChartHeight = d.chartHeight,
            d.oldChartWidth = d.chartWidth,
            u(a) && (d.chartWidth = e = r(0, t(a)), d.hasUserSize = !!e),
            u(b) && (d.chartHeight = f = r(0, t(b))),
            I(d.container, {
                width: e + "px",
                height: f + "px"
            }),
            d.setChartSize(!0),
            d.renderer.setSize(e, f, c),
            d.maxTicks = null,
            n(d.axes,
            function(a) {
                a.isDirty = !0,
                a.setScale()
            }),
            n(d.series,
            function(a) {
                a.isDirty = !0
            }),
            d.isDirtyLegend = !0,
            d.isDirtyBox = !0,
            d.getMargins(),
            d.redraw(c),
            d.oldChartHeight = null,
            A(d, "resize"),
            Fa === !1 ? g() : setTimeout(g, Fa && Fa.duration || 500)
        },
        setChartSize: function(a) {
            var i, j, k, l, b = this.inverted,
            c = this.renderer,
            d = this.chartWidth,
            e = this.chartHeight,
            f = this.options.chart,
            g = this.spacing,
            h = this.clipOffset;
            this.plotLeft = i = t(this.plotLeft),
            this.plotTop = j = t(this.plotTop),
            this.plotWidth = k = r(0, t(d - i - this.marginRight)),
            this.plotHeight = l = r(0, t(e - j - this.marginBottom)),
            this.plotSizeX = b ? l: k,
            this.plotSizeY = b ? k: l,
            this.plotBorderWidth = f.plotBorderWidth || 0,
            this.spacingBox = c.spacingBox = {
                x: g[3],
                y: g[0],
                width: d - g[3] - g[1],
                height: e - g[0] - g[2]
            },
            this.plotBox = c.plotBox = {
                x: i,
                y: j,
                width: k,
                height: l
            },
            d = 2 * P(this.plotBorderWidth / 2),
            b = wa(r(d, h[3]) / 2),
            c = wa(r(d, h[0]) / 2),
            this.clipBox = {
                x: b,
                y: c,
                width: P(this.plotSizeX - r(d, h[1]) / 2 - b),
                height: P(this.plotSizeY - r(d, h[2]) / 2 - c)
            },
            a || n(this.axes,
            function(a) {
                a.setAxisSize(),
                a.setAxisTranslation()
            })
        },
        resetMargins: function() {
            var a = this.spacing,
            b = this.margin;
            this.plotTop = o(b[0], a[0]),
            this.marginRight = o(b[1], a[1]),
            this.marginBottom = o(b[2], a[2]),
            this.plotLeft = o(b[3], a[3]),
            this.axisOffset = [0, 0, 0, 0],
            this.clipOffset = [0, 0, 0, 0]
        },
        drawChartBox: function() {
            var p, a = this.options.chart,
            b = this.renderer,
            c = this.chartWidth,
            d = this.chartHeight,
            e = this.chartBackground,
            f = this.plotBackground,
            g = this.plotBorder,
            h = this.plotBGImage,
            i = a.borderWidth || 0,
            j = a.backgroundColor,
            k = a.plotBackgroundColor,
            l = a.plotBackgroundImage,
            m = a.plotBorderWidth || 0,
            q = this.plotLeft,
            o = this.plotTop,
            n = this.plotWidth,
            r = this.plotHeight,
            t = this.plotBox,
            u = this.clipRect,
            s = this.clipBox;
            p = i + (a.shadow ? 8 : 0),
            (i || j) && (e ? e.animate(e.crisp(null, null, null, c - p, d - p)) : (e = {
                fill: j || S
            },
            i && (e.stroke = a.borderColor, e["stroke-width"] = i), this.chartBackground = b.rect(p / 2, p / 2, c - p, d - p, a.borderRadius, i).attr(e).add().shadow(a.shadow))),
            k && (f ? f.animate(t) : this.plotBackground = b.rect(q, o, n, r, 0).attr({
                fill: k
            }).add().shadow(a.plotShadow)),
            l && (h ? h.animate(t) : this.plotBGImage = b.image(l, q, o, n, r).add()),
            u ? u.animate({
                width: s.width,
                height: s.height
            }) : this.clipRect = b.clipRect(s),
            m && (g ? g.animate(g.crisp(null, q, o, n, r)) : this.plotBorder = b.rect(q, o, n, r, 0, -m).attr({
                stroke: a.plotBorderColor,
                "stroke-width": m,
                zIndex: 1
            }).add()),
            this.isDirtyBox = !1
        },
        propFromSeries: function() {
            var c, e, f, a = this,
            b = a.options.chart,
            d = a.options.series;
            n(["inverted", "angular", "polar"],
            function(g) {
                for (c = X[b.type || b.defaultSeriesType], f = a[g] || b[g] || c && c.prototype[g], e = d && d.length; ! f && e--;)(c = X[d[e].type]) && c.prototype[g] && (f = !0);
                a[g] = f
            })
        },
        linkSeries: function() {
            var a = this,
            b = a.series;
            n(b,
            function(a) {
                a.linkedSeries.length = 0
            }),
            n(b,
            function(b) {
                var d = b.options.linkedTo;
                ea(d) && (d = ":previous" === d ? a.series[b.index - 1] : a.get(d)) && (d.linkedSeries.push(b), b.linkedParent = d)
            })
        },
        render: function() {
            var g, a = this,
            b = a.axes,
            c = a.renderer,
            d = a.options,
            e = d.labels,
            f = d.credits;
            a.setTitle(),
            a.legend = new fb(a, d.legend),
            a.getStacks(),
            n(b,
            function(a) {
                a.setScale()
            }),
            a.getMargins(),
            a.maxTicks = null,
            n(b,
            function(a) {
                a.setTickPositions(!0),
                a.setMaxTicks()
            }),
            a.adjustTickAmounts(),
            a.getMargins(),
            a.drawChartBox(),
            a.hasCartesianSeries && n(b,
            function(a) {
                a.render()
            }),
            a.seriesGroup || (a.seriesGroup = c.g("series-group").attr({
                zIndex: 3
            }).add()),
            n(a.series,
            function(a) {
                a.translate(),
                a.setTooltipPoints(),
                a.render()
            }),
            e.items && n(e.items,
            function(b) {
                var d = s(e.style, b.style),
                f = y(d.left) + a.plotLeft,
                g = y(d.top) + a.plotTop + 12;
                delete d.left,
                delete d.top,
                c.text(b.html, f, g).attr({
                    zIndex: 2
                }).css(d).add()
            }),
            f.enabled && !a.credits && (g = f.href, a.credits = c.text(f.text, 0, 0).on("click",
            function() {
                g && (location.href = g)
            }).attr({
                align: f.position.align,
                zIndex: 8
            }).css(f.style).add().align(f.position)),
            a.hasRendered = !0
        },
        destroy: function() {
            var e, a = this,
            b = a.axes,
            c = a.series,
            d = a.container,
            f = d && d.parentNode;
            for (A(a, "destroy"), Ga[a.index] = v, a.renderTo.removeAttribute("data-highcharts-chart"), $(a), e = b.length; e--;) b[e] = b[e].destroy();
            for (e = c.length; e--;) c[e] = c[e].destroy();
            n("title,subtitle,chartBackground,plotBackground,plotBGImage,plotBorder,seriesGroup,clipRect,credits,pointer,scroller,rangeSelector,legend,resetZoomButton,tooltip,renderer".split(","),
            function(b) {
                var c = a[b];
                c && c.destroy && (a[b] = c.destroy())
            }),
            d && (d.innerHTML = "", $(d), f && Ta(d));
            for (e in a) delete a[e]
        },
        isReadyToRender: function() {
            var a = this;
            return ! W && N == N.top && "complete" !== z.readyState || ca && !N.canvg ? (ca ? Tb.push(function() {
                a.firstRender()
            },
            a.options.global.canvasToolsURL) : z.attachEvent("onreadystatechange",
            function() {
                z.detachEvent("onreadystatechange", a.firstRender),
                "complete" === z.readyState && a.firstRender()
            }), !1) : !0
        },
        firstRender: function() {
            var a = this,
            b = a.options,
            c = a.callback;
            a.isReadyToRender() && (a.getContainer(), A(a, "init"), a.resetMargins(), a.setChartSize(), a.propFromSeries(), a.getAxes(), n(b.series || [],
            function(b) {
                a.initSeries(b)
            }), a.linkSeries(), A(a, "beforeRender"), a.pointer = new xb(a, b), a.render(), a.renderer.draw(), c && c.apply(a, [a]), n(a.callbacks,
            function(b) {
                b.apply(a, [a])
            }), a.cloneRenderTo(!0), A(a, "load"))
        },
        splashArray: function(a, b) {
            var c = b[a],
            c = T(c) ? c: [c, c, c, c];
            return [o(b[a + "Top"], c[0]), o(b[a + "Right"], c[1]), o(b[a + "Bottom"], c[2]), o(b[a + "Left"], c[3])]
        }
    },
    yb.prototype.callbacks = [];
    var Pa = function() {};
    Pa.prototype = {
        init: function(a, b, c) {
            return this.series = a,
            this.applyOptions(b, c),
            this.pointAttr = {},
            a.options.colorByPoint && (b = a.options.colors || a.chart.options.colors, this.color = this.color || b[a.colorCounter++], a.colorCounter === b.length) && (a.colorCounter = 0),
            a.chart.pointCount++,
            this
        },
        applyOptions: function(a, b) {
            var c = this.series,
            d = c.pointValKey,
            a = Pa.prototype.optionsToObject.call(this, a);
            return s(this, a),
            this.options = this.options ? s(this.options, a) : a,
            d && (this.y = this[d]),
            this.x === v && c && (this.x = b === v ? c.autoIncrement() : b),
            this
        },
        optionsToObject: function(a) {
            var b = {},
            c = this.series,
            d = c.pointArrayMap || ["y"],
            e = d.length,
            f = 0,
            g = 0;
            if ("number" == typeof a || null === a) b[d[0]] = a;
            else if (Ia(a)) for (a.length > e && (c = typeof a[0], "string" === c ? b.name = a[0] : "number" === c && (b.x = a[0]), f++); e > g;) b[d[g++]] = a[f++];
            else "object" == typeof a && (b = a, a.dataLabels && (c._hasPointLabels = !0), a.marker && (c._hasPointMarkers = !0));
            return b
        },
        destroy: function() {
            var c, a = this.series.chart,
            b = a.hoverPoints;
            a.pointCount--,
            b && (this.setState(), ga(b, this), !b.length) && (a.hoverPoints = null),
            this === a.hoverPoint && this.onMouseOut(),
            (this.graphic || this.dataLabel) && ($(this), this.destroyElements()),
            this.legendItem && a.legend.destroyItem(this);
            for (c in this) this[c] = null
        },
        destroyElements: function() {
            for (var b, a = "graphic,dataLabel,dataLabelUpper,group,connector,shadowGroup".split(","), c = 6; c--;) b = a[c],
            this[b] && (this[b] = this[b].destroy())
        },
        getLabelConfig: function() {
            return {
                x: this.category,
                y: this.y,
                key: this.name || this.category,
                series: this.series,
                point: this,
                percentage: this.percentage,
                total: this.total || this.stackTotal
            }
        },
        select: function(a, b) {
            var c = this,
            d = c.series,
            e = d.chart,
            a = o(a, !c.selected);
            c.firePointEvent(a ? "select": "unselect", {
                accumulate: b
            },
            function() {
                c.selected = c.options.selected = a,
                d.options.data[pa(c, d.data)] = c.options,
                c.setState(a && "select"),
                b || n(e.getSelectedPoints(),
                function(a) {
                    a.selected && a !== c && (a.selected = a.options.selected = !1, d.options.data[pa(a, d.data)] = a.options, a.setState(""), a.firePointEvent("unselect"))
                })
            })
        },
        onMouseOver: function(a) {
            var b = this.series,
            c = b.chart,
            d = c.tooltip,
            e = c.hoverPoint;
            e && e !== this && e.onMouseOut(),
            this.firePointEvent("mouseOver"),
            d && (!d.shared || b.noSharedTooltip) && d.refresh(this, a),
            this.setState("hover"),
            c.hoverPoint = this
        },
        onMouseOut: function() {
            var a = this.series.chart,
            b = a.hoverPoints;
            b && -1 !== pa(this, b) || (this.firePointEvent("mouseOut"), this.setState(), a.hoverPoint = null)
        },
        tooltipFormatter: function(a) {
            var b = this.series,
            c = b.tooltipOptions,
            d = o(c.valueDecimals, ""),
            e = c.valuePrefix || "",
            f = c.valueSuffix || "";
            return n(b.pointArrayMap || ["y"],
            function(b) {
                b = "{point." + b,
                (e || f) && (a = a.replace(b + "}", e + b + "}" + f)),
                a = a.replace(b + "}", b + ":,." + d + "f}")
            }),
            Ca(a, {
                point: this,
                series: this.series
            })
        },
        update: function(a, b, c) {
            var g, d = this,
            e = d.series,
            f = d.graphic,
            h = e.data,
            i = e.chart,
            j = e.options,
            b = o(b, !0);
            d.firePointEvent("update", {
                options: a
            },
            function() {
                d.applyOptions(a),
                T(a) && (e.getAttribs(), f) && (a && a.marker && a.marker.symbol ? d.graphic = f.destroy() : f.attr(d.pointAttr[d.state || ""])),
                g = pa(d, h),
                e.xData[g] = d.x,
                e.yData[g] = e.toYData ? e.toYData(d) : d.y,
                e.zData[g] = d.z,
                j.data[g] = d.options,
                e.isDirty = e.isDirtyData = !0,
                !e.fixedBox && e.hasCartesianSeries && (i.isDirtyBox = !0),
                "point" === j.legendType && i.legend.destroyItem(d),
                b && i.redraw(c)
            })
        },
        remove: function(a, b) {
            var g, c = this,
            d = c.series,
            e = d.points,
            f = d.chart,
            h = d.data;
            La(b, f),
            a = o(a, !0),
            c.firePointEvent("remove", null,
            function() {
                g = pa(c, h),
                h.length === e.length && e.splice(g, 1),
                h.splice(g, 1),
                d.options.data.splice(g, 1),
                d.xData.splice(g, 1),
                d.yData.splice(g, 1),
                d.zData.splice(g, 1),
                c.destroy(),
                d.isDirty = !0,
                d.isDirtyData = !0,
                a && f.redraw()
            })
        },
        firePointEvent: function(a, b, c) {
            var d = this,
            e = this.series.options; (e.point.events[a] || d.options && d.options.events && d.options.events[a]) && this.importEvents(),
            "click" === a && e.allowPointSelect && (c = function(a) {
                d.select(null, a.ctrlKey || a.metaKey || a.shiftKey)
            }),
            A(this, a, b, c)
        },
        importEvents: function() {
            if (!this.hasImportedEvents) {
                var b, a = x(this.series.options.point, this.options).events;
                this.events = a;
                for (b in a) K(this, b, a[b]);
                this.hasImportedEvents = !0
            }
        },
        setState: function(a) {
            var b = this.plotX,
            c = this.plotY,
            d = this.series,
            e = d.options.states,
            f = Z[d.type].marker && d.options.marker,
            g = f && !f.enabled,
            h = f && f.states[a],
            i = h && h.enabled === !1,
            j = d.stateMarkerGraphic,
            k = this.marker || {},
            l = d.chart,
            m = this.pointAttr,
            a = a || "";
            a === this.state || this.selected && "select" !== a || e[a] && e[a].enabled === !1 || a && (i || g && !h.enabled) || a && k.states && k.states[a] && k.states[a].enabled === !1 || (this.graphic ? (e = f && this.graphic.symbolName && m[a].r, this.graphic.attr(x(m[a], e ? {
                x: b - e,
                y: c - e,
                width: 2 * e,
                height: 2 * e
            }: {}))) : (a && h && (e = h.radius, k = k.symbol || d.symbol, j && j.currentSymbol !== k && (j = j.destroy()), j ? j.attr({
                x: b - e,
                y: c - e
            }) : (d.stateMarkerGraphic = j = l.renderer.symbol(k, b - e, c - e, 2 * e, 2 * e).attr(m[a]).add(d.markerGroup), j.currentSymbol = k)), j && j[a && l.isInsidePlot(b, c) ? "show": "hide"]()), this.state = a)
        }
    };
    var Q = function() {};
    Q.prototype = {
        isCartesian: !0,
        type: "line",
        pointClass: Pa,
        sorted: !0,
        requireSorting: !0,
        pointAttrToOptions: {
            stroke: "lineColor",
            "stroke-width": "lineWidth",
            fill: "fillColor",
            r: "radius"
        },
        colorCounter: 0,
        init: function(a, b) {
            var c, d, e = a.series;
            this.chart = a,
            this.options = b = this.setOptions(b),
            this.linkedSeries = [],
            this.bindAxes(),
            s(this, {
                name: b.name,
                state: "",
                pointAttr: {},
                visible: b.visible !== !1,
                selected: b.selected === !0
            }),
            ca && (b.animation = !1),
            d = b.events;
            for (c in d) K(this, c, d[c]); (d && d.click || b.point && b.point.events && b.point.events.click || b.allowPointSelect) && (a.runTrackerClick = !0),
            this.getColor(),
            this.getSymbol(),
            this.setData(b.data, !1),
            this.isCartesian && (a.hasCartesianSeries = !0),
            e.push(this),
            this._i = e.length - 1,
            Kb(e,
            function(a, b) {
                return o(a.options.index, a._i) - o(b.options.index, a._i)
            }),
            n(e,
            function(a, b) {
                a.index = b,
                a.name = a.name || "Series " + (b + 1)
            })
        },
        bindAxes: function() {
            var d, a = this,
            b = a.options,
            c = a.chart;
            a.isCartesian && n(["xAxis", "yAxis"],
            function(e) {
                n(c[e],
                function(c) {
                    d = c.options,
                    (b[e] === d.index || b[e] !== v && b[e] === d.id || b[e] === v && 0 === d.index) && (c.series.push(a), a[e] = c, c.isDirty = !0)
                }),
                a[e] || ka(18, !0)
            })
        },
        autoIncrement: function() {
            var a = this.options,
            b = this.xIncrement,
            b = o(b, a.pointStart, 0);
            return this.pointInterval = o(this.pointInterval, a.pointInterval, 1),
            this.xIncrement = b + this.pointInterval,
            b
        },
        getSegments: function() {
            var c, a = -1,
            b = [],
            d = this.points,
            e = d.length;
            if (e) if (this.options.connectNulls) {
                for (c = e; c--;) null === d[c].y && d.splice(c, 1);
                d.length && (b = [d])
            } else n(d,
            function(c, g) {
                null === c.y ? (g > a + 1 && b.push(d.slice(a + 1, g)), a = g) : g === e - 1 && b.push(d.slice(a + 1, g + 1))
            });
            this.segments = b
        },
        setOptions: function(a) {
            var b = this.chart.options,
            c = b.plotOptions,
            d = c[this.type];
            return this.userOptions = a,
            a = x(d, c.series, a),
            this.tooltipOptions = x(b.tooltip, a.tooltip),
            null === d.marker && delete a.marker,
            a
        },
        getColor: function() {
            var e, a = this.options,
            b = this.userOptions,
            c = this.chart.options.colors,
            d = this.chart.counters;
            e = a.color || Z[this.type].color,
            e || a.colorByPoint || (u(b._colorIndex) ? a = b._colorIndex: (b._colorIndex = d.color, a = d.color++), e = c[a]),
            this.color = e,
            d.wrapColor(c.length)
        },
        getSymbol: function() {
            var a = this.userOptions,
            b = this.options.marker,
            c = this.chart,
            d = c.options.symbols,
            c = c.counters;
            this.symbol = b.symbol,
            this.symbol || (u(a._symbolIndex) ? a = a._symbolIndex: (a._symbolIndex = c.symbol, a = c.symbol++), this.symbol = d[a]),
            /^url/.test(this.symbol) && (b.radius = 0),
            c.wrapSymbol(d.length)
        },
        drawLegendSymbol: function(a) {
            var e, b = this.options,
            c = b.marker,
            d = a.options;
            e = d.symbolWidth;
            var f = this.chart.renderer,
            g = this.legendGroup,
            a = a.baseline - t(.3 * f.fontMetrics(d.itemStyle.fontSize).b);
            b.lineWidth && (d = {
                "stroke-width": b.lineWidth
            },
            b.dashStyle && (d.dashstyle = b.dashStyle), this.legendLine = f.path(["M", 0, a, "L", e, a]).attr(d).add(g)),
            c && c.enabled && (b = c.radius, this.legendSymbol = e = f.symbol(this.symbol, e / 2 - b, a - b, 2 * b, 2 * b).add(g), e.isMarker = !0)
        },
        addPoint: function(a, b, c, d) {
            var r, e = this.options,
            f = this.data,
            g = this.graph,
            h = this.area,
            i = this.chart,
            j = this.xData,
            k = this.yData,
            l = this.zData,
            m = this.xAxis && this.xAxis.names,
            p = g && g.shift || 0,
            q = e.data;
            if (La(d, i), c && n([g, h, this.graphNeg, this.areaNeg],
            function(a) {
                a && (a.shift = p + 1)
            }), h && (h.isArea = !0), b = o(b, !0), d = {
                series: this
            },
            this.pointClass.prototype.applyOptions.apply(d, [a]), g = d.x, h = j.length, this.requireSorting && g < j[h - 1]) for (r = !0; h && j[h - 1] > g;) h--;
            j.splice(h, 0, g),
            k.splice(h, 0, this.toYData ? this.toYData(d) : d.y),
            l.splice(h, 0, d.z),
            m && (m[g] = d.name),
            q.splice(h, 0, a),
            r && (this.data.splice(h, 0, null), this.processData()),
            "point" === e.legendType && this.generatePoints(),
            c && (f[0] && f[0].remove ? f[0].remove(!1) : (f.shift(), j.shift(), k.shift(), l.shift(), q.shift())),
            this.isDirtyData = this.isDirty = !0,
            b && (this.getAttribs(), i.redraw())
        },
        setData: function(a, b) {
            var i, c = this.points,
            d = this.options,
            e = this.chart,
            f = null,
            g = this.xAxis,
            h = g && g.names;
            this.xIncrement = null,
            this.pointRange = g && g.categories ? 1 : d.pointRange,
            this.colorCounter = 0;
            var j = [],
            k = [],
            l = [],
            m = a ? a.length: [];
            i = o(d.turboThreshold, 1e3);
            var p = this.pointArrayMap,
            p = p && p.length,
            q = !!this.toYData;
            if (i && m > i) {
                for (i = 0; null === f && m > i;) f = a[i],
                i++;
                if (ra(f)) {
                    for (h = o(d.pointStart, 0), d = o(d.pointInterval, 1), i = 0; m > i; i++) j[i] = h,
                    k[i] = a[i],
                    h += d;
                    this.xIncrement = h
                } else if (Ia(f)) if (p) for (i = 0; m > i; i++) d = a[i],
                j[i] = d[0],
                k[i] = d.slice(1, p + 1);
                else for (i = 0; m > i; i++) d = a[i],
                j[i] = d[0],
                k[i] = d[1];
                else ka(12)
            } else for (i = 0; m > i; i++) a[i] !== v && (d = {
                series: this
            },
            this.pointClass.prototype.applyOptions.apply(d, [a[i]]), j[i] = d.x, k[i] = q ? this.toYData(d) : d.y, l[i] = d.z, h && d.name) && (h[d.x] = d.name);
            for (ea(k[0]) && ka(14, !0), this.data = [], this.options.data = a, this.xData = j, this.yData = k, this.zData = l, i = c && c.length || 0; i--;) c[i] && c[i].destroy && c[i].destroy();
            g && (g.minRange = g.userMinRange),
            this.isDirty = this.isDirtyData = e.isDirtyBox = !0,
            o(b, !0) && e.redraw(!1)
        },
        remove: function(a, b) {
            var c = this,
            d = c.chart,
            a = o(a, !0);
            c.isRemoving || (c.isRemoving = !0, A(c, "remove", null,
            function() {
                c.destroy(),
                d.isDirtyLegend = d.isDirtyBox = !0,
                d.linkSeries(),
                a && d.redraw(b)
            })),
            c.isRemoving = !1
        },
        processData: function(a) {
            var e, b = this.xData,
            c = this.yData,
            d = b.length;
            e = 0;
            var f, g, h = this.xAxis,
            i = this.options,
            j = i.cropThreshold,
            k = this.isCartesian;
            if (k && !this.isDirty && !h.isDirty && !this.yAxis.isDirty && !a) return ! 1;
            for (k && this.sorted && (!j || d > j || this.forceCrop) && (a = h.min, h = h.max, b[d - 1] < a || b[0] > h ? (b = [], c = []) : (b[0] < a || b[d - 1] > h) && (e = this.cropData(this.xData, this.yData, a, h), b = e.xData, c = e.yData, e = e.start, f = !0)), h = b.length - 1; h >= 0; h--) d = b[h] - b[h - 1],
            d > 0 && (g === v || g > d) ? g = d: 0 > d && this.requireSorting && ka(15);
            this.cropped = f,
            this.cropStart = e,
            this.processedXData = b,
            this.processedYData = c,
            null === i.pointRange && (this.pointRange = g || 1),
            this.closestPointRange = g
        },
        cropData: function(a, b, c, d) {
            var i, e = a.length,
            f = 0,
            g = e,
            h = o(this.cropShoulder, 1);
            for (i = 0; e > i; i++) if (a[i] >= c) {
                f = r(0, i - h);
                break
            }
            for (; e > i; i++) if (a[i] > d) {
                g = i + h;
                break
            }
            return {
                xData: a.slice(f, g),
                yData: b.slice(f, g),
                start: f,
                end: g
            }
        },
        generatePoints: function() {
            var c, i, k, m, a = this.options.data,
            b = this.data,
            d = this.processedXData,
            e = this.processedYData,
            f = this.pointClass,
            g = d.length,
            h = this.cropStart || 0,
            j = this.hasGroupedData,
            l = [];
            for (b || j || (b = [], b.length = a.length, b = this.data = b), m = 0; g > m; m++) i = h + m,
            j ? l[m] = (new f).init(this, [d[m]].concat(ja(e[m]))) : (b[i] ? k = b[i] : a[i] !== v && (b[i] = k = (new f).init(this, a[i], d[m])), l[m] = k);
            if (b && (g !== (c = b.length) || j)) for (m = 0; c > m; m++) m === h && !j && (m += g),
            b[m] && (b[m].destroyElements(), b[m].plotX = v);
            this.data = b,
            this.points = l
        },
        setStackedPoints: function() {
            if (this.options.stacking && (this.visible === !0 || this.chart.options.chart.ignoreHiddenSeries === !1)) {
                var p, q, o, n, t, a = this.processedXData,
                b = this.processedYData,
                c = [],
                d = b.length,
                e = this.options,
                f = e.threshold,
                g = e.stack,
                e = e.stacking,
                h = this.stackKey,
                i = "-" + h,
                j = this.negStacks,
                k = this.yAxis,
                l = k.stacks,
                m = k.oldStacks;
                for (o = 0; d > o; o++) n = a[o],
                t = b[o],
                q = (p = j && f > t) ? i: h,
                l[q] || (l[q] = {}),
                l[q][n] || (m[q] && m[q][n] ? (l[q][n] = m[q][n], l[q][n].total = null) : l[q][n] = new Mb(k, k.options.stackLabels, p, n, g, e)),
                q = l[q][n],
                q.points[this.index] = [q.cum || 0],
                "percent" === e ? (p = p ? h: i, j && l[p] && l[p][n] ? (p = l[p][n], q.total = p.total = r(p.total, q.total) + M(t) || 0) : q.total += M(t) || 0) : q.total += t || 0,
                q.cum = (q.cum || 0) + (t || 0),
                q.points[this.index].push(q.cum),
                c[o] = q.cum;
                "percent" === e && (k.usePercentage = !0),
                this.stackedYData = c,
                k.oldStacks = {}
            }
        },
        setPercentStacks: function() {
            var a = this,
            b = a.stackKey,
            c = a.yAxis.stacks;
            n([b, "-" + b],
            function(b) {
                for (var d, f, g, e = a.xData.length; e--;) f = a.xData[e],
                d = (g = c[b] && c[b][f]) && g.points[a.index],
                (f = d) && (g = g.total ? 100 / g.total: 0, f[0] = ia(f[0] * g), f[1] = ia(f[1] * g), a.stackedYData[e] = f[1])
            })
        },
        getExtremes: function() {
            var i, j, k, l, a = this.yAxis,
            b = this.processedXData,
            c = this.stackedYData || this.processedYData,
            d = c.length,
            e = [],
            f = 0,
            g = this.xAxis.getExtremes(),
            h = g.min,
            g = g.max;
            for (l = 0; d > l; l++) if (j = b[l], k = c[l], i = null !== k && k !== v && (!a.isLog || k.length || k > 0), j = this.getExtremesFromAll || this.cropped || (b[l + 1] || j) >= h && (b[l - 1] || j) <= g, i && j) if (i = k.length) for (; i--;) null !== k[i] && (e[f++] = k[i]);
            else e[f++] = k;
            this.dataMin = o(void 0, Ja(e)),
            this.dataMax = o(void 0, ua(e))
        },
        translate: function() {
            this.processedXData || this.processData(),
            this.generatePoints();
            for (var a = this.options,
            b = a.stacking,
            c = this.xAxis,
            d = c.categories,
            e = this.yAxis,
            f = this.points,
            g = f.length,
            h = !!this.modifyValue,
            i = a.pointPlacement,
            j = "between" === i || ra(i), k = a.threshold, a = 0; g > a; a++) {
                var l = f[a],
                m = l.x,
                p = l.y,
                q = l.low,
                n = e.stacks[(this.negStacks && k > p ? "-": "") + this.stackKey];
                e.isLog && 0 >= p && (l.y = p = null),
                l.plotX = c.translate(m, 0, 0, 0, 1, i, "flags" === this.type),
                b && this.visible && n && n[m] && (n = n[m], p = n.points[this.index], q = p[0], p = p[1], 0 === q && (q = o(k, e.min)), e.isLog && 0 >= q && (q = null), l.total = l.stackTotal = n.total, l.percentage = "percent" === b && 100 * (l.y / n.total), l.stackY = p, n.setOffset(this.pointXOffset || 0, this.barW || 0)),
                l.yBottom = u(q) ? e.translate(q, 0, 1, 0, 1) : null,
                h && (p = this.modifyValue(p, l)),
                l.plotY = "number" == typeof p && 1 / 0 !== p ? e.translate(p, 0, 1, 0, 1) : v,
                l.clientX = j ? c.translate(m, 0, 0, 0, 1) : l.plotX,
                l.negative = l.y < (k || 0),
                l.category = d && d[l.x] !== v ? d[l.x] : l.x
            }
            this.getSegments()
        },
        setTooltipPoints: function(a) {
            var c, d, h, i, b = [],
            e = this.xAxis,
            f = e && e.getExtremes(),
            g = e ? e.tooltipLen || e.len: this.chart.plotSizeX,
            j = [];
            if (this.options.enableMouseTracking !== !1) {
                for (a && (this.tooltipPoints = null), n(this.segments || this.points,
                function(a) {
                    b = b.concat(a)
                }), e && e.reversed && (b = b.reverse()), this.orderTooltipPoints && this.orderTooltipPoints(b), a = b.length, i = 0; a > i; i++) if (e = b[i], c = e.x, c >= f.min && c <= f.max) for (h = b[i + 1], c = d === v ? 0 : d + 1, d = b[i + 1] ? J(r(0, P((e.clientX + (h ? h.wrappedClientX || h.clientX: g)) / 2)), g) : g; c >= 0 && d >= c;) j[c++] = e;
                this.tooltipPoints = j
            }
        },
        tooltipHeaderFormatter: function(a) {
            var g, b = this.tooltipOptions,
            c = b.xDateFormat,
            d = b.dateTimeLabelFormats,
            e = this.xAxis,
            f = e && "datetime" === e.options.type,
            b = b.headerFormat,
            e = e && e.closestPointRange;
            if (f && !c) if (e) {
                for (g in D) if (D[g] >= e) {
                    c = d[g];
                    break
                }
            } else c = d.day;
            return f && c && ra(a.key) && (b = b.replace("{point.key}", "{point.key:" + c + "}")),
            Ca(b, {
                point: a,
                series: this
            })
        },
        onMouseOver: function() {
            var a = this.chart,
            b = a.hoverSeries;
            b && b !== this && b.onMouseOut(),
            this.options.events.mouseOver && A(this, "mouseOver"),
            this.setState("hover"),
            a.hoverSeries = this
        },
        onMouseOut: function() {
            var a = this.options,
            b = this.chart,
            c = b.tooltip,
            d = b.hoverPoint;
            d && d.onMouseOut(),
            this && a.events.mouseOut && A(this, "mouseOut"),
            c && !a.stickyTracking && (!c.shared || this.noSharedTooltip) && c.hide(),
            this.setState(),
            b.hoverSeries = null
        },
        animate: function(a) {
            var e, b = this,
            c = b.chart,
            d = c.renderer;
            e = b.options.animation;
            var h, f = c.clipBox,
            g = c.inverted;
            e && !T(e) && (e = Z[b.type].animation),
            h = "_sharedClip" + e.duration + e.easing,
            a ? (a = c[h], e = c[h + "m"], a || (c[h] = a = d.clipRect(s(f, {
                width: 0
            })), c[h + "m"] = e = d.clipRect( - 99, g ? -c.plotLeft: -c.plotTop, 99, g ? c.chartWidth: c.chartHeight)), b.group.clip(a), b.markerGroup.clip(e), b.sharedClipKey = h) : ((a = c[h]) && (a.animate({
                width: c.plotSizeX
            },
            e), c[h + "m"].animate({
                width: c.plotSizeX + 99
            },
            e)), b.animate = null, b.animationTimeout = setTimeout(function() {
                b.afterAnimate()
            },
            e.duration))
        },
        afterAnimate: function() {
            var a = this.chart,
            b = this.sharedClipKey,
            c = this.group;
            c && this.options.clip !== !1 && (c.clip(a.clipRect), this.markerGroup.clip()),
            setTimeout(function() {
                b && a[b] && (a[b] = a[b].destroy(), a[b + "m"] = a[b + "m"].destroy())
            },
            100)
        },
        drawPoints: function() {
            var a, d, e, f, g, h, i, j, k, m, b = this.points,
            c = this.chart,
            l = this.options.marker,
            n = this.markerGroup;
            if (l.enabled || this._hasPointMarkers) for (f = b.length; f--;) g = b[f],
            d = P(g.plotX),
            e = g.plotY,
            k = g.graphic,
            i = g.marker || {},
            a = l.enabled && i.enabled === v || i.enabled,
            m = c.isInsidePlot(t(d), e, c.inverted),
            a && e !== v && !isNaN(e) && null !== g.y ? (a = g.pointAttr[g.selected ? "select": ""], h = a.r, i = o(i.symbol, this.symbol), j = 0 === i.indexOf("url"), k ? k.attr({
                visibility: m ? W ? "inherit": "visible": "hidden"
            }).animate(s({
                x: d - h,
                y: e - h
            },
            k.symbolName ? {
                width: 2 * h,
                height: 2 * h
            }: {})) : m && (h > 0 || j) && (g.graphic = c.renderer.symbol(i, d - h, e - h, 2 * h, 2 * h).attr(a).add(n))) : k && (g.graphic = k.destroy())
        },
        convertAttribs: function(a, b, c, d) {
            var f, g, e = this.pointAttrToOptions,
            h = {},
            a = a || {},
            b = b || {},
            c = c || {},
            d = d || {};
            for (f in e) g = e[f],
            h[f] = o(a[g], b[f], c[f], d[f]);
            return h
        },
        getAttribs: function() {
            var f, k, q, a = this,
            b = a.options,
            c = Z[a.type].marker ? b.marker: b,
            d = c.states,
            e = d.hover,
            g = a.color,
            h = {
                stroke: g,
                fill: g
            },
            i = a.points || [],
            j = [],
            l = a.pointAttrToOptions,
            m = b.negativeColor,
            p = c.lineColor;
            for (b.marker ? (e.radius = e.radius || c.radius + 2, e.lineWidth = e.lineWidth || c.lineWidth + 1) : e.color = e.color || qa(e.color || g).brighten(e.brightness).get(), j[""] = a.convertAttribs(c, h), n(["hover", "select"],
            function(b) {
                j[b] = a.convertAttribs(d[b], j[""])
            }), a.pointAttr = j, g = i.length; g--;) {
                if (h = i[g], (c = h.options && h.options.marker || h.options) && c.enabled === !1 && (c.radius = 0), h.negative && m && (h.color = h.fillColor = m), f = b.colorByPoint || h.color, h.options) for (q in l) u(c[l[q]]) && (f = !0);
                f ? (c = c || {},
                k = [], d = c.states || {},
                f = d.hover = d.hover || {},
                b.marker || (f.color = qa(f.color || h.color).brighten(f.brightness || e.brightness).get()), k[""] = a.convertAttribs(s({
                    color: h.color,
                    fillColor: h.color,
                    lineColor: null === p ? h.color: v
                },
                c), j[""]), k.hover = a.convertAttribs(d.hover, j.hover, k[""]), k.select = a.convertAttribs(d.select, j.select, k[""])) : k = j,
                h.pointAttr = k
            }
        },
        update: function(a, b) {
            var f, c = this.chart,
            d = this.type,
            e = X[d].prototype,
            a = x(this.userOptions, {
                animation: !1,
                index: this.index,
                pointStart: this.xData[0]
            },
            {
                data: this.options.data
            },
            a);
            this.remove(!1);
            for (f in e) e.hasOwnProperty(f) && (this[f] = v);
            s(this, X[a.type || d].prototype),
            this.init(c, a),
            o(b, !0) && c.redraw(!1)
        },
        destroy: function() {
            var d, e, g, h, i, a = this,
            b = a.chart,
            c = /AppleWebKit\/533/.test(na),
            f = a.data || [];
            for (A(a, "destroy"), $(a), n(["xAxis", "yAxis"],
            function(b) { (i = a[b]) && (ga(i.series, a), i.isDirty = i.forceRedraw = !0, i.stacks = {})
            }), a.legendItem && a.chart.legend.destroyItem(a), e = f.length; e--;)(g = f[e]) && g.destroy && g.destroy();
            a.points = null,
            clearTimeout(a.animationTimeout),
            n("area,graph,dataLabelsGroup,group,markerGroup,tracker,graphNeg,areaNeg,posClip,negClip".split(","),
            function(b) {
                a[b] && (d = c && "group" === b ? "hide": "destroy", a[b][d]())
            }),
            b.hoverSeries === a && (b.hoverSeries = null),
            ga(b.series, a);
            for (h in a) delete a[h]
        },
        drawDataLabels: function() {
            var e, f, g, h, a = this,
            b = a.options,
            c = b.cursor,
            d = b.dataLabels,
            b = a.points; (d.enabled || a._hasPointLabels) && (a.dlProcessOptions && a.dlProcessOptions(d), h = a.plotGroup("dataLabelsGroup", "data-labels", a.visible ? "visible": "hidden", d.zIndex || 6), f = d, n(b,
            function(b) {
                var j, l, m, k = b.dataLabel,
                n = b.connector,
                q = !0;
                if (e = b.options && b.options.dataLabels, j = o(e && e.enabled, f.enabled), k && !j) b.dataLabel = k.destroy();
                else if (j) {
                    if (d = x(f, e), j = d.rotation, l = b.getLabelConfig(), g = d.format ? Ca(d.format, l) : d.formatter.call(l, d), d.style.color = o(d.color, d.style.color, a.color, "black"), k) u(g) ? (k.attr({
                        text: g
                    }), q = !1) : (b.dataLabel = k = k.destroy(), n && (b.connector = n.destroy()));
                    else if (u(g)) {
                        k = {
                            fill: d.backgroundColor,
                            stroke: d.borderColor,
                            "stroke-width": d.borderWidth,
                            r: d.borderRadius || 0,
                            rotation: j,
                            padding: d.padding,
                            zIndex: 1
                        };
                        for (m in k) k[m] === v && delete k[m];
                        k = b.dataLabel = a.chart.renderer[j ? "text": "label"](g, 0, -999, null, null, null, d.useHTML).attr(k).css(s(d.style, c && {
                            cursor: c
                        })).add(h).shadow(d.shadow)
                    }
                    k && a.alignDataLabel(b, k, d, null, q)
                }
            }))
        },
        alignDataLabel: function(a, b, c, d, e) {
            var f = this.chart,
            g = f.inverted,
            h = o(a.plotX, -999),
            i = o(a.plotY, -999),
            j = b.getBBox(); (a = this.visible && f.isInsidePlot(a.plotX, a.plotY, g)) && (d = s({
                x: g ? f.plotWidth - i: h,
                y: t(g ? f.plotHeight - h: i),
                width: 0,
                height: 0
            },
            d), s(c, {
                width: j.width,
                height: j.height
            }), c.rotation ? (g = {
                align: c.align,
                x: d.x + c.x + d.width / 2,
                y: d.y + c.y + d.height / 2
            },
            b[e ? "attr": "animate"](g)) : (b.align(c, null, d), g = b.alignAttr, "justify" === o(c.overflow, "justify") ? this.justifyDataLabel(b, c, g, j, d, e) : o(c.crop, !0) && (a = f.isInsidePlot(g.x, g.y) && f.isInsidePlot(g.x + j.width, g.y + j.height)))),
            a || b.attr({
                y: -999
            })
        },
        justifyDataLabel: function(a, b, c, d, e, f) {
            var j, k, g = this.chart,
            h = b.align,
            i = b.verticalAlign;
            j = c.x,
            0 > j && ("right" === h ? b.align = "left": b.x = -j, k = !0),
            j = c.x + d.width,
            j > g.plotWidth && ("left" === h ? b.align = "right": b.x = g.plotWidth - j, k = !0),
            j = c.y,
            0 > j && ("bottom" === i ? b.verticalAlign = "top": b.y = -j, k = !0),
            j = c.y + d.height,
            j > g.plotHeight && ("top" === i ? b.verticalAlign = "bottom": b.y = g.plotHeight - j, k = !0),
            k && (a.placed = !f, a.align(b, null, e))
        },
        getSegmentPath: function(a) {
            var b = this,
            c = [],
            d = b.options.step;
            return n(a,
            function(e, f) {
                var i, g = e.plotX,
                h = e.plotY;
                b.getPointSpline ? c.push.apply(c, b.getPointSpline(a, e, f)) : (c.push(f ? "L": "M"), d && f && (i = a[f - 1], "right" === d ? c.push(i.plotX, h) : "center" === d ? c.push((i.plotX + g) / 2, i.plotY, (i.plotX + g) / 2, h) : c.push(g, i.plotY)), c.push(e.plotX, e.plotY))
            }),
            c
        },
        getGraphPath: function() {
            var c, a = this,
            b = [],
            d = [];
            return n(a.segments,
            function(e) {
                c = a.getSegmentPath(e),
                e.length > 1 ? b = b.concat(c) : d.push(e[0])
            }),
            a.singlePoints = d,
            a.graphPath = b
        },
        drawGraph: function() {
            var a = this,
            b = this.options,
            c = [["graph", b.lineColor || this.color]],
            d = b.lineWidth,
            e = b.dashStyle,
            f = "square" !== b.linecap,
            g = this.getGraphPath(),
            h = b.negativeColor;
            h && c.push(["graphNeg", h]),
            n(c,
            function(c, h) {
                var k = c[0],
                l = a[k];
                l ? (Wa(l), l.animate({
                    d: g
                })) : d && g.length && (l = {
                    stroke: c[1],
                    "stroke-width": d,
                    zIndex: 1
                },
                e ? l.dashstyle = e: f && (l["stroke-linecap"] = l["stroke-linejoin"] = "round"), a[k] = a.chart.renderer.path(g).attr(l).add(a.group).shadow(!h && b.shadow))
            })
        },
        clipNeg: function() {
            var e, a = this.options,
            b = this.chart,
            c = b.renderer,
            d = a.negativeColor || a.negativeFillColor,
            f = this.graph,
            g = this.area,
            h = this.posClip,
            i = this.negClip;
            e = b.chartWidth;
            var j = b.chartHeight,
            k = r(e, j),
            l = this.yAxis;
            d && (f || g) && (d = t(l.toPixels(a.threshold || 0, !0)), a = {
                x: 0,
                y: 0,
                width: k,
                height: d
            },
            k = {
                x: 0,
                y: d,
                width: k,
                height: k
            },
            b.inverted && (a.height = k.y = b.plotWidth - d, c.isVML && (a = {
                x: b.plotWidth - d - b.plotLeft,
                y: 0,
                width: e,
                height: j
            },
            k = {
                x: d + b.plotLeft - e,
                y: 0,
                width: b.plotLeft + d,
                height: e
            })), l.reversed ? (b = k, e = a) : (b = a, e = k), h ? (h.animate(b), i.animate(e)) : (this.posClip = h = c.clipRect(b), this.negClip = i = c.clipRect(e), f && this.graphNeg && (f.clip(h), this.graphNeg.clip(i)), g && (g.clip(h), this.areaNeg.clip(i))))
        },
        invertGroups: function() {
            function a() {
                var a = {
                    width: b.yAxis.len,
                    height: b.xAxis.len
                };
                n(["group", "markerGroup"],
                function(c) {
                    b[c] && b[c].attr(a).invert()
                })
            }
            var b = this,
            c = b.chart;
            b.xAxis && (K(c, "resize", a), K(b, "destroy",
            function() {
                $(c, "resize", a)
            }), a(), b.invertGroups = a)
        },
        plotGroup: function(a, b, c, d, e) {
            var f = this[a],
            g = !f;
            return g && (this[a] = f = this.chart.renderer.g(b).attr({
                visibility: c,
                zIndex: d || .1
            }).add(e)),
            f[g ? "attr": "animate"](this.getPlotBox()),
            f
        },
        getPlotBox: function() {
            return {
                translateX: this.xAxis ? this.xAxis.left: this.chart.plotLeft,
                translateY: this.yAxis ? this.yAxis.top: this.chart.plotTop,
                scaleX: 1,
                scaleY: 1
            }
        },
        render: function() {
            var b, a = this.chart,
            c = this.options,
            d = c.animation && !!this.animate && a.renderer.isSVG,
            e = this.visible ? "visible": "hidden",
            f = c.zIndex,
            g = this.hasRendered,
            h = a.seriesGroup;
            b = this.plotGroup("group", "series", e, f, h),
            this.markerGroup = this.plotGroup("markerGroup", "markers", e, f, h),
            d && this.animate(!0),
            this.getAttribs(),
            b.inverted = this.isCartesian ? a.inverted: !1,
            this.drawGraph && (this.drawGraph(), this.clipNeg()),
            this.drawDataLabels(),
            this.drawPoints(),
            this.options.enableMouseTracking !== !1 && this.drawTracker(),
            a.inverted && this.invertGroups(),
            c.clip !== !1 && !this.sharedClipKey && !g && b.clip(a.clipRect),
            d ? this.animate() : g || this.afterAnimate(),
            this.isDirty = this.isDirtyData = !1,
            this.hasRendered = !0
        },
        redraw: function() {
            var a = this.chart,
            b = this.isDirtyData,
            c = this.group,
            d = this.xAxis,
            e = this.yAxis;
            c && (a.inverted && c.attr({
                width: a.plotWidth,
                height: a.plotHeight
            }), c.animate({
                translateX: o(d && d.left, a.plotLeft),
                translateY: o(e && e.top, a.plotTop)
            })),
            this.translate(),
            this.setTooltipPoints(!0),
            this.render(),
            b && A(this, "updatedData")
        },
        setState: function(a) {
            var b = this.options,
            c = this.graph,
            d = this.graphNeg,
            e = b.states,
            b = b.lineWidth,
            a = a || "";
            this.state !== a && (this.state = a, e[a] && e[a].enabled === !1 || (a && (b = e[a].lineWidth || b + 1), c && !c.dashstyle && (a = {
                "stroke-width": b
            },
            c.attr(a), d && d.attr(a))))
        },
        setVisible: function(a, b) {
            var f, c = this,
            d = c.chart,
            e = c.legendItem,
            g = d.options.chart.ignoreHiddenSeries,
            h = c.visible;
            f = (c.visible = a = c.userOptions.visible = a === v ? !h: a) ? "show": "hide",
            n(["group", "dataLabelsGroup", "markerGroup", "tracker"],
            function(a) {
                c[a] && c[a][f]()
            }),
            d.hoverSeries === c && c.onMouseOut(),
            e && d.legend.colorizeItem(c, a),
            c.isDirty = !0,
            c.options.stacking && n(d.series,
            function(a) {
                a.options.stacking && a.visible && (a.isDirty = !0)
            }),
            n(c.linkedSeries,
            function(b) {
                b.setVisible(a, !1)
            }),
            g && (d.isDirtyBox = !0),
            b !== !1 && d.redraw(),
            A(c, f)
        },
        show: function() {
            this.setVisible(!0)
        },
        hide: function() {
            this.setVisible(!1)
        },
        select: function(a) {
            this.selected = a = a === v ? !this.selected: a,
            this.checkbox && (this.checkbox.checked = a),
            A(this, a ? "select": "unselect")
        },
        drawTracker: function() {
            var m, a = this,
            b = a.options,
            c = b.trackByArea,
            d = [].concat(c ? a.areaPath: a.graphPath),
            e = d.length,
            f = a.chart,
            g = f.pointer,
            h = f.renderer,
            i = f.options.tooltip.snap,
            j = a.tracker,
            k = b.cursor,
            l = k && {
                cursor: k
            },
            k = a.singlePoints,
            p = function() {
                f.hoverSeries !== a && a.onMouseOver()
            };
            if (e && !c) for (m = e + 1; m--;)"M" === d[m] && d.splice(m + 1, 0, d[m + 1] - i, d[m + 2], "L"),
            (m && "M" === d[m] || m === e) && d.splice(m, 0, "L", d[m - 2] + i, d[m - 1]);
            for (m = 0; m < k.length; m++) e = k[m],
            d.push("M", e.plotX - i, e.plotY, "L", e.plotX + i, e.plotY);
            j ? j.attr({
                d: d
            }) : (a.tracker = h.path(d).attr({
                "stroke-linejoin": "round",
                visibility: a.visible ? "visible": "hidden",
                stroke: Qb,
                fill: c ? Qb: S,
                "stroke-width": b.lineWidth + (c ? 0 : 2 * i),
                zIndex: 2
            }).add(a.group), n([a.tracker, a.markerGroup],
            function(a) {
                a.addClass("highcharts-tracker").on("mouseover", p).on("mouseout",
                function(a) {
                    g.onTrackerMouseOut(a)
                }).css(l),
                jb && a.on("touchstart", p)
            }))
        }
    },
    G = ha(Q),
    X.line = G,
    Z.area = x(Y, {
        threshold: 0
    }),
    G = ha(Q, {
        type: "area",
        getSegments: function() {
            var h, i, l, m, p, a = [],
            b = [],
            c = [],
            d = this.xAxis,
            e = this.yAxis,
            f = e.stacks[this.stackKey],
            g = {},
            j = this.points,
            k = this.options.connectNulls;
            if (this.options.stacking && !this.cropped) {
                for (m = 0; m < j.length; m++) g[j[m].x] = j[m];
                for (p in f) null !== f[p].total && c.push( + p);
                c.sort(function(a, b) {
                    return a - b
                }),
                n(c,
                function(a) { (!k || g[a] && null !== g[a].y) && (g[a] ? b.push(g[a]) : (h = d.translate(a), l = f[a].percent ? f[a].total ? 100 * f[a].cum / f[a].total: 0 : f[a].cum, i = e.toPixels(l, !0), b.push({
                        y: null,
                        plotX: h,
                        clientX: h,
                        plotY: i,
                        yBottom: i,
                        onMouseOver: oa
                    })))
                }),
                b.length && a.push(b)
            } else Q.prototype.getSegments.call(this),
            a = this.segments;
            this.segments = a
        },
        getSegmentPath: function(a) {
            var d, b = Q.prototype.getSegmentPath.call(this, a),
            c = [].concat(b),
            e = this.options;
            d = b.length;
            var g, f = this.yAxis.getThreshold(e.threshold);
            if (3 === d && c.push("L", b[1], b[2]), e.stacking && !this.closedStacks) for (d = a.length - 1; d >= 0; d--) g = o(a[d].yBottom, f),
            d < a.length - 1 && e.step && c.push(a[d + 1].plotX, g),
            c.push(a[d].plotX, g);
            else this.closeSegment(c, a, f);
            return this.areaPath = this.areaPath.concat(c),
            b
        },
        closeSegment: function(a, b, c) {
            a.push("L", b[b.length - 1].plotX, c, "L", b[0].plotX, c)
        },
        drawGraph: function() {
            this.areaPath = [],
            Q.prototype.drawGraph.apply(this);
            var a = this,
            b = this.areaPath,
            c = this.options,
            d = c.negativeColor,
            e = c.negativeFillColor,
            f = [["area", this.color, c.fillColor]]; (d || e) && f.push(["areaNeg", d, e]),
            n(f,
            function(d) {
                var e = d[0],
                f = a[e];
                f ? f.animate({
                    d: b
                }) : a[e] = a.chart.renderer.path(b).attr({
                    fill: o(d[2], qa(d[1]).setOpacity(o(c.fillOpacity, .75)).get()),
                    zIndex: 0
                }).add(a.group)
            })
        },
        drawLegendSymbol: function(a, b) {
            b.legendSymbol = this.chart.renderer.rect(0, a.baseline - 11, a.options.symbolWidth, 12, 2).attr({
                zIndex: 3
            }).add(b.legendGroup)
        }
    }),
    X.area = G,
    Z.spline = x(Y),
    F = ha(Q, {
        type: "spline",
        getPointSpline: function(a, b, c) {
            var h, i, j, k, d = b.plotX,
            e = b.plotY,
            f = a[c - 1],
            g = a[c + 1];
            if (f && g) {
                a = f.plotY,
                j = g.plotX;
                var l, g = g.plotY;
                h = (1.5 * d + f.plotX) / 2.5,
                i = (1.5 * e + a) / 2.5,
                j = (1.5 * d + j) / 2.5,
                k = (1.5 * e + g) / 2.5,
                l = (k - i) * (j - d) / (j - h) + e - k,
                i += l,
                k += l,
                i > a && i > e ? (i = r(a, e), k = 2 * e - i) : a > i && e > i && (i = J(a, e), k = 2 * e - i),
                k > g && k > e ? (k = r(g, e), i = 2 * e - k) : g > k && e > k && (k = J(g, e), i = 2 * e - k),
                b.rightContX = j,
                b.rightContY = k
            }
            return c ? (b = ["C", f.rightContX || f.plotX, f.rightContY || f.plotY, h || d, i || e, d, e], f.rightContX = f.rightContY = null) : b = ["M", d, e],
            b
        }
    }),
    X.spline = F,
    Z.areaspline = x(Z.area),
    la = G.prototype,
    F = ha(F, {
        type: "areaspline",
        closedStacks: !0,
        getSegmentPath: la.getSegmentPath,
        closeSegment: la.closeSegment,
        drawGraph: la.drawGraph,
        drawLegendSymbol: la.drawLegendSymbol
    }),
    X.areaspline = F,
    Z.column = x(Y, {
        borderColor: "#FFFFFF",
        borderWidth: 1,
        borderRadius: 0,
        groupPadding: .2,
        marker: null,
        pointPadding: .1,
        minPointLength: 0,
        cropThreshold: 50,
        pointRange: null,
        states: {
            hover: {
                brightness: .1,
                shadow: !1
            },
            select: {
                color: "#C0C0C0",
                borderColor: "#000000",
                shadow: !1
            }
        },
        dataLabels: {
            align: null,
            verticalAlign: null,
            y: null
        },
        stickyTracking: !1,
        threshold: 0
    }),
    F = ha(Q, {
        type: "column",
        pointAttrToOptions: {
            stroke: "borderColor",
            "stroke-width": "borderWidth",
            fill: "color",
            r: "borderRadius"
        },
        cropShoulder: 0,
        trackerGroups: ["group", "dataLabelsGroup"],
        negStacks: !0,
        init: function() {
            Q.prototype.init.apply(this, arguments);
            var a = this,
            b = a.chart;
            b.hasRendered && n(b.series,
            function(b) {
                b.type === a.type && (b.isDirty = !0)
            })
        },
        getColumnMetrics: function() {
            var f, h, a = this,
            b = a.options,
            c = a.xAxis,
            d = a.yAxis,
            e = c.reversed,
            g = {},
            i = 0;
            b.grouping === !1 ? i = 1 : n(a.chart.series,
            function(b) {
                var c = b.options,
                e = b.yAxis;
                b.type === a.type && b.visible && d.len === e.len && d.pos === e.pos && (c.stacking ? (f = b.stackKey, g[f] === v && (g[f] = i++), h = g[f]) : c.grouping !== !1 && (h = i++), b.columnIndex = h)
            });
            var c = J(M(c.transA) * (c.ordinalSlope || b.pointRange || c.closestPointRange || 1), c.len),
            j = c * b.groupPadding,
            k = (c - 2 * j) / i,
            l = b.pointWidth,
            b = u(l) ? (k - l) / 2 : k * b.pointPadding,
            l = o(l, k - 2 * b);
            return a.columnMetrics = {
                width: l,
                offset: b + (j + ((e ? i - (a.columnIndex || 0) : a.columnIndex) || 0) * k - c / 2) * (e ? -1 : 1)
            }
        },
        translate: function() {
            var a = this.chart,
            b = this.options,
            c = b.borderWidth,
            d = this.yAxis,
            e = this.translatedThreshold = d.getThreshold(b.threshold),
            f = o(b.minPointLength, 5),
            b = this.getColumnMetrics(),
            g = b.width,
            h = this.barW = wa(r(g, 1 + 2 * c)),
            i = this.pointXOffset = b.offset,
            j = -(c % 2 ? .5 : 0),
            k = c % 2 ? .5 : 1;
            a.renderer.isVML && a.inverted && (k += 1),
            Q.prototype.translate.apply(this),
            n(this.points,
            function(a) {
                var v, b = o(a.yBottom, e),
                c = J(r( - 999 - b, a.plotY), d.len + 999 + b),
                n = a.plotX + i,
                u = h,
                s = J(c, b),
                c = r(c, b) - s;
                M(c) < f && f && (c = f, s = t(M(s - e) > f ? b - f: e - (d.translate(a.y, 0, 1, 0, 1) <= e ? f: 0))),
                a.barX = n,
                a.pointWidth = g,
                b = M(n) < .5,
                u = t(n + u) + j,
                n = t(n) + j,
                u -= n,
                v = M(s) < .5,
                c = t(s + c) + k,
                s = t(s) + k,
                c -= s,
                b && (n += 1, u -= 1),
                v && (s -= 1, c += 1),
                a.shapeType = "rect",
                a.shapeArgs = {
                    x: n,
                    y: s,
                    width: u,
                    height: c
                }
            })
        },
        getSymbol: oa,
        drawLegendSymbol: G.prototype.drawLegendSymbol,
        drawGraph: oa,
        drawPoints: function() {
            var d, a = this,
            b = a.options,
            c = a.chart.renderer;
            n(a.points,
            function(e) {
                var f = e.plotY,
                g = e.graphic;
                f === v || isNaN(f) || null === e.y ? g && (e.graphic = g.destroy()) : (d = e.shapeArgs, g ? (Wa(g), g.animate(x(d))) : e.graphic = c[e.shapeType](d).attr(e.pointAttr[e.selected ? "select": ""]).add(a.group).shadow(b.shadow, null, b.stacking && !b.borderRadius))
            })
        },
        drawTracker: function() {
            var a = this,
            b = a.chart,
            c = b.pointer,
            d = a.options.cursor,
            e = d && {
                cursor: d
            },
            f = function(c) {
                var e, d = c.target;
                for (b.hoverSeries !== a && a.onMouseOver(); d && !e;) e = d.point,
                d = d.parentNode;
                e !== v && e !== b.hoverPoint && e.onMouseOver(c)
            };
            n(a.points,
            function(a) {
                a.graphic && (a.graphic.element.point = a),
                a.dataLabel && (a.dataLabel.element.point = a)
            }),
            a._hasTracking || (n(a.trackerGroups,
            function(b) {
                a[b] && (a[b].addClass("highcharts-tracker").on("mouseover", f).on("mouseout",
                function(a) {
                    c.onTrackerMouseOut(a)
                }).css(e), jb) && a[b].on("touchstart", f)
            }), a._hasTracking = !0)
        },
        alignDataLabel: function(a, b, c, d, e) {
            var f = this.chart,
            g = f.inverted,
            h = a.dlBox || a.shapeArgs,
            i = a.below || a.plotY > o(this.translatedThreshold, f.plotSizeY),
            j = o(c.inside, !!this.options.stacking);
            h && (d = x(h), g && (d = {
                x: f.plotWidth - d.y - d.height,
                y: f.plotHeight - d.x - d.width,
                width: d.height,
                height: d.width
            }), !j) && (g ? (d.x += i ? 0 : d.width, d.width = 0) : (d.y += i ? d.height: 0, d.height = 0)),
            c.align = o(c.align, !g || j ? "center": i ? "right": "left"),
            c.verticalAlign = o(c.verticalAlign, g || j ? "middle": i ? "top": "bottom"),
            Q.prototype.alignDataLabel.call(this, a, b, c, d, e)
        },
        animate: function(a) {
            var b = this.yAxis,
            c = this.options,
            d = this.chart.inverted,
            e = {};
            W && (a ? (e.scaleY = .001, a = J(b.pos + b.len, r(b.pos, b.toPixels(c.threshold))), d ? e.translateX = a - b.len: e.translateY = a, this.group.attr(e)) : (e.scaleY = 1, e[d ? "translateX": "translateY"] = b.pos, this.group.animate(e, this.options.animation), this.animate = null))
        },
        remove: function() {
            var a = this,
            b = a.chart;
            b.hasRendered && n(b.series,
            function(b) {
                b.type === a.type && (b.isDirty = !0)
            }),
            Q.prototype.remove.apply(a, arguments)
        }
    }),
    X.column = F,
    Z.bar = x(Z.column),
    la = ha(F, {
        type: "bar",
        inverted: !0
    }),
    X.bar = la,
    Z.scatter = x(Y, {
        lineWidth: 0,
        tooltip: {
            headerFormat: '<span style="font-size: 10px; color:{series.color}">{series.name}</span><br/>',
            pointFormat: "x: <b>{point.x}</b><br/>y: <b>{point.y}</b><br/>",
            followPointer: !0
        },
        stickyTracking: !1
    }),
    la = ha(Q, {
        type: "scatter",
        sorted: !1,
        requireSorting: !1,
        noSharedTooltip: !0,
        trackerGroups: ["markerGroup"],
        takeOrdinalPosition: !1,
        drawTracker: F.prototype.drawTracker,
        setTooltipPoints: oa
    }),
    X.scatter = la,
    Z.pie = x(Y, {
        borderColor: "#FFFFFF",
        borderWidth: 1,
        center: [null, null],
        clip: !1,
        colorByPoint: !0,
        dataLabels: {
            distance: 30,
            enabled: !0,
            formatter: function() {
                return this.point.name
            }
        },
        ignoreHiddenPoint: !0,
        legendType: "point",
        marker: null,
        size: null,
        showInLegend: !1,
        slicedOffset: 10,
        states: {
            hover: {
                brightness: .1,
                shadow: !1
            }
        },
        stickyTracking: !1,
        tooltip: {
            followPointer: !0
        }
    }),
    Y = {
        type: "pie",
        isCartesian: !1,
        pointClass: ha(Pa, {
            init: function() {
                Pa.prototype.init.apply(this, arguments);
                var b, a = this;
                return a.y < 0 && (a.y = null),
                s(a, {
                    visible: a.visible !== !1,
                    name: o(a.name, "Slice")
                }),
                b = function(b) {
                    a.slice("select" === b.type)
                },
                K(a, "select", b),
                K(a, "unselect", b),
                a
            },
            setVisible: function(a) {
                var e, b = this,
                c = b.series,
                d = c.chart;
                b.visible = b.options.visible = a = a === v ? !b.visible: a,
                c.options.data[pa(b, c.data)] = b.options,
                e = a ? "show": "hide",
                n(["graphic", "dataLabel", "connector", "shadowGroup"],
                function(a) {
                    b[a] && b[a][e]()
                }),
                b.legendItem && d.legend.colorizeItem(b, a),
                !c.isDirty && c.options.ignoreHiddenPoint && (c.isDirty = !0, d.redraw())
            },
            slice: function(a, b, c) {
                var d = this.series;
                La(c, d.chart),
                o(b, !0),
                this.sliced = this.options.sliced = a = u(a) ? a: !this.sliced,
                d.options.data[pa(this, d.data)] = this.options,
                a = a ? this.slicedTranslation: {
                    translateX: 0,
                    translateY: 0
                },
                this.graphic.animate(a),
                this.shadowGroup && this.shadowGroup.animate(a)
            }
        }),
        requireSorting: !1,
        noSharedTooltip: !0,
        trackerGroups: ["group", "dataLabelsGroup"],
        pointAttrToOptions: {
            stroke: "borderColor",
            "stroke-width": "borderWidth",
            fill: "color"
        },
        getColor: oa,
        animate: function(a) {
            var b = this,
            c = b.points,
            d = b.startAngleRad;
            a || (n(c,
            function(a) {
                var c = a.graphic,
                a = a.shapeArgs;
                c && (c.attr({
                    r: b.center[3] / 2,
                    start: d,
                    end: d
                }), c.animate({
                    r: a.r,
                    start: a.start,
                    end: a.end
                },
                b.options.animation))
            }), b.animate = null)
        },
        setData: function(a, b) {
            Q.prototype.setData.call(this, a, !1),
            this.processData(),
            this.generatePoints(),
            o(b, !0) && this.chart.redraw()
        },
        generatePoints: function() {
            var a, c, d, e, b = 0,
            f = this.options.ignoreHiddenPoint;
            for (Q.prototype.generatePoints.call(this), c = this.points, d = c.length, a = 0; d > a; a++) e = c[a],
            b += f && !e.visible ? 0 : e.y;
            for (this.total = b, a = 0; d > a; a++) e = c[a],
            e.percentage = b > 0 ? 100 * (e.y / b) : 0,
            e.total = b
        },
        getCenter: function() {
            var d, h, a = this.options,
            b = this.chart,
            c = 2 * (a.slicedOffset || 0),
            e = b.plotWidth - 2 * c,
            f = b.plotHeight - 2 * c,
            b = a.center,
            a = [o(b[0], "50%"), o(b[1], "50%"), a.size || "100%", a.innerSize || 0],
            g = J(e, f);
            return Na(a,
            function(a, b) {
                return h = /%$/.test(a),
                d = 2 > b || 2 === b && h,
                (h ? [e, f, g, g][b] * y(a) / 100 : a) + (d ? c: 0)
            })
        },
        translate: function(a) {
            this.generatePoints();
            var f, g, h, m, o, b = 0,
            c = this.options,
            d = c.slicedOffset,
            e = d + c.borderWidth,
            i = c.startAngle || 0,
            j = this.startAngleRad = xa / 180 * (i - 90),
            i = (this.endAngleRad = xa / 180 * ((c.endAngle || i + 360) - 90)) - j,
            k = this.points,
            l = c.dataLabels.distance,
            c = c.ignoreHiddenPoint,
            n = k.length;
            for (a || (this.center = a = this.getCenter()), this.getX = function(b, c) {
                return h = R.asin((b - a[1]) / (a[2] / 2 + l)),
                a[0] + (c ? -1 : 1) * V(h) * (a[2] / 2 + l)
            },
            m = 0; n > m; m++) o = k[m],
            f = j + b * i,
            (!c || o.visible) && (b += o.percentage / 100),
            g = j + b * i,
            o.shapeType = "arc",
            o.shapeArgs = {
                x: a[0],
                y: a[1],
                r: a[2] / 2,
                innerR: a[3] / 2,
                start: t(1e3 * f) / 1e3,
                end: t(1e3 * g) / 1e3
            },
            h = (g + f) / 2,
            h > .75 * i && (h -= 2 * xa),
            o.slicedTranslation = {
                translateX: t(V(h) * d),
                translateY: t(ba(h) * d)
            },
            f = V(h) * a[2] / 2,
            g = ba(h) * a[2] / 2,
            o.tooltipPos = [a[0] + .7 * f, a[1] + .7 * g],
            o.half = -xa / 2 > h || h > xa / 2 ? 1 : 0,
            o.angle = h,
            e = J(e, l / 2),
            o.labelPos = [a[0] + f + V(h) * l, a[1] + g + ba(h) * l, a[0] + f + V(h) * e, a[1] + g + ba(h) * e, a[0] + f, a[1] + g, 0 > l ? "center": o.half ? "right": "left", h]
        },
        setTooltipPoints: oa,
        drawGraph: null,
        drawPoints: function() {
            var c, d, f, g, a = this,
            b = a.chart.renderer,
            e = a.options.shadow;
            e && !a.shadowGroup && (a.shadowGroup = b.g("shadow").add(a.group)),
            n(a.points,
            function(h) {
                d = h.graphic,
                g = h.shapeArgs,
                f = h.shadowGroup,
                e && !f && (f = h.shadowGroup = b.g("shadow").add(a.shadowGroup)),
                c = h.sliced ? h.slicedTranslation: {
                    translateX: 0,
                    translateY: 0
                },
                f && f.attr(c),
                d ? d.animate(s(g, c)) : h.graphic = d = b.arc(g).setRadialReference(a.center).attr(h.pointAttr[h.selected ? "select": ""]).attr({
                    "stroke-linejoin": "round"
                }).attr(c).add(a.group).shadow(e, f),
                h.visible === !1 && h.setVisible(!1)
            })
        },
        sortByAngle: function(a, b) {
            a.sort(function(a, d) {
                return void 0 !== a.angle && (d.angle - a.angle) * b
            })
        },
        drawDataLabels: function() {
            var c, i, j, s, v, w, x, z, A, E, H, C, a = this,
            b = a.data,
            d = a.chart,
            e = a.options.dataLabels,
            f = o(e.connectorPadding, 10),
            g = o(e.connectorWidth, 1),
            h = d.plotWidth,
            d = d.plotHeight,
            k = o(e.softConnector, !0),
            l = e.distance,
            m = a.center,
            p = m[2] / 2,
            q = m[1],
            u = l > 0,
            y = [[], []],
            D = [0, 0, 0, 0],
            J = function(a, b) {
                return b.y - a.y
            };
            if (a.visible && (e.enabled || a._hasPointLabels)) {
                for (Q.prototype.drawDataLabels.apply(a), n(b,
                function(a) {
                    a.dataLabel && y[a.half].push(a)
                }), H = 0; ! x && b[H];) x = b[H] && b[H].dataLabel && (b[H].dataLabel.getBBox().height || 21),
                H++;
                for (H = 2; H--;) {
                    var F, b = [],
                    I = [],
                    G = y[H],
                    K = G.length;
                    if (a.sortByAngle(G, H - .5), l > 0) {
                        for (C = q - p - l; q + p + l >= C; C += x) b.push(C);
                        if (v = b.length, K > v) {
                            for (c = [].concat(G), c.sort(J), C = K; C--;) c[C].rank = C;
                            for (C = K; C--;) G[C].rank >= v && G.splice(C, 1);
                            K = G.length
                        }
                        for (C = 0; K > C; C++) {
                            c = G[C],
                            w = c.labelPos,
                            c = 9999;
                            var N, L;
                            for (L = 0; v > L; L++) N = M(b[L] - w[1]),
                            c > N && (c = N, F = L);
                            if (C > F && null !== b[C]) F = C;
                            else for (K - C + F > v && null !== b[C] && (F = v - K + C); null === b[F];) F++;
                            I.push({
                                i: F,
                                y: b[F]
                            }),
                            b[F] = null
                        }
                        I.sort(J)
                    }
                    for (C = 0; K > C; C++) c = G[C],
                    w = c.labelPos,
                    s = c.dataLabel,
                    E = c.visible === !1 ? "hidden": "visible",
                    c = w[1],
                    l > 0 ? (v = I.pop(), F = v.i, A = v.y, (c > A && null !== b[F + 1] || A > c && null !== b[F - 1]) && (A = c)) : A = c,
                    z = e.justify ? m[0] + (H ? -1 : 1) * (p + l) : a.getX(0 === F || F === b.length - 1 ? c: A, H),
                    s._attr = {
                        visibility: E,
                        align: w[6]
                    },
                    s._pos = {
                        x: z + e.x + ({
                            left: f,
                            right: -f
                        } [w[6]] || 0),
                        y: A + e.y - 10
                    },
                    s.connX = z,
                    s.connY = A,
                    null === this.options.size && (v = s.width, f > z - v ? D[3] = r(t(v - z + f), D[3]) : z + v > h - f && (D[1] = r(t(z + v - h + f), D[1])), 0 > A - x / 2 ? D[0] = r(t( - A + x / 2), D[0]) : A + x / 2 > d && (D[2] = r(t(A + x / 2 - d), D[2])))
                } (0 === ua(D) || this.verifyDataLabelOverflow(D)) && (this.placeDataLabels(), u && g && n(this.points,
                function(b) {
                    i = b.connector,
                    w = b.labelPos,
                    (s = b.dataLabel) && s._pos ? (E = s._attr.visibility, z = s.connX, A = s.connY, j = k ? ["M", z + ("left" === w[6] ? 5 : -5), A, "C", z, A, 2 * w[2] - w[4], 2 * w[3] - w[5], w[2], w[3], "L", w[4], w[5]] : ["M", z + ("left" === w[6] ? 5 : -5), A, "L", w[2], w[3], "L", w[4], w[5]], i ? (i.animate({
                        d: j
                    }), i.attr("visibility", E)) : b.connector = i = a.chart.renderer.path(j).attr({
                        "stroke-width": g,
                        stroke: e.connectorColor || b.color || "#606060",
                        visibility: E
                    }).add(a.group)) : i && (b.connector = i.destroy())
                }))
            }
        },
        verifyDataLabelOverflow: function(a) {
            var f, b = this.center,
            c = this.options,
            d = c.center,
            e = c = c.minSize || 80;
            return null !== d[0] ? e = r(b[2] - r(a[1], a[3]), c) : (e = r(b[2] - a[1] - a[3], c), b[0] += (a[3] - a[1]) / 2),
            null !== d[1] ? e = r(J(e, b[2] - r(a[0], a[2])), c) : (e = r(J(e, b[2] - a[0] - a[2]), c), b[1] += (a[0] - a[2]) / 2),
            e < b[2] ? (b[2] = e, this.translate(b), n(this.points,
            function(a) {
                a.dataLabel && (a.dataLabel._pos = null)
            }), this.drawDataLabels()) : f = !0,
            f
        },
        placeDataLabels: function() {
            n(this.points,
            function(a) {
                var b, a = a.dataLabel;
                a && ((b = a._pos) ? (a.attr(a._attr), a[a.moved ? "animate": "attr"](b), a.moved = !0) : a && a.attr({
                    y: -999
                }))
            })
        },
        alignDataLabel: oa,
        drawTracker: F.prototype.drawTracker,
        drawLegendSymbol: G.prototype.drawLegendSymbol,
        getSymbol: oa
    },
    Y = ha(Q, Y),
    X.pie = Y,
    s(Highcharts, {
        Axis: eb,
        Chart: yb,
        Color: qa,
        Legend: fb,
        Pointer: xb,
        Point: Pa,
        Tick: Ma,
        Tooltip: wb,
        Renderer: Va,
        Series: Q,
        SVGElement: va,
        SVGRenderer: za,
        arrayMin: Ja,
        arrayMax: ua,
        charts: Ga,
        dateFormat: Ya,
        format: Ca,
        pathAnim: Ab,
        getOptions: function() {
            return L
        },
        hasBidiBug: Ub,
        isTouchDevice: Ob,
        numberFormat: Aa,
        seriesTypes: X,
        setOptions: function(a) {
            return L = x(L, a),
            Lb(),
            L
        },
        addEvent: K,
        removeEvent: $,
        createElement: U,
        discardElement: Ta,
        css: I,
        each: n,
        extend: s,
        map: Na,
        merge: x,
        pick: o,
        splat: ja,
        extendClass: ha,
        pInt: y,
        wrap: mb,
        svg: W,
        canvas: ca,
        vml: !W && !ca,
        product: "Highcharts",
        version: "3.0.7"
    })
} (),
function(e, t) {
    function i(t, i) {
        var s, a, o, r = t.nodeName.toLowerCase();
        return "area" === r ? (s = t.parentNode, a = s.name, t.href && a && "map" === s.nodeName.toLowerCase() ? (o = e("img[usemap=#" + a + "]")[0], !!o && n(o)) : !1) : (/input|select|textarea|button|object/.test(r) ? !t.disabled: "a" === r ? t.href || i: i) && n(t)
    }
    function n(t) {
        return e.expr.filters.visible(t) && !e(t).parents().addBack().filter(function() {
            return "hidden" === e.css(this, "visibility")
        }).length
    }
    var s = 0,
    a = /^ui-id-\d+$/;
    e.ui = e.ui || {},
    e.extend(e.ui, {
        version: "1.10.3",
        keyCode: {
            BACKSPACE: 8,
            COMMA: 188,
            DELETE: 46,
            DOWN: 40,
            END: 35,
            ENTER: 13,
            ESCAPE: 27,
            HOME: 36,
            LEFT: 37,
            NUMPAD_ADD: 107,
            NUMPAD_DECIMAL: 110,
            NUMPAD_DIVIDE: 111,
            NUMPAD_ENTER: 108,
            NUMPAD_MULTIPLY: 106,
            NUMPAD_SUBTRACT: 109,
            PAGE_DOWN: 34,
            PAGE_UP: 33,
            PERIOD: 190,
            RIGHT: 39,
            SPACE: 32,
            TAB: 9,
            UP: 38
        }
    }),
    e.fn.extend({
        focus: function(t) {
            return function(i, n) {
                return "number" == typeof i ? this.each(function() {
                    var t = this;
                    setTimeout(function() {
                        e(t).focus(),
                        n && n.call(t)
                    },
                    i)
                }) : t.apply(this, arguments)
            }
        } (e.fn.focus),
        scrollParent: function() {
            var t;
            return t = e.ui.ie && /(static|relative)/.test(this.css("position")) || /absolute/.test(this.css("position")) ? this.parents().filter(function() {
                return /(relative|absolute|fixed)/.test(e.css(this, "position")) && /(auto|scroll)/.test(e.css(this, "overflow") + e.css(this, "overflow-y") + e.css(this, "overflow-x"))
            }).eq(0) : this.parents().filter(function() {
                return /(auto|scroll)/.test(e.css(this, "overflow") + e.css(this, "overflow-y") + e.css(this, "overflow-x"))
            }).eq(0),
            /fixed/.test(this.css("position")) || !t.length ? e(document) : t
        },
        zIndex: function(i) {
            if (i !== t) return this.css("zIndex", i);
            if (this.length) for (var n, s, a = e(this[0]); a.length && a[0] !== document;) {
                if (n = a.css("position"), ("absolute" === n || "relative" === n || "fixed" === n) && (s = parseInt(a.css("zIndex"), 10), !isNaN(s) && 0 !== s)) return s;
                a = a.parent()
            }
            return 0
        },
        uniqueId: function() {
            return this.each(function() {
                this.id || (this.id = "ui-id-" + ++s)
            })
        },
        removeUniqueId: function() {
            return this.each(function() {
                a.test(this.id) && e(this).removeAttr("id")
            })
        }
    }),
    e.extend(e.expr[":"], {
        data: e.expr.createPseudo ? e.expr.createPseudo(function(t) {
            return function(i) {
                return !! e.data(i, t)
            }
        }) : function(t, i, n) {
            return !! e.data(t, n[3])
        },
        focusable: function(t) {
            return i(t, !isNaN(e.attr(t, "tabindex")))
        },
        tabbable: function(t) {
            var n = e.attr(t, "tabindex"),
            s = isNaN(n);
            return (s || n >= 0) && i(t, !s)
        }
    }),
    e("<a>").outerWidth(1).jquery || e.each(["Width", "Height"],
    function(i, n) {
        function s(t, i, n, s) {
            return e.each(a,
            function() {
                i -= parseFloat(e.css(t, "padding" + this)) || 0,
                n && (i -= parseFloat(e.css(t, "border" + this + "Width")) || 0),
                s && (i -= parseFloat(e.css(t, "margin" + this)) || 0)
            }),
            i
        }
        var a = "Width" === n ? ["Left", "Right"] : ["Top", "Bottom"],
        o = n.toLowerCase(),
        r = {
            innerWidth: e.fn.innerWidth,
            innerHeight: e.fn.innerHeight,
            outerWidth: e.fn.outerWidth,
            outerHeight: e.fn.outerHeight
        };
        e.fn["inner" + n] = function(i) {
            return i === t ? r["inner" + n].call(this) : this.each(function() {
                e(this).css(o, s(this, i) + "px")
            })
        },
        e.fn["outer" + n] = function(t, i) {
            return "number" != typeof t ? r["outer" + n].call(this, t) : this.each(function() {
                e(this).css(o, s(this, t, !0, i) + "px")
            })
        }
    }),
    e.fn.addBack || (e.fn.addBack = function(e) {
        return this.add(null == e ? this.prevObject: this.prevObject.filter(e))
    }),
    e("<a>").data("a-b", "a").removeData("a-b").data("a-b") && (e.fn.removeData = function(t) {
        return function(i) {
            return arguments.length ? t.call(this, e.camelCase(i)) : t.call(this)
        }
    } (e.fn.removeData)),
    e.ui.ie = !!/msie [\w.]+/.exec(navigator.userAgent.toLowerCase()),
    e.support.selectstart = "onselectstart" in document.createElement("div"),
    e.fn.extend({
        disableSelection: function() {
            return this.bind((e.support.selectstart ? "selectstart": "mousedown") + ".ui-disableSelection",
            function(e) {
                e.preventDefault()
            })
        },
        enableSelection: function() {
            return this.unbind(".ui-disableSelection")
        }
    }),
    e.extend(e.ui, {
        plugin: {
            add: function(t, i, n) {
                var s, a = e.ui[t].prototype;
                for (s in n) a.plugins[s] = a.plugins[s] || [],
                a.plugins[s].push([i, n[s]])
            },
            call: function(e, t, i) {
                var n, s = e.plugins[t];
                if (s && e.element[0].parentNode && 11 !== e.element[0].parentNode.nodeType) for (n = 0; s.length > n; n++) e.options[s[n][0]] && s[n][1].apply(e.element, i)
            }
        },
        hasScroll: function(t, i) {
            if ("hidden" === e(t).css("overflow")) return ! 1;
            var n = i && "left" === i ? "scrollLeft": "scrollTop",
            s = !1;
            return t[n] > 0 ? !0 : (t[n] = 1, s = t[n] > 0, t[n] = 0, s)
        }
    })
} (jQuery),
function(t, e) {
    var i = 0,
    s = Array.prototype.slice,
    n = t.cleanData;
    t.cleanData = function(e) {
        for (var i, s = 0; null != (i = e[s]); s++) try {
            t(i).triggerHandler("remove")
        } catch(o) {}
        n(e)
    },
    t.widget = function(i, s, n) {
        var o, a, r, h, l = {},
        c = i.split(".")[0];
        i = i.split(".")[1],
        o = c + "-" + i,
        n || (n = s, s = t.Widget),
        t.expr[":"][o.toLowerCase()] = function(e) {
            return !! t.data(e, o)
        },
        t[c] = t[c] || {},
        a = t[c][i],
        r = t[c][i] = function(t, i) {
            return this._createWidget ? (arguments.length && this._createWidget(t, i), e) : new r(t, i)
        },
        t.extend(r, a, {
            version: n.version,
            _proto: t.extend({},
            n),
            _childConstructors: []
        }),
        h = new s,
        h.options = t.widget.extend({},
        h.options),
        t.each(n,
        function(i, n) {
            return t.isFunction(n) ? (l[i] = function() {
                var t = function() {
                    return s.prototype[i].apply(this, arguments)
                },
                e = function(t) {
                    return s.prototype[i].apply(this, t)
                };
                return function() {
                    var i, s = this._super,
                    o = this._superApply;
                    return this._super = t,
                    this._superApply = e,
                    i = n.apply(this, arguments),
                    this._super = s,
                    this._superApply = o,
                    i
                }
            } (), e) : (l[i] = n, e)
        }),
        r.prototype = t.widget.extend(h, {
            widgetEventPrefix: a ? h.widgetEventPrefix: i
        },
        l, {
            constructor: r,
            namespace: c,
            widgetName: i,
            widgetFullName: o
        }),
        a ? (t.each(a._childConstructors,
        function(e, i) {
            var s = i.prototype;
            t.widget(s.namespace + "." + s.widgetName, r, i._proto)
        }), delete a._childConstructors) : s._childConstructors.push(r),
        t.widget.bridge(i, r)
    },
    t.widget.extend = function(i) {
        for (var n, o, a = s.call(arguments, 1), r = 0, h = a.length; h > r; r++) for (n in a[r]) o = a[r][n],
        a[r].hasOwnProperty(n) && o !== e && (i[n] = t.isPlainObject(o) ? t.isPlainObject(i[n]) ? t.widget.extend({},
        i[n], o) : t.widget.extend({},
        o) : o);
        return i
    },
    t.widget.bridge = function(i, n) {
        var o = n.prototype.widgetFullName || i;
        t.fn[i] = function(a) {
            var r = "string" == typeof a,
            h = s.call(arguments, 1),
            l = this;
            return a = !r && h.length ? t.widget.extend.apply(null, [a].concat(h)) : a,
            r ? this.each(function() {
                var s, n = t.data(this, o);
                return n ? t.isFunction(n[a]) && "_" !== a.charAt(0) ? (s = n[a].apply(n, h), s !== n && s !== e ? (l = s && s.jquery ? l.pushStack(s.get()) : s, !1) : e) : t.error("no such method '" + a + "' for " + i + " widget instance") : t.error("cannot call methods on " + i + " prior to initialization; attempted to call method '" + a + "'")
            }) : this.each(function() {
                var e = t.data(this, o);
                e ? e.option(a || {})._init() : t.data(this, o, new n(a, this))
            }),
            l
        }
    },
    t.Widget = function() {},
    t.Widget._childConstructors = [],
    t.Widget.prototype = {
        widgetName: "widget",
        widgetEventPrefix: "",
        defaultElement: "<div>",
        options: {
            disabled: !1,
            create: null
        },
        _createWidget: function(e, s) {
            s = t(s || this.defaultElement || this)[0],
            this.element = t(s),
            this.uuid = i++,
            this.eventNamespace = "." + this.widgetName + this.uuid,
            this.options = t.widget.extend({},
            this.options, this._getCreateOptions(), e),
            this.bindings = t(),
            this.hoverable = t(),
            this.focusable = t(),
            s !== this && (t.data(s, this.widgetFullName, this), this._on(!0, this.element, {
                remove: function(t) {
                    t.target === s && this.destroy()
                }
            }), this.document = t(s.style ? s.ownerDocument: s.document || s), this.window = t(this.document[0].defaultView || this.document[0].parentWindow)),
            this._create(),
            this._trigger("create", null, this._getCreateEventData()),
            this._init()
        },
        _getCreateOptions: t.noop,
        _getCreateEventData: t.noop,
        _create: t.noop,
        _init: t.noop,
        destroy: function() {
            this._destroy(),
            this.element.unbind(this.eventNamespace).removeData(this.widgetName).removeData(this.widgetFullName).removeData(t.camelCase(this.widgetFullName)),
            this.widget().unbind(this.eventNamespace).removeAttr("aria-disabled").removeClass(this.widgetFullName + "-disabled ui-state-disabled"),
            this.bindings.unbind(this.eventNamespace),
            this.hoverable.removeClass("ui-state-hover"),
            this.focusable.removeClass("ui-state-focus")
        },
        _destroy: t.noop,
        widget: function() {
            return this.element
        },
        option: function(i, s) {
            var n, o, a, r = i;
            if (0 === arguments.length) return t.widget.extend({},
            this.options);
            if ("string" == typeof i) if (r = {},
            n = i.split("."), i = n.shift(), n.length) {
                for (o = r[i] = t.widget.extend({},
                this.options[i]), a = 0; n.length - 1 > a; a++) o[n[a]] = o[n[a]] || {},
                o = o[n[a]];
                if (i = n.pop(), s === e) return o[i] === e ? null: o[i];
                o[i] = s
            } else {
                if (s === e) return this.options[i] === e ? null: this.options[i];
                r[i] = s
            }
            return this._setOptions(r),
            this
        },
        _setOptions: function(t) {
            var e;
            for (e in t) this._setOption(e, t[e]);
            return this
        },
        _setOption: function(t, e) {
            return this.options[t] = e,
            "disabled" === t && (this.widget().toggleClass(this.widgetFullName + "-disabled ui-state-disabled", !!e).attr("aria-disabled", e), this.hoverable.removeClass("ui-state-hover"), this.focusable.removeClass("ui-state-focus")),
            this
        },
        enable: function() {
            return this._setOption("disabled", !1)
        },
        disable: function() {
            return this._setOption("disabled", !0)
        },
        _on: function(i, s, n) {
            var o, a = this;
            "boolean" != typeof i && (n = s, s = i, i = !1),
            n ? (s = o = t(s), this.bindings = this.bindings.add(s)) : (n = s, s = this.element, o = this.widget()),
            t.each(n,
            function(n, r) {
                function h() {
                    return i || a.options.disabled !== !0 && !t(this).hasClass("ui-state-disabled") ? ("string" == typeof r ? a[r] : r).apply(a, arguments) : e
                }
                "string" != typeof r && (h.guid = r.guid = r.guid || h.guid || t.guid++);
                var l = n.match(/^(\w+)\s*(.*)$/),
                c = l[1] + a.eventNamespace,
                u = l[2];
                u ? o.delegate(u, c, h) : s.bind(c, h)
            })
        },
        _off: function(t, e) {
            e = (e || "").split(" ").join(this.eventNamespace + " ") + this.eventNamespace,
            t.unbind(e).undelegate(e)
        },
        _delay: function(t, e) {
            function i() {
                return ("string" == typeof t ? s[t] : t).apply(s, arguments)
            }
            var s = this;
            return setTimeout(i, e || 0)
        },
        _hoverable: function(e) {
            this.hoverable = this.hoverable.add(e),
            this._on(e, {
                mouseenter: function(e) {
                    t(e.currentTarget).addClass("ui-state-hover")
                },
                mouseleave: function(e) {
                    t(e.currentTarget).removeClass("ui-state-hover")
                }
            })
        },
        _focusable: function(e) {
            this.focusable = this.focusable.add(e),
            this._on(e, {
                focusin: function(e) {
                    t(e.currentTarget).addClass("ui-state-focus")
                },
                focusout: function(e) {
                    t(e.currentTarget).removeClass("ui-state-focus")
                }
            })
        },
        _trigger: function(e, i, s) {
            var n, o, a = this.options[e];
            if (s = s || {},
            i = t.Event(i), i.type = (e === this.widgetEventPrefix ? e: this.widgetEventPrefix + e).toLowerCase(), i.target = this.element[0], o = i.originalEvent) for (n in o) n in i || (i[n] = o[n]);
            return this.element.trigger(i, s),
            !(t.isFunction(a) && a.apply(this.element[0], [i].concat(s)) === !1 || i.isDefaultPrevented())
        }
    },
    t.each({
        show: "fadeIn",
        hide: "fadeOut"
    },
    function(e, i) {
        t.Widget.prototype["_" + e] = function(s, n, o) {
            "string" == typeof n && (n = {
                effect: n
            });
            var a, r = n ? n === !0 || "number" == typeof n ? i: n.effect || i: e;
            n = n || {},
            "number" == typeof n && (n = {
                duration: n
            }),
            a = !t.isEmptyObject(n),
            n.complete = o,
            n.delay && s.delay(n.delay),
            a && t.effects && t.effects.effect[r] ? s[e](n) : r !== e && s[r] ? s[r](n.duration, n.easing, o) : s.queue(function(i) {
                t(this)[e](),
                o && o.call(s[0]),
                i()
            })
        }
    })
} (jQuery),
function(t) {
    var e = !1;
    t(document).mouseup(function() {
        e = !1
    }),
    t.widget("ui.mouse", {
        version: "1.10.3",
        options: {
            cancel: "input,textarea,button,select,option",
            distance: 1,
            delay: 0
        },
        _mouseInit: function() {
            var e = this;
            this.element.bind("mousedown." + this.widgetName,
            function(t) {
                return e._mouseDown(t)
            }).bind("click." + this.widgetName,
            function(i) {
                return ! 0 === t.data(i.target, e.widgetName + ".preventClickEvent") ? (t.removeData(i.target, e.widgetName + ".preventClickEvent"), i.stopImmediatePropagation(), !1) : void 0
            }),
            this.started = !1
        },
        _mouseDestroy: function() {
            this.element.unbind("." + this.widgetName),
            this._mouseMoveDelegate && t(document).unbind("mousemove." + this.widgetName, this._mouseMoveDelegate).unbind("mouseup." + this.widgetName, this._mouseUpDelegate)
        },
        _mouseDown: function(i) {
            if (!e) {
                this._mouseStarted && this._mouseUp(i),
                this._mouseDownEvent = i;
                var s = this,
                n = 1 === i.which,
                a = "string" == typeof this.options.cancel && i.target.nodeName ? t(i.target).closest(this.options.cancel).length: !1;
                return n && !a && this._mouseCapture(i) ? (this.mouseDelayMet = !this.options.delay, this.mouseDelayMet || (this._mouseDelayTimer = setTimeout(function() {
                    s.mouseDelayMet = !0
                },
                this.options.delay)), this._mouseDistanceMet(i) && this._mouseDelayMet(i) && (this._mouseStarted = this._mouseStart(i) !== !1, !this._mouseStarted) ? (i.preventDefault(), !0) : (!0 === t.data(i.target, this.widgetName + ".preventClickEvent") && t.removeData(i.target, this.widgetName + ".preventClickEvent"), this._mouseMoveDelegate = function(t) {
                    return s._mouseMove(t)
                },
                this._mouseUpDelegate = function(t) {
                    return s._mouseUp(t)
                },
                t(document).bind("mousemove." + this.widgetName, this._mouseMoveDelegate).bind("mouseup." + this.widgetName, this._mouseUpDelegate), i.preventDefault(), e = !0, !0)) : !0
            }
        },
        _mouseMove: function(e) {
            return t.ui.ie && (!document.documentMode || 9 > document.documentMode) && !e.button ? this._mouseUp(e) : this._mouseStarted ? (this._mouseDrag(e), e.preventDefault()) : (this._mouseDistanceMet(e) && this._mouseDelayMet(e) && (this._mouseStarted = this._mouseStart(this._mouseDownEvent, e) !== !1, this._mouseStarted ? this._mouseDrag(e) : this._mouseUp(e)), !this._mouseStarted)
        },
        _mouseUp: function(e) {
            return t(document).unbind("mousemove." + this.widgetName, this._mouseMoveDelegate).unbind("mouseup." + this.widgetName, this._mouseUpDelegate),
            this._mouseStarted && (this._mouseStarted = !1, e.target === this._mouseDownEvent.target && t.data(e.target, this.widgetName + ".preventClickEvent", !0), this._mouseStop(e)),
            !1
        },
        _mouseDistanceMet: function(t) {
            return Math.max(Math.abs(this._mouseDownEvent.pageX - t.pageX), Math.abs(this._mouseDownEvent.pageY - t.pageY)) >= this.options.distance
        },
        _mouseDelayMet: function() {
            return this.mouseDelayMet
        },
        _mouseStart: function() {},
        _mouseDrag: function() {},
        _mouseStop: function() {},
        _mouseCapture: function() {
            return ! 0
        }
    })
} (jQuery),
function(t, e) {
    function i(t, e, i) {
        return [parseFloat(t[0]) * (p.test(t[0]) ? e / 100 : 1), parseFloat(t[1]) * (p.test(t[1]) ? i / 100 : 1)]
    }
    function s(e, i) {
        return parseInt(t.css(e, i), 10) || 0
    }
    function n(e) {
        var i = e[0];
        return 9 === i.nodeType ? {
            width: e.width(),
            height: e.height(),
            offset: {
                top: 0,
                left: 0
            }
        }: t.isWindow(i) ? {
            width: e.width(),
            height: e.height(),
            offset: {
                top: e.scrollTop(),
                left: e.scrollLeft()
            }
        }: i.preventDefault ? {
            width: 0,
            height: 0,
            offset: {
                top: i.pageY,
                left: i.pageX
            }
        }: {
            width: e.outerWidth(),
            height: e.outerHeight(),
            offset: e.offset()
        }
    }
    t.ui = t.ui || {};
    var a, o = Math.max,
    r = Math.abs,
    l = Math.round,
    h = /left|center|right/,
    c = /top|center|bottom/,
    u = /[\+\-]\d+(\.[\d]+)?%?/,
    d = /^\w+/,
    p = /%$/,
    f = t.fn.position;
    t.position = {
        scrollbarWidth: function() {
            if (a !== e) return a;
            var i, s, n = t("<div style='display:block;width:50px;height:50px;overflow:hidden;'><div style='height:100px;width:auto;'></div></div>"),
            o = n.children()[0];
            return t("body").append(n),
            i = o.offsetWidth,
            n.css("overflow", "scroll"),
            s = o.offsetWidth,
            i === s && (s = n[0].clientWidth),
            n.remove(),
            a = i - s
        },
        getScrollInfo: function(e) {
            var i = e.isWindow ? "": e.element.css("overflow-x"),
            s = e.isWindow ? "": e.element.css("overflow-y"),
            n = "scroll" === i || "auto" === i && e.width < e.element[0].scrollWidth,
            a = "scroll" === s || "auto" === s && e.height < e.element[0].scrollHeight;
            return {
                width: a ? t.position.scrollbarWidth() : 0,
                height: n ? t.position.scrollbarWidth() : 0
            }
        },
        getWithinInfo: function(e) {
            var i = t(e || window),
            s = t.isWindow(i[0]);
            return {
                element: i,
                isWindow: s,
                offset: i.offset() || {
                    left: 0,
                    top: 0
                },
                scrollLeft: i.scrollLeft(),
                scrollTop: i.scrollTop(),
                width: s ? i.width() : i.outerWidth(),
                height: s ? i.height() : i.outerHeight()
            }
        }
    },
    t.fn.position = function(e) {
        if (!e || !e.of) return f.apply(this, arguments);
        e = t.extend({},
        e);
        var a, p, g, m, v, _, b = t(e.of),
        y = t.position.getWithinInfo(e.within),
        k = t.position.getScrollInfo(y),
        w = (e.collision || "flip").split(" "),
        D = {};
        return _ = n(b),
        b[0].preventDefault && (e.at = "left top"),
        p = _.width,
        g = _.height,
        m = _.offset,
        v = t.extend({},
        m),
        t.each(["my", "at"],
        function() {
            var t, i, s = (e[this] || "").split(" ");
            1 === s.length && (s = h.test(s[0]) ? s.concat(["center"]) : c.test(s[0]) ? ["center"].concat(s) : ["center", "center"]),
            s[0] = h.test(s[0]) ? s[0] : "center",
            s[1] = c.test(s[1]) ? s[1] : "center",
            t = u.exec(s[0]),
            i = u.exec(s[1]),
            D[this] = [t ? t[0] : 0, i ? i[0] : 0],
            e[this] = [d.exec(s[0])[0], d.exec(s[1])[0]]
        }),
        1 === w.length && (w[1] = w[0]),
        "right" === e.at[0] ? v.left += p: "center" === e.at[0] && (v.left += p / 2),
        "bottom" === e.at[1] ? v.top += g: "center" === e.at[1] && (v.top += g / 2),
        a = i(D.at, p, g),
        v.left += a[0],
        v.top += a[1],
        this.each(function() {
            var n, h, c = t(this),
            u = c.outerWidth(),
            d = c.outerHeight(),
            f = s(this, "marginLeft"),
            _ = s(this, "marginTop"),
            x = u + f + s(this, "marginRight") + k.width,
            C = d + _ + s(this, "marginBottom") + k.height,
            M = t.extend({},
            v),
            T = i(D.my, c.outerWidth(), c.outerHeight());
            "right" === e.my[0] ? M.left -= u: "center" === e.my[0] && (M.left -= u / 2),
            "bottom" === e.my[1] ? M.top -= d: "center" === e.my[1] && (M.top -= d / 2),
            M.left += T[0],
            M.top += T[1],
            t.support.offsetFractions || (M.left = l(M.left), M.top = l(M.top)),
            n = {
                marginLeft: f,
                marginTop: _
            },
            t.each(["left", "top"],
            function(i, s) {
                t.ui.position[w[i]] && t.ui.position[w[i]][s](M, {
                    targetWidth: p,
                    targetHeight: g,
                    elemWidth: u,
                    elemHeight: d,
                    collisionPosition: n,
                    collisionWidth: x,
                    collisionHeight: C,
                    offset: [a[0] + T[0], a[1] + T[1]],
                    my: e.my,
                    at: e.at,
                    within: y,
                    elem: c
                })
            }),
            e.using && (h = function(t) {
                var i = m.left - M.left,
                s = i + p - u,
                n = m.top - M.top,
                a = n + g - d,
                l = {
                    target: {
                        element: b,
                        left: m.left,
                        top: m.top,
                        width: p,
                        height: g
                    },
                    element: {
                        element: c,
                        left: M.left,
                        top: M.top,
                        width: u,
                        height: d
                    },
                    horizontal: 0 > s ? "left": i > 0 ? "right": "center",
                    vertical: 0 > a ? "top": n > 0 ? "bottom": "middle"
                };
                u > p && p > r(i + s) && (l.horizontal = "center"),
                d > g && g > r(n + a) && (l.vertical = "middle"),
                l.important = o(r(i), r(s)) > o(r(n), r(a)) ? "horizontal": "vertical",
                e.using.call(this, t, l)
            }),
            c.offset(t.extend(M, {
                using: h
            }))
        })
    },
    t.ui.position = {
        fit: {
            left: function(t, e) {
                var i, s = e.within,
                n = s.isWindow ? s.scrollLeft: s.offset.left,
                a = s.width,
                r = t.left - e.collisionPosition.marginLeft,
                l = n - r,
                h = r + e.collisionWidth - a - n;
                e.collisionWidth > a ? l > 0 && 0 >= h ? (i = t.left + l + e.collisionWidth - a - n, t.left += l - i) : t.left = h > 0 && 0 >= l ? n: l > h ? n + a - e.collisionWidth: n: l > 0 ? t.left += l: h > 0 ? t.left -= h: t.left = o(t.left - r, t.left)
            },
            top: function(t, e) {
                var i, s = e.within,
                n = s.isWindow ? s.scrollTop: s.offset.top,
                a = e.within.height,
                r = t.top - e.collisionPosition.marginTop,
                l = n - r,
                h = r + e.collisionHeight - a - n;
                e.collisionHeight > a ? l > 0 && 0 >= h ? (i = t.top + l + e.collisionHeight - a - n, t.top += l - i) : t.top = h > 0 && 0 >= l ? n: l > h ? n + a - e.collisionHeight: n: l > 0 ? t.top += l: h > 0 ? t.top -= h: t.top = o(t.top - r, t.top)
            }
        },
        flip: {
            left: function(t, e) {
                var i, s, n = e.within,
                a = n.offset.left + n.scrollLeft,
                o = n.width,
                l = n.isWindow ? n.scrollLeft: n.offset.left,
                h = t.left - e.collisionPosition.marginLeft,
                c = h - l,
                u = h + e.collisionWidth - o - l,
                d = "left" === e.my[0] ? -e.elemWidth: "right" === e.my[0] ? e.elemWidth: 0,
                p = "left" === e.at[0] ? e.targetWidth: "right" === e.at[0] ? -e.targetWidth: 0,
                f = -2 * e.offset[0];
                0 > c ? (i = t.left + d + p + f + e.collisionWidth - o - a, (0 > i || r(c) > i) && (t.left += d + p + f)) : u > 0 && (s = t.left - e.collisionPosition.marginLeft + d + p + f - l, (s > 0 || u > r(s)) && (t.left += d + p + f))
            },
            top: function(t, e) {
                var i, s, n = e.within,
                a = n.offset.top + n.scrollTop,
                o = n.height,
                l = n.isWindow ? n.scrollTop: n.offset.top,
                h = t.top - e.collisionPosition.marginTop,
                c = h - l,
                u = h + e.collisionHeight - o - l,
                d = "top" === e.my[1],
                p = d ? -e.elemHeight: "bottom" === e.my[1] ? e.elemHeight: 0,
                f = "top" === e.at[1] ? e.targetHeight: "bottom" === e.at[1] ? -e.targetHeight: 0,
                g = -2 * e.offset[1];
                0 > c ? (s = t.top + p + f + g + e.collisionHeight - o - a, t.top + p + f + g > c && (0 > s || r(c) > s) && (t.top += p + f + g)) : u > 0 && (i = t.top - e.collisionPosition.marginTop + p + f + g - l, t.top + p + f + g > u && (i > 0 || u > r(i)) && (t.top += p + f + g))
            }
        },
        flipfit: {
            left: function() {
                t.ui.position.flip.left.apply(this, arguments),
                t.ui.position.fit.left.apply(this, arguments)
            },
            top: function() {
                t.ui.position.flip.top.apply(this, arguments),
                t.ui.position.fit.top.apply(this, arguments)
            }
        }
    },
    function() {
        var e, i, s, n, a, o = document.getElementsByTagName("body")[0],
        r = document.createElement("div");
        e = document.createElement(o ? "div": "body"),
        s = {
            visibility: "hidden",
            width: 0,
            height: 0,
            border: 0,
            margin: 0,
            background: "none"
        },
        o && t.extend(s, {
            position: "absolute",
            left: "-1000px",
            top: "-1000px"
        });
        for (a in s) e.style[a] = s[a];
        e.appendChild(r),
        i = o || document.documentElement,
        i.insertBefore(e, i.firstChild),
        r.style.cssText = "position: absolute; left: 10.7432222px;",
        n = t(r).offset().left,
        t.support.offsetFractions = n > 10 && 11 > n,
        e.innerHTML = "",
        i.removeChild(e)
    } ()
} (jQuery),
function(t) {
    t.widget("ui.draggable", t.ui.mouse, {
        version: "1.10.3",
        widgetEventPrefix: "drag",
        options: {
            addClasses: !0,
            appendTo: "parent",
            axis: !1,
            connectToSortable: !1,
            containment: !1,
            cursor: "auto",
            cursorAt: !1,
            grid: !1,
            handle: !1,
            helper: "original",
            iframeFix: !1,
            opacity: !1,
            refreshPositions: !1,
            revert: !1,
            revertDuration: 500,
            scope: "default",
            scroll: !0,
            scrollSensitivity: 20,
            scrollSpeed: 20,
            snap: !1,
            snapMode: "both",
            snapTolerance: 20,
            stack: !1,
            zIndex: !1,
            drag: null,
            start: null,
            stop: null
        },
        _create: function() {
            "original" !== this.options.helper || /^(?:r|a|f)/.test(this.element.css("position")) || (this.element[0].style.position = "relative"),
            this.options.addClasses && this.element.addClass("ui-draggable"),
            this.options.disabled && this.element.addClass("ui-draggable-disabled"),
            this._mouseInit()
        },
        _destroy: function() {
            this.element.removeClass("ui-draggable ui-draggable-dragging ui-draggable-disabled"),
            this._mouseDestroy()
        },
        _mouseCapture: function(e) {
            var i = this.options;
            return this.helper || i.disabled || t(e.target).closest(".ui-resizable-handle").length > 0 ? !1 : (this.handle = this._getHandle(e), this.handle ? (t(i.iframeFix === !0 ? "iframe": i.iframeFix).each(function() {
                t("<div class='ui-draggable-iframeFix' style='background: #fff;'></div>").css({
                    width: this.offsetWidth + "px",
                    height: this.offsetHeight + "px",
                    position: "absolute",
                    opacity: "0.001",
                    zIndex: 1e3
                }).css(t(this).offset()).appendTo("body")
            }), !0) : !1)
        },
        _mouseStart: function(e) {
            var i = this.options;
            return this.helper = this._createHelper(e),
            this.helper.addClass("ui-draggable-dragging"),
            this._cacheHelperProportions(),
            t.ui.ddmanager && (t.ui.ddmanager.current = this),
            this._cacheMargins(),
            this.cssPosition = this.helper.css("position"),
            this.scrollParent = this.helper.scrollParent(),
            this.offsetParent = this.helper.offsetParent(),
            this.offsetParentCssPosition = this.offsetParent.css("position"),
            this.offset = this.positionAbs = this.element.offset(),
            this.offset = {
                top: this.offset.top - this.margins.top,
                left: this.offset.left - this.margins.left
            },
            this.offset.scroll = !1,
            t.extend(this.offset, {
                click: {
                    left: e.pageX - this.offset.left,
                    top: e.pageY - this.offset.top
                },
                parent: this._getParentOffset(),
                relative: this._getRelativeOffset()
            }),
            this.originalPosition = this.position = this._generatePosition(e),
            this.originalPageX = e.pageX,
            this.originalPageY = e.pageY,
            i.cursorAt && this._adjustOffsetFromHelper(i.cursorAt),
            this._setContainment(),
            this._trigger("start", e) === !1 ? (this._clear(), !1) : (this._cacheHelperProportions(), t.ui.ddmanager && !i.dropBehaviour && t.ui.ddmanager.prepareOffsets(this, e), this._mouseDrag(e, !0), t.ui.ddmanager && t.ui.ddmanager.dragStart(this, e), !0)
        },
        _mouseDrag: function(e, i) {
            if ("fixed" === this.offsetParentCssPosition && (this.offset.parent = this._getParentOffset()), this.position = this._generatePosition(e), this.positionAbs = this._convertPositionTo("absolute"), !i) {
                var s = this._uiHash();
                if (this._trigger("drag", e, s) === !1) return this._mouseUp({}),
                !1;
                this.position = s.position
            }
            return this.options.axis && "y" === this.options.axis || (this.helper[0].style.left = this.position.left + "px"),
            this.options.axis && "x" === this.options.axis || (this.helper[0].style.top = this.position.top + "px"),
            t.ui.ddmanager && t.ui.ddmanager.drag(this, e),
            !1
        },
        _mouseStop: function(e) {
            var i = this,
            s = !1;
            return t.ui.ddmanager && !this.options.dropBehaviour && (s = t.ui.ddmanager.drop(this, e)),
            this.dropped && (s = this.dropped, this.dropped = !1),
            "original" !== this.options.helper || t.contains(this.element[0].ownerDocument, this.element[0]) ? ("invalid" === this.options.revert && !s || "valid" === this.options.revert && s || this.options.revert === !0 || t.isFunction(this.options.revert) && this.options.revert.call(this.element, s) ? t(this.helper).animate(this.originalPosition, parseInt(this.options.revertDuration, 10),
            function() {
                i._trigger("stop", e) !== !1 && i._clear()
            }) : this._trigger("stop", e) !== !1 && this._clear(), !1) : !1
        },
        _mouseUp: function(e) {
            return t("div.ui-draggable-iframeFix").each(function() {
                this.parentNode.removeChild(this)
            }),
            t.ui.ddmanager && t.ui.ddmanager.dragStop(this, e),
            t.ui.mouse.prototype._mouseUp.call(this, e)
        },
        cancel: function() {
            return this.helper.is(".ui-draggable-dragging") ? this._mouseUp({}) : this._clear(),
            this
        },
        _getHandle: function(e) {
            return this.options.handle ? !!t(e.target).closest(this.element.find(this.options.handle)).length: !0
        },
        _createHelper: function(e) {
            var i = this.options,
            s = t.isFunction(i.helper) ? t(i.helper.apply(this.element[0], [e])) : "clone" === i.helper ? this.element.clone().removeAttr("id") : this.element;
            return s.parents("body").length || s.appendTo("parent" === i.appendTo ? this.element[0].parentNode: i.appendTo),
            s[0] === this.element[0] || /(fixed|absolute)/.test(s.css("position")) || s.css("position", "absolute"),
            s
        },
        _adjustOffsetFromHelper: function(e) {
            "string" == typeof e && (e = e.split(" ")),
            t.isArray(e) && (e = {
                left: +e[0],
                top: +e[1] || 0
            }),
            "left" in e && (this.offset.click.left = e.left + this.margins.left),
            "right" in e && (this.offset.click.left = this.helperProportions.width - e.right + this.margins.left),
            "top" in e && (this.offset.click.top = e.top + this.margins.top),
            "bottom" in e && (this.offset.click.top = this.helperProportions.height - e.bottom + this.margins.top)
        },
        _getParentOffset: function() {
            var e = this.offsetParent.offset();
            return "absolute" === this.cssPosition && this.scrollParent[0] !== document && t.contains(this.scrollParent[0], this.offsetParent[0]) && (e.left += this.scrollParent.scrollLeft(), e.top += this.scrollParent.scrollTop()),
            (this.offsetParent[0] === document.body || this.offsetParent[0].tagName && "html" === this.offsetParent[0].tagName.toLowerCase() && t.ui.ie) && (e = {
                top: 0,
                left: 0
            }),
            {
                top: e.top + (parseInt(this.offsetParent.css("borderTopWidth"), 10) || 0),
                left: e.left + (parseInt(this.offsetParent.css("borderLeftWidth"), 10) || 0)
            }
        },
        _getRelativeOffset: function() {
            if ("relative" === this.cssPosition) {
                var t = this.element.position();
                return {
                    top: t.top - (parseInt(this.helper.css("top"), 10) || 0) + this.scrollParent.scrollTop(),
                    left: t.left - (parseInt(this.helper.css("left"), 10) || 0) + this.scrollParent.scrollLeft()
                }
            }
            return {
                top: 0,
                left: 0
            }
        },
        _cacheMargins: function() {
            this.margins = {
                left: parseInt(this.element.css("marginLeft"), 10) || 0,
                top: parseInt(this.element.css("marginTop"), 10) || 0,
                right: parseInt(this.element.css("marginRight"), 10) || 0,
                bottom: parseInt(this.element.css("marginBottom"), 10) || 0
            }
        },
        _cacheHelperProportions: function() {
            this.helperProportions = {
                width: this.helper.outerWidth(),
                height: this.helper.outerHeight()
            }
        },
        _setContainment: function() {
            var e, i, s, n = this.options;
            return n.containment ? "window" === n.containment ? (this.containment = [t(window).scrollLeft() - this.offset.relative.left - this.offset.parent.left, t(window).scrollTop() - this.offset.relative.top - this.offset.parent.top, t(window).scrollLeft() + t(window).width() - this.helperProportions.width - this.margins.left, t(window).scrollTop() + (t(window).height() || document.body.parentNode.scrollHeight) - this.helperProportions.height - this.margins.top], void 0) : "document" === n.containment ? (this.containment = [0, 0, t(document).width() - this.helperProportions.width - this.margins.left, (t(document).height() || document.body.parentNode.scrollHeight) - this.helperProportions.height - this.margins.top], void 0) : n.containment.constructor === Array ? (this.containment = n.containment, void 0) : ("parent" === n.containment && (n.containment = this.helper[0].parentNode), i = t(n.containment), s = i[0], s && (e = "hidden" !== i.css("overflow"), this.containment = [(parseInt(i.css("borderLeftWidth"), 10) || 0) + (parseInt(i.css("paddingLeft"), 10) || 0), (parseInt(i.css("borderTopWidth"), 10) || 0) + (parseInt(i.css("paddingTop"), 10) || 0), (e ? Math.max(s.scrollWidth, s.offsetWidth) : s.offsetWidth) - (parseInt(i.css("borderRightWidth"), 10) || 0) - (parseInt(i.css("paddingRight"), 10) || 0) - this.helperProportions.width - this.margins.left - this.margins.right, (e ? Math.max(s.scrollHeight, s.offsetHeight) : s.offsetHeight) - (parseInt(i.css("borderBottomWidth"), 10) || 0) - (parseInt(i.css("paddingBottom"), 10) || 0) - this.helperProportions.height - this.margins.top - this.margins.bottom], this.relative_container = i), void 0) : (this.containment = null, void 0)
        },
        _convertPositionTo: function(e, i) {
            i || (i = this.position);
            var s = "absolute" === e ? 1 : -1,
            n = "absolute" !== this.cssPosition || this.scrollParent[0] !== document && t.contains(this.scrollParent[0], this.offsetParent[0]) ? this.scrollParent: this.offsetParent;
            return this.offset.scroll || (this.offset.scroll = {
                top: n.scrollTop(),
                left: n.scrollLeft()
            }),
            {
                top: i.top + this.offset.relative.top * s + this.offset.parent.top * s - ("fixed" === this.cssPosition ? -this.scrollParent.scrollTop() : this.offset.scroll.top) * s,
                left: i.left + this.offset.relative.left * s + this.offset.parent.left * s - ("fixed" === this.cssPosition ? -this.scrollParent.scrollLeft() : this.offset.scroll.left) * s
            }
        },
        _generatePosition: function(e) {
            var i, s, n, a, o = this.options,
            r = "absolute" !== this.cssPosition || this.scrollParent[0] !== document && t.contains(this.scrollParent[0], this.offsetParent[0]) ? this.scrollParent: this.offsetParent,
            l = e.pageX,
            h = e.pageY;
            return this.offset.scroll || (this.offset.scroll = {
                top: r.scrollTop(),
                left: r.scrollLeft()
            }),
            this.originalPosition && (this.containment && (this.relative_container ? (s = this.relative_container.offset(), i = [this.containment[0] + s.left, this.containment[1] + s.top, this.containment[2] + s.left, this.containment[3] + s.top]) : i = this.containment, e.pageX - this.offset.click.left < i[0] && (l = i[0] + this.offset.click.left), e.pageY - this.offset.click.top < i[1] && (h = i[1] + this.offset.click.top), e.pageX - this.offset.click.left > i[2] && (l = i[2] + this.offset.click.left), e.pageY - this.offset.click.top > i[3] && (h = i[3] + this.offset.click.top)), o.grid && (n = o.grid[1] ? this.originalPageY + Math.round((h - this.originalPageY) / o.grid[1]) * o.grid[1] : this.originalPageY, h = i ? n - this.offset.click.top >= i[1] || n - this.offset.click.top > i[3] ? n: n - this.offset.click.top >= i[1] ? n - o.grid[1] : n + o.grid[1] : n, a = o.grid[0] ? this.originalPageX + Math.round((l - this.originalPageX) / o.grid[0]) * o.grid[0] : this.originalPageX, l = i ? a - this.offset.click.left >= i[0] || a - this.offset.click.left > i[2] ? a: a - this.offset.click.left >= i[0] ? a - o.grid[0] : a + o.grid[0] : a)),
            {
                top: h - this.offset.click.top - this.offset.relative.top - this.offset.parent.top + ("fixed" === this.cssPosition ? -this.scrollParent.scrollTop() : this.offset.scroll.top),
                left: l - this.offset.click.left - this.offset.relative.left - this.offset.parent.left + ("fixed" === this.cssPosition ? -this.scrollParent.scrollLeft() : this.offset.scroll.left)
            }
        },
        _clear: function() {
            this.helper.removeClass("ui-draggable-dragging"),
            this.helper[0] === this.element[0] || this.cancelHelperRemoval || this.helper.remove(),
            this.helper = null,
            this.cancelHelperRemoval = !1
        },
        _trigger: function(e, i, s) {
            return s = s || this._uiHash(),
            t.ui.plugin.call(this, e, [i, s]),
            "drag" === e && (this.positionAbs = this._convertPositionTo("absolute")),
            t.Widget.prototype._trigger.call(this, e, i, s)
        },
        plugins: {},
        _uiHash: function() {
            return {
                helper: this.helper,
                position: this.position,
                originalPosition: this.originalPosition,
                offset: this.positionAbs
            }
        }
    }),
    t.ui.plugin.add("draggable", "connectToSortable", {
        start: function(e, i) {
            var s = t(this).data("ui-draggable"),
            n = s.options,
            a = t.extend({},
            i, {
                item: s.element
            });
            s.sortables = [],
            t(n.connectToSortable).each(function() {
                var i = t.data(this, "ui-sortable");
                i && !i.options.disabled && (s.sortables.push({
                    instance: i,
                    shouldRevert: i.options.revert
                }), i.refreshPositions(), i._trigger("activate", e, a))
            })
        },
        stop: function(e, i) {
            var s = t(this).data("ui-draggable"),
            n = t.extend({},
            i, {
                item: s.element
            });
            t.each(s.sortables,
            function() {
                this.instance.isOver ? (this.instance.isOver = 0, s.cancelHelperRemoval = !0, this.instance.cancelHelperRemoval = !1, this.shouldRevert && (this.instance.options.revert = this.shouldRevert), this.instance._mouseStop(e), this.instance.options.helper = this.instance.options._helper, "original" === s.options.helper && this.instance.currentItem.css({
                    top: "auto",
                    left: "auto"
                })) : (this.instance.cancelHelperRemoval = !1, this.instance._trigger("deactivate", e, n))
            })
        },
        drag: function(e, i) {
            var s = t(this).data("ui-draggable"),
            n = this;
            t.each(s.sortables,
            function() {
                var a = !1,
                o = this;
                this.instance.positionAbs = s.positionAbs,
                this.instance.helperProportions = s.helperProportions,
                this.instance.offset.click = s.offset.click,
                this.instance._intersectsWith(this.instance.containerCache) && (a = !0, t.each(s.sortables,
                function() {
                    return this.instance.positionAbs = s.positionAbs,
                    this.instance.helperProportions = s.helperProportions,
                    this.instance.offset.click = s.offset.click,
                    this !== o && this.instance._intersectsWith(this.instance.containerCache) && t.contains(o.instance.element[0], this.instance.element[0]) && (a = !1),
                    a
                })),
                a ? (this.instance.isOver || (this.instance.isOver = 1, this.instance.currentItem = t(n).clone().removeAttr("id").appendTo(this.instance.element).data("ui-sortable-item", !0), this.instance.options._helper = this.instance.options.helper, this.instance.options.helper = function() {
                    return i.helper[0]
                },
                e.target = this.instance.currentItem[0], this.instance._mouseCapture(e, !0), this.instance._mouseStart(e, !0, !0), this.instance.offset.click.top = s.offset.click.top, this.instance.offset.click.left = s.offset.click.left, this.instance.offset.parent.left -= s.offset.parent.left - this.instance.offset.parent.left, this.instance.offset.parent.top -= s.offset.parent.top - this.instance.offset.parent.top, s._trigger("toSortable", e), s.dropped = this.instance.element, s.currentItem = s.element, this.instance.fromOutside = s), this.instance.currentItem && this.instance._mouseDrag(e)) : this.instance.isOver && (this.instance.isOver = 0, this.instance.cancelHelperRemoval = !0, this.instance.options.revert = !1, this.instance._trigger("out", e, this.instance._uiHash(this.instance)), this.instance._mouseStop(e, !0), this.instance.options.helper = this.instance.options._helper, this.instance.currentItem.remove(), this.instance.placeholder && this.instance.placeholder.remove(), s._trigger("fromSortable", e), s.dropped = !1)
            })
        }
    }),
    t.ui.plugin.add("draggable", "cursor", {
        start: function() {
            var e = t("body"),
            i = t(this).data("ui-draggable").options;
            e.css("cursor") && (i._cursor = e.css("cursor")),
            e.css("cursor", i.cursor)
        },
        stop: function() {
            var e = t(this).data("ui-draggable").options;
            e._cursor && t("body").css("cursor", e._cursor)
        }
    }),
    t.ui.plugin.add("draggable", "opacity", {
        start: function(e, i) {
            var s = t(i.helper),
            n = t(this).data("ui-draggable").options;
            s.css("opacity") && (n._opacity = s.css("opacity")),
            s.css("opacity", n.opacity)
        },
        stop: function(e, i) {
            var s = t(this).data("ui-draggable").options;
            s._opacity && t(i.helper).css("opacity", s._opacity)
        }
    }),
    t.ui.plugin.add("draggable", "scroll", {
        start: function() {
            var e = t(this).data("ui-draggable");
            e.scrollParent[0] !== document && "HTML" !== e.scrollParent[0].tagName && (e.overflowOffset = e.scrollParent.offset())
        },
        drag: function(e) {
            var i = t(this).data("ui-draggable"),
            s = i.options,
            n = !1;
            i.scrollParent[0] !== document && "HTML" !== i.scrollParent[0].tagName ? (s.axis && "x" === s.axis || (i.overflowOffset.top + i.scrollParent[0].offsetHeight - e.pageY < s.scrollSensitivity ? i.scrollParent[0].scrollTop = n = i.scrollParent[0].scrollTop + s.scrollSpeed: e.pageY - i.overflowOffset.top < s.scrollSensitivity && (i.scrollParent[0].scrollTop = n = i.scrollParent[0].scrollTop - s.scrollSpeed)), s.axis && "y" === s.axis || (i.overflowOffset.left + i.scrollParent[0].offsetWidth - e.pageX < s.scrollSensitivity ? i.scrollParent[0].scrollLeft = n = i.scrollParent[0].scrollLeft + s.scrollSpeed: e.pageX - i.overflowOffset.left < s.scrollSensitivity && (i.scrollParent[0].scrollLeft = n = i.scrollParent[0].scrollLeft - s.scrollSpeed))) : (s.axis && "x" === s.axis || (e.pageY - t(document).scrollTop() < s.scrollSensitivity ? n = t(document).scrollTop(t(document).scrollTop() - s.scrollSpeed) : t(window).height() - (e.pageY - t(document).scrollTop()) < s.scrollSensitivity && (n = t(document).scrollTop(t(document).scrollTop() + s.scrollSpeed))), s.axis && "y" === s.axis || (e.pageX - t(document).scrollLeft() < s.scrollSensitivity ? n = t(document).scrollLeft(t(document).scrollLeft() - s.scrollSpeed) : t(window).width() - (e.pageX - t(document).scrollLeft()) < s.scrollSensitivity && (n = t(document).scrollLeft(t(document).scrollLeft() + s.scrollSpeed)))),
            n !== !1 && t.ui.ddmanager && !s.dropBehaviour && t.ui.ddmanager.prepareOffsets(i, e)
        }
    }),
    t.ui.plugin.add("draggable", "snap", {
        start: function() {
            var e = t(this).data("ui-draggable"),
            i = e.options;
            e.snapElements = [],
            t(i.snap.constructor !== String ? i.snap.items || ":data(ui-draggable)": i.snap).each(function() {
                var i = t(this),
                s = i.offset();
                this !== e.element[0] && e.snapElements.push({
                    item: this,
                    width: i.outerWidth(),
                    height: i.outerHeight(),
                    top: s.top,
                    left: s.left
                })
            })
        },
        drag: function(e, i) {
            var s, n, a, o, r, l, h, c, u, d, p = t(this).data("ui-draggable"),
            g = p.options,
            f = g.snapTolerance,
            m = i.offset.left,
            _ = m + p.helperProportions.width,
            v = i.offset.top,
            b = v + p.helperProportions.height;
            for (u = p.snapElements.length - 1; u >= 0; u--) r = p.snapElements[u].left,
            l = r + p.snapElements[u].width,
            h = p.snapElements[u].top,
            c = h + p.snapElements[u].height,
            r - f > _ || m > l + f || h - f > b || v > c + f || !t.contains(p.snapElements[u].item.ownerDocument, p.snapElements[u].item) ? (p.snapElements[u].snapping && p.options.snap.release && p.options.snap.release.call(p.element, e, t.extend(p._uiHash(), {
                snapItem: p.snapElements[u].item
            })), p.snapElements[u].snapping = !1) : ("inner" !== g.snapMode && (s = f >= Math.abs(h - b), n = f >= Math.abs(c - v), a = f >= Math.abs(r - _), o = f >= Math.abs(l - m), s && (i.position.top = p._convertPositionTo("relative", {
                top: h - p.helperProportions.height,
                left: 0
            }).top - p.margins.top), n && (i.position.top = p._convertPositionTo("relative", {
                top: c,
                left: 0
            }).top - p.margins.top), a && (i.position.left = p._convertPositionTo("relative", {
                top: 0,
                left: r - p.helperProportions.width
            }).left - p.margins.left), o && (i.position.left = p._convertPositionTo("relative", {
                top: 0,
                left: l
            }).left - p.margins.left)), d = s || n || a || o, "outer" !== g.snapMode && (s = f >= Math.abs(h - v), n = f >= Math.abs(c - b), a = f >= Math.abs(r - m), o = f >= Math.abs(l - _), s && (i.position.top = p._convertPositionTo("relative", {
                top: h,
                left: 0
            }).top - p.margins.top), n && (i.position.top = p._convertPositionTo("relative", {
                top: c - p.helperProportions.height,
                left: 0
            }).top - p.margins.top), a && (i.position.left = p._convertPositionTo("relative", {
                top: 0,
                left: r
            }).left - p.margins.left), o && (i.position.left = p._convertPositionTo("relative", {
                top: 0,
                left: l - p.helperProportions.width
            }).left - p.margins.left)), !p.snapElements[u].snapping && (s || n || a || o || d) && p.options.snap.snap && p.options.snap.snap.call(p.element, e, t.extend(p._uiHash(), {
                snapItem: p.snapElements[u].item
            })), p.snapElements[u].snapping = s || n || a || o || d)
        }
    }),
    t.ui.plugin.add("draggable", "stack", {
        start: function() {
            var e, i = this.data("ui-draggable").options,
            s = t.makeArray(t(i.stack)).sort(function(e, i) {
                return (parseInt(t(e).css("zIndex"), 10) || 0) - (parseInt(t(i).css("zIndex"), 10) || 0)
            });
            s.length && (e = parseInt(t(s[0]).css("zIndex"), 10) || 0, t(s).each(function(i) {
                t(this).css("zIndex", e + i)
            }), this.css("zIndex", e + s.length))
        }
    }),
    t.ui.plugin.add("draggable", "zIndex", {
        start: function(e, i) {
            var s = t(i.helper),
            n = t(this).data("ui-draggable").options;
            s.css("zIndex") && (n._zIndex = s.css("zIndex")),
            s.css("zIndex", n.zIndex)
        },
        stop: function(e, i) {
            var s = t(this).data("ui-draggable").options;
            s._zIndex && t(i.helper).css("zIndex", s._zIndex)
        }
    })
} (jQuery),
function(t) {
    function e(t, e, i) {
        return t > e && e + i > t
    }
    t.widget("ui.droppable", {
        version: "1.10.3",
        widgetEventPrefix: "drop",
        options: {
            accept: "*",
            activeClass: !1,
            addClasses: !0,
            greedy: !1,
            hoverClass: !1,
            scope: "default",
            tolerance: "intersect",
            activate: null,
            deactivate: null,
            drop: null,
            out: null,
            over: null
        },
        _create: function() {
            var e = this.options,
            i = e.accept;
            this.isover = !1,
            this.isout = !0,
            this.accept = t.isFunction(i) ? i: function(t) {
                return t.is(i)
            },
            this.proportions = {
                width: this.element[0].offsetWidth,
                height: this.element[0].offsetHeight
            },
            t.ui.ddmanager.droppables[e.scope] = t.ui.ddmanager.droppables[e.scope] || [],
            t.ui.ddmanager.droppables[e.scope].push(this),
            e.addClasses && this.element.addClass("ui-droppable")
        },
        _destroy: function() {
            for (var e = 0,
            i = t.ui.ddmanager.droppables[this.options.scope]; i.length > e; e++) i[e] === this && i.splice(e, 1);
            this.element.removeClass("ui-droppable ui-droppable-disabled")
        },
        _setOption: function(e, i) {
            "accept" === e && (this.accept = t.isFunction(i) ? i: function(t) {
                return t.is(i)
            }),
            t.Widget.prototype._setOption.apply(this, arguments)
        },
        _activate: function(e) {
            var i = t.ui.ddmanager.current;
            this.options.activeClass && this.element.addClass(this.options.activeClass),
            i && this._trigger("activate", e, this.ui(i))
        },
        _deactivate: function(e) {
            var i = t.ui.ddmanager.current;
            this.options.activeClass && this.element.removeClass(this.options.activeClass),
            i && this._trigger("deactivate", e, this.ui(i))
        },
        _over: function(e) {
            var i = t.ui.ddmanager.current;
            i && (i.currentItem || i.element)[0] !== this.element[0] && this.accept.call(this.element[0], i.currentItem || i.element) && (this.options.hoverClass && this.element.addClass(this.options.hoverClass), this._trigger("over", e, this.ui(i)))
        },
        _out: function(e) {
            var i = t.ui.ddmanager.current;
            i && (i.currentItem || i.element)[0] !== this.element[0] && this.accept.call(this.element[0], i.currentItem || i.element) && (this.options.hoverClass && this.element.removeClass(this.options.hoverClass), this._trigger("out", e, this.ui(i)))
        },
        _drop: function(e, i) {
            var s = i || t.ui.ddmanager.current,
            n = !1;
            return s && (s.currentItem || s.element)[0] !== this.element[0] ? (this.element.find(":data(ui-droppable)").not(".ui-draggable-dragging").each(function() {
                var e = t.data(this, "ui-droppable");
                return e.options.greedy && !e.options.disabled && e.options.scope === s.options.scope && e.accept.call(e.element[0], s.currentItem || s.element) && t.ui.intersect(s, t.extend(e, {
                    offset: e.element.offset()
                }), e.options.tolerance) ? (n = !0, !1) : void 0
            }), n ? !1 : this.accept.call(this.element[0], s.currentItem || s.element) ? (this.options.activeClass && this.element.removeClass(this.options.activeClass), this.options.hoverClass && this.element.removeClass(this.options.hoverClass), this._trigger("drop", e, this.ui(s)), this.element) : !1) : !1
        },
        ui: function(t) {
            return {
                draggable: t.currentItem || t.element,
                helper: t.helper,
                position: t.position,
                offset: t.positionAbs
            }
        }
    }),
    t.ui.intersect = function(t, i, s) {
        if (!i.offset) return ! 1;
        var n, a, o = (t.positionAbs || t.position.absolute).left,
        r = o + t.helperProportions.width,
        l = (t.positionAbs || t.position.absolute).top,
        h = l + t.helperProportions.height,
        c = i.offset.left,
        u = c + i.proportions.width,
        d = i.offset.top,
        p = d + i.proportions.height;
        switch (s) {
        case "fit":
            return o >= c && u >= r && l >= d && p >= h;
        case "intersect":
            return o + t.helperProportions.width / 2 > c && u > r - t.helperProportions.width / 2 && l + t.helperProportions.height / 2 > d && p > h - t.helperProportions.height / 2;
        case "pointer":
            return n = (t.positionAbs || t.position.absolute).left + (t.clickOffset || t.offset.click).left,
            a = (t.positionAbs || t.position.absolute).top + (t.clickOffset || t.offset.click).top,
            e(a, d, i.proportions.height) && e(n, c, i.proportions.width);
        case "touch":
            return (l >= d && p >= l || h >= d && p >= h || d > l && h > p) && (o >= c && u >= o || r >= c && u >= r || c > o && r > u);
        default:
            return ! 1
        }
    },
    t.ui.ddmanager = {
        current: null,
        droppables: {
            "default": []
        },
        prepareOffsets: function(e, i) {
            var s, n, a = t.ui.ddmanager.droppables[e.options.scope] || [],
            o = i ? i.type: null,
            r = (e.currentItem || e.element).find(":data(ui-droppable)").addBack();
            t: for (s = 0; a.length > s; s++) if (! (a[s].options.disabled || e && !a[s].accept.call(a[s].element[0], e.currentItem || e.element))) {
                for (n = 0; r.length > n; n++) if (r[n] === a[s].element[0]) {
                    a[s].proportions.height = 0;
                    continue t
                }
                a[s].visible = "none" !== a[s].element.css("display"),
                a[s].visible && ("mousedown" === o && a[s]._activate.call(a[s], i), a[s].offset = a[s].element.offset(), a[s].proportions = {
                    width: a[s].element[0].offsetWidth,
                    height: a[s].element[0].offsetHeight
                })
            }
        },
        drop: function(e, i) {
            var s = !1;
            return t.each((t.ui.ddmanager.droppables[e.options.scope] || []).slice(),
            function() {
                this.options && (!this.options.disabled && this.visible && t.ui.intersect(e, this, this.options.tolerance) && (s = this._drop.call(this, i) || s), !this.options.disabled && this.visible && this.accept.call(this.element[0], e.currentItem || e.element) && (this.isout = !0, this.isover = !1, this._deactivate.call(this, i)))
            }),
            s
        },
        dragStart: function(e, i) {
            e.element.parentsUntil("body").bind("scroll.droppable",
            function() {
                e.options.refreshPositions || t.ui.ddmanager.prepareOffsets(e, i)
            })
        },
        drag: function(e, i) {
            e.options.refreshPositions && t.ui.ddmanager.prepareOffsets(e, i),
            t.each(t.ui.ddmanager.droppables[e.options.scope] || [],
            function() {
                if (!this.options.disabled && !this.greedyChild && this.visible) {
                    var s, n, a, o = t.ui.intersect(e, this, this.options.tolerance),
                    r = !o && this.isover ? "isout": o && !this.isover ? "isover": null;
                    r && (this.options.greedy && (n = this.options.scope, a = this.element.parents(":data(ui-droppable)").filter(function() {
                        return t.data(this, "ui-droppable").options.scope === n
                    }), a.length && (s = t.data(a[0], "ui-droppable"), s.greedyChild = "isover" === r)), s && "isover" === r && (s.isover = !1, s.isout = !0, s._out.call(s, i)), this[r] = !0, this["isout" === r ? "isover": "isout"] = !1, this["isover" === r ? "_over": "_out"].call(this, i), s && "isout" === r && (s.isout = !1, s.isover = !0, s._over.call(s, i)))
                }
            })
        },
        dragStop: function(e, i) {
            e.element.parentsUntil("body").unbind("scroll.droppable"),
            e.options.refreshPositions || t.ui.ddmanager.prepareOffsets(e, i)
        }
    }
} (jQuery),
function(t) {
    function e(t) {
        return parseInt(t, 10) || 0
    }
    function i(t) {
        return ! isNaN(parseInt(t, 10))
    }
    t.widget("ui.resizable", t.ui.mouse, {
        version: "1.10.3",
        widgetEventPrefix: "resize",
        options: {
            alsoResize: !1,
            animate: !1,
            animateDuration: "slow",
            animateEasing: "swing",
            aspectRatio: !1,
            autoHide: !1,
            containment: !1,
            ghost: !1,
            grid: !1,
            handles: "e,s,se",
            helper: !1,
            maxHeight: null,
            maxWidth: null,
            minHeight: 10,
            minWidth: 10,
            zIndex: 90,
            resize: null,
            start: null,
            stop: null
        },
        _create: function() {
            var e, i, s, n, a, o = this,
            r = this.options;
            if (this.element.addClass("ui-resizable"), t.extend(this, {
                _aspectRatio: !!r.aspectRatio,
                aspectRatio: r.aspectRatio,
                originalElement: this.element,
                _proportionallyResizeElements: [],
                _helper: r.helper || r.ghost || r.animate ? r.helper || "ui-resizable-helper": null
            }), this.element[0].nodeName.match(/canvas|textarea|input|select|button|img/i) && (this.element.wrap(t("<div class='ui-wrapper' style='overflow: hidden;'></div>").css({
                position: this.element.css("position"),
                width: this.element.outerWidth(),
                height: this.element.outerHeight(),
                top: this.element.css("top"),
                left: this.element.css("left")
            })), this.element = this.element.parent().data("ui-resizable", this.element.data("ui-resizable")), this.elementIsWrapper = !0, this.element.css({
                marginLeft: this.originalElement.css("marginLeft"),
                marginTop: this.originalElement.css("marginTop"),
                marginRight: this.originalElement.css("marginRight"),
                marginBottom: this.originalElement.css("marginBottom")
            }), this.originalElement.css({
                marginLeft: 0,
                marginTop: 0,
                marginRight: 0,
                marginBottom: 0
            }), this.originalResizeStyle = this.originalElement.css("resize"), this.originalElement.css("resize", "none"), this._proportionallyResizeElements.push(this.originalElement.css({
                position: "static",
                zoom: 1,
                display: "block"
            })), this.originalElement.css({
                margin: this.originalElement.css("margin")
            }), this._proportionallyResize()), this.handles = r.handles || (t(".ui-resizable-handle", this.element).length ? {
                n: ".ui-resizable-n",
                e: ".ui-resizable-e",
                s: ".ui-resizable-s",
                w: ".ui-resizable-w",
                se: ".ui-resizable-se",
                sw: ".ui-resizable-sw",
                ne: ".ui-resizable-ne",
                nw: ".ui-resizable-nw"
            }: "e,s,se"), this.handles.constructor === String) for ("all" === this.handles && (this.handles = "n,e,s,w,se,sw,ne,nw"), e = this.handles.split(","), this.handles = {},
            i = 0; e.length > i; i++) s = t.trim(e[i]),
            a = "ui-resizable-" + s,
            n = t("<div class='ui-resizable-handle " + a + "'></div>"),
            n.css({
                zIndex: r.zIndex
            }),
            "se" === s && n.addClass("ui-icon ui-icon-gripsmall-diagonal-se"),
            this.handles[s] = ".ui-resizable-" + s,
            this.element.append(n);
            this._renderAxis = function(e) {
                var i, s, n, a;
                e = e || this.element;
                for (i in this.handles) this.handles[i].constructor === String && (this.handles[i] = t(this.handles[i], this.element).show()),
                this.elementIsWrapper && this.originalElement[0].nodeName.match(/textarea|input|select|button/i) && (s = t(this.handles[i], this.element), a = /sw|ne|nw|se|n|s/.test(i) ? s.outerHeight() : s.outerWidth(), n = ["padding", /ne|nw|n/.test(i) ? "Top": /se|sw|s/.test(i) ? "Bottom": /^e$/.test(i) ? "Right": "Left"].join(""), e.css(n, a), this._proportionallyResize()),
                t(this.handles[i]).length
            },
            this._renderAxis(this.element),
            this._handles = t(".ui-resizable-handle", this.element).disableSelection(),
            this._handles.mouseover(function() {
                o.resizing || (this.className && (n = this.className.match(/ui-resizable-(se|sw|ne|nw|n|e|s|w)/i)), o.axis = n && n[1] ? n[1] : "se")
            }),
            r.autoHide && (this._handles.hide(), t(this.element).addClass("ui-resizable-autohide").mouseenter(function() {
                r.disabled || (t(this).removeClass("ui-resizable-autohide"), o._handles.show())
            }).mouseleave(function() {
                r.disabled || o.resizing || (t(this).addClass("ui-resizable-autohide"), o._handles.hide())
            })),
            this._mouseInit()
        },
        _destroy: function() {
            this._mouseDestroy();
            var e, i = function(e) {
                t(e).removeClass("ui-resizable ui-resizable-disabled ui-resizable-resizing").removeData("resizable").removeData("ui-resizable").unbind(".resizable").find(".ui-resizable-handle").remove()
            };
            return this.elementIsWrapper && (i(this.element), e = this.element, this.originalElement.css({
                position: e.css("position"),
                width: e.outerWidth(),
                height: e.outerHeight(),
                top: e.css("top"),
                left: e.css("left")
            }).insertAfter(e), e.remove()),
            this.originalElement.css("resize", this.originalResizeStyle),
            i(this.originalElement),
            this
        },
        _mouseCapture: function(e) {
            var i, s, n = !1;
            for (i in this.handles) s = t(this.handles[i])[0],
            (s === e.target || t.contains(s, e.target)) && (n = !0);
            return ! this.options.disabled && n
        },
        _mouseStart: function(i) {
            var s, n, a, o = this.options,
            r = this.element.position(),
            h = this.element;
            return this.resizing = !0,
            /absolute/.test(h.css("position")) ? h.css({
                position: "absolute",
                top: h.css("top"),
                left: h.css("left")
            }) : h.is(".ui-draggable") && h.css({
                position: "absolute",
                top: r.top,
                left: r.left
            }),
            this._renderProxy(),
            s = e(this.helper.css("left")),
            n = e(this.helper.css("top")),
            o.containment && (s += t(o.containment).scrollLeft() || 0, n += t(o.containment).scrollTop() || 0),
            this.offset = this.helper.offset(),
            this.position = {
                left: s,
                top: n
            },
            this.size = this._helper ? {
                width: h.outerWidth(),
                height: h.outerHeight()
            }: {
                width: h.width(),
                height: h.height()
            },
            this.originalSize = this._helper ? {
                width: h.outerWidth(),
                height: h.outerHeight()
            }: {
                width: h.width(),
                height: h.height()
            },
            this.originalPosition = {
                left: s,
                top: n
            },
            this.sizeDiff = {
                width: h.outerWidth() - h.width(),
                height: h.outerHeight() - h.height()
            },
            this.originalMousePosition = {
                left: i.pageX,
                top: i.pageY
            },
            this.aspectRatio = "number" == typeof o.aspectRatio ? o.aspectRatio: this.originalSize.width / this.originalSize.height || 1,
            a = t(".ui-resizable-" + this.axis).css("cursor"),
            t("body").css("cursor", "auto" === a ? this.axis + "-resize": a),
            h.addClass("ui-resizable-resizing"),
            this._propagate("start", i),
            !0
        },
        _mouseDrag: function(e) {
            var i, s = this.helper,
            n = {},
            a = this.originalMousePosition,
            o = this.axis,
            r = this.position.top,
            h = this.position.left,
            l = this.size.width,
            c = this.size.height,
            u = e.pageX - a.left || 0,
            d = e.pageY - a.top || 0,
            p = this._change[o];
            return p ? (i = p.apply(this, [e, u, d]), this._updateVirtualBoundaries(e.shiftKey), (this._aspectRatio || e.shiftKey) && (i = this._updateRatio(i, e)), i = this._respectSize(i, e), this._updateCache(i), this._propagate("resize", e), this.position.top !== r && (n.top = this.position.top + "px"), this.position.left !== h && (n.left = this.position.left + "px"), this.size.width !== l && (n.width = this.size.width + "px"), this.size.height !== c && (n.height = this.size.height + "px"), s.css(n), !this._helper && this._proportionallyResizeElements.length && this._proportionallyResize(), t.isEmptyObject(n) || this._trigger("resize", e, this.ui()), !1) : !1
        },
        _mouseStop: function(e) {
            this.resizing = !1;
            var i, s, n, a, o, r, h, l = this.options,
            c = this;
            return this._helper && (i = this._proportionallyResizeElements, s = i.length && /textarea/i.test(i[0].nodeName), n = s && t.ui.hasScroll(i[0], "left") ? 0 : c.sizeDiff.height, a = s ? 0 : c.sizeDiff.width, o = {
                width: c.helper.width() - a,
                height: c.helper.height() - n
            },
            r = parseInt(c.element.css("left"), 10) + (c.position.left - c.originalPosition.left) || null, h = parseInt(c.element.css("top"), 10) + (c.position.top - c.originalPosition.top) || null, l.animate || this.element.css(t.extend(o, {
                top: h,
                left: r
            })), c.helper.height(c.size.height), c.helper.width(c.size.width), this._helper && !l.animate && this._proportionallyResize()),
            t("body").css("cursor", "auto"),
            this.element.removeClass("ui-resizable-resizing"),
            this._propagate("stop", e),
            this._helper && this.helper.remove(),
            !1
        },
        _updateVirtualBoundaries: function(t) {
            var e, s, n, a, o, r = this.options;
            o = {
                minWidth: i(r.minWidth) ? r.minWidth: 0,
                maxWidth: i(r.maxWidth) ? r.maxWidth: 1 / 0,
                minHeight: i(r.minHeight) ? r.minHeight: 0,
                maxHeight: i(r.maxHeight) ? r.maxHeight: 1 / 0
            },
            (this._aspectRatio || t) && (e = o.minHeight * this.aspectRatio, n = o.minWidth / this.aspectRatio, s = o.maxHeight * this.aspectRatio, a = o.maxWidth / this.aspectRatio, e > o.minWidth && (o.minWidth = e), n > o.minHeight && (o.minHeight = n), o.maxWidth > s && (o.maxWidth = s), o.maxHeight > a && (o.maxHeight = a)),
            this._vBoundaries = o
        },
        _updateCache: function(t) {
            this.offset = this.helper.offset(),
            i(t.left) && (this.position.left = t.left),
            i(t.top) && (this.position.top = t.top),
            i(t.height) && (this.size.height = t.height),
            i(t.width) && (this.size.width = t.width)
        },
        _updateRatio: function(t) {
            var e = this.position,
            s = this.size,
            n = this.axis;
            return i(t.height) ? t.width = t.height * this.aspectRatio: i(t.width) && (t.height = t.width / this.aspectRatio),
            "sw" === n && (t.left = e.left + (s.width - t.width), t.top = null),
            "nw" === n && (t.top = e.top + (s.height - t.height), t.left = e.left + (s.width - t.width)),
            t
        },
        _respectSize: function(t) {
            var e = this._vBoundaries,
            s = this.axis,
            n = i(t.width) && e.maxWidth && e.maxWidth < t.width,
            a = i(t.height) && e.maxHeight && e.maxHeight < t.height,
            o = i(t.width) && e.minWidth && e.minWidth > t.width,
            r = i(t.height) && e.minHeight && e.minHeight > t.height,
            h = this.originalPosition.left + this.originalSize.width,
            l = this.position.top + this.size.height,
            c = /sw|nw|w/.test(s),
            u = /nw|ne|n/.test(s);
            return o && (t.width = e.minWidth),
            r && (t.height = e.minHeight),
            n && (t.width = e.maxWidth),
            a && (t.height = e.maxHeight),
            o && c && (t.left = h - e.minWidth),
            n && c && (t.left = h - e.maxWidth),
            r && u && (t.top = l - e.minHeight),
            a && u && (t.top = l - e.maxHeight),
            t.width || t.height || t.left || !t.top ? t.width || t.height || t.top || !t.left || (t.left = null) : t.top = null,
            t
        },
        _proportionallyResize: function() {
            if (this._proportionallyResizeElements.length) {
                var t, e, i, s, n, a = this.helper || this.element;
                for (t = 0; this._proportionallyResizeElements.length > t; t++) {
                    if (n = this._proportionallyResizeElements[t], !this.borderDif) for (this.borderDif = [], i = [n.css("borderTopWidth"), n.css("borderRightWidth"), n.css("borderBottomWidth"), n.css("borderLeftWidth")], s = [n.css("paddingTop"), n.css("paddingRight"), n.css("paddingBottom"), n.css("paddingLeft")], e = 0; i.length > e; e++) this.borderDif[e] = (parseInt(i[e], 10) || 0) + (parseInt(s[e], 10) || 0);
                    n.css({
                        height: a.height() - this.borderDif[0] - this.borderDif[2] || 0,
                        width: a.width() - this.borderDif[1] - this.borderDif[3] || 0
                    })
                }
            }
        },
        _renderProxy: function() {
            var e = this.element,
            i = this.options;
            this.elementOffset = e.offset(),
            this._helper ? (this.helper = this.helper || t("<div style='overflow:hidden;'></div>"), this.helper.addClass(this._helper).css({
                width: this.element.outerWidth() - 1,
                height: this.element.outerHeight() - 1,
                position: "absolute",
                left: this.elementOffset.left + "px",
                top: this.elementOffset.top + "px",
                zIndex: ++i.zIndex
            }), this.helper.appendTo("body").disableSelection()) : this.helper = this.element
        },
        _change: {
            e: function(t, e) {
                return {
                    width: this.originalSize.width + e
                }
            },
            w: function(t, e) {
                var i = this.originalSize,
                s = this.originalPosition;
                return {
                    left: s.left + e,
                    width: i.width - e
                }
            },
            n: function(t, e, i) {
                var s = this.originalSize,
                n = this.originalPosition;
                return {
                    top: n.top + i,
                    height: s.height - i
                }
            },
            s: function(t, e, i) {
                return {
                    height: this.originalSize.height + i
                }
            },
            se: function(e, i, s) {
                return t.extend(this._change.s.apply(this, arguments), this._change.e.apply(this, [e, i, s]))
            },
            sw: function(e, i, s) {
                return t.extend(this._change.s.apply(this, arguments), this._change.w.apply(this, [e, i, s]))
            },
            ne: function(e, i, s) {
                return t.extend(this._change.n.apply(this, arguments), this._change.e.apply(this, [e, i, s]))
            },
            nw: function(e, i, s) {
                return t.extend(this._change.n.apply(this, arguments), this._change.w.apply(this, [e, i, s]))
            }
        },
        _propagate: function(e, i) {
            t.ui.plugin.call(this, e, [i, this.ui()]),
            "resize" !== e && this._trigger(e, i, this.ui())
        },
        plugins: {},
        ui: function() {
            return {
                originalElement: this.originalElement,
                element: this.element,
                helper: this.helper,
                position: this.position,
                size: this.size,
                originalSize: this.originalSize,
                originalPosition: this.originalPosition
            }
        }
    }),
    t.ui.plugin.add("resizable", "animate", {
        stop: function(e) {
            var i = t(this).data("ui-resizable"),
            s = i.options,
            n = i._proportionallyResizeElements,
            a = n.length && /textarea/i.test(n[0].nodeName),
            o = a && t.ui.hasScroll(n[0], "left") ? 0 : i.sizeDiff.height,
            r = a ? 0 : i.sizeDiff.width,
            h = {
                width: i.size.width - r,
                height: i.size.height - o
            },
            l = parseInt(i.element.css("left"), 10) + (i.position.left - i.originalPosition.left) || null,
            c = parseInt(i.element.css("top"), 10) + (i.position.top - i.originalPosition.top) || null;
            i.element.animate(t.extend(h, c && l ? {
                top: c,
                left: l
            }: {}), {
                duration: s.animateDuration,
                easing: s.animateEasing,
                step: function() {
                    var s = {
                        width: parseInt(i.element.css("width"), 10),
                        height: parseInt(i.element.css("height"), 10),
                        top: parseInt(i.element.css("top"), 10),
                        left: parseInt(i.element.css("left"), 10)
                    };
                    n && n.length && t(n[0]).css({
                        width: s.width,
                        height: s.height
                    }),
                    i._updateCache(s),
                    i._propagate("resize", e)
                }
            })
        }
    }),
    t.ui.plugin.add("resizable", "containment", {
        start: function() {
            var i, s, n, a, o, r, h, l = t(this).data("ui-resizable"),
            c = l.options,
            u = l.element,
            d = c.containment,
            p = d instanceof t ? d.get(0) : /parent/.test(d) ? u.parent().get(0) : d;
            p && (l.containerElement = t(p), /document/.test(d) || d === document ? (l.containerOffset = {
                left: 0,
                top: 0
            },
            l.containerPosition = {
                left: 0,
                top: 0
            },
            l.parentData = {
                element: t(document),
                left: 0,
                top: 0,
                width: t(document).width(),
                height: t(document).height() || document.body.parentNode.scrollHeight
            }) : (i = t(p), s = [], t(["Top", "Right", "Left", "Bottom"]).each(function(t, n) {
                s[t] = e(i.css("padding" + n))
            }), l.containerOffset = i.offset(), l.containerPosition = i.position(), l.containerSize = {
                height: i.innerHeight() - s[3],
                width: i.innerWidth() - s[1]
            },
            n = l.containerOffset, a = l.containerSize.height, o = l.containerSize.width, r = t.ui.hasScroll(p, "left") ? p.scrollWidth: o, h = t.ui.hasScroll(p) ? p.scrollHeight: a, l.parentData = {
                element: p,
                left: n.left,
                top: n.top,
                width: r,
                height: h
            }))
        },
        resize: function(e) {
            var i, s, n, a, o = t(this).data("ui-resizable"),
            r = o.options,
            h = o.containerOffset,
            l = o.position,
            c = o._aspectRatio || e.shiftKey,
            u = {
                top: 0,
                left: 0
            },
            d = o.containerElement;
            d[0] !== document && /static/.test(d.css("position")) && (u = h),
            l.left < (o._helper ? h.left: 0) && (o.size.width = o.size.width + (o._helper ? o.position.left - h.left: o.position.left - u.left), c && (o.size.height = o.size.width / o.aspectRatio), o.position.left = r.helper ? h.left: 0),
            l.top < (o._helper ? h.top: 0) && (o.size.height = o.size.height + (o._helper ? o.position.top - h.top: o.position.top), c && (o.size.width = o.size.height * o.aspectRatio), o.position.top = o._helper ? h.top: 0),
            o.offset.left = o.parentData.left + o.position.left,
            o.offset.top = o.parentData.top + o.position.top,
            i = Math.abs((o._helper ? o.offset.left - u.left: o.offset.left - u.left) + o.sizeDiff.width),
            s = Math.abs((o._helper ? o.offset.top - u.top: o.offset.top - h.top) + o.sizeDiff.height),
            n = o.containerElement.get(0) === o.element.parent().get(0),
            a = /relative|absolute/.test(o.containerElement.css("position")),
            n && a && (i -= o.parentData.left),
            i + o.size.width >= o.parentData.width && (o.size.width = o.parentData.width - i, c && (o.size.height = o.size.width / o.aspectRatio)),
            s + o.size.height >= o.parentData.height && (o.size.height = o.parentData.height - s, c && (o.size.width = o.size.height * o.aspectRatio))
        },
        stop: function() {
            var e = t(this).data("ui-resizable"),
            i = e.options,
            s = e.containerOffset,
            n = e.containerPosition,
            a = e.containerElement,
            o = t(e.helper),
            r = o.offset(),
            h = o.outerWidth() - e.sizeDiff.width,
            l = o.outerHeight() - e.sizeDiff.height;
            e._helper && !i.animate && /relative/.test(a.css("position")) && t(this).css({
                left: r.left - n.left - s.left,
                width: h,
                height: l
            }),
            e._helper && !i.animate && /static/.test(a.css("position")) && t(this).css({
                left: r.left - n.left - s.left,
                width: h,
                height: l
            })
        }
    }),
    t.ui.plugin.add("resizable", "alsoResize", {
        start: function() {
            var e = t(this).data("ui-resizable"),
            i = e.options,
            s = function(e) {
                t(e).each(function() {
                    var e = t(this);
                    e.data("ui-resizable-alsoresize", {
                        width: parseInt(e.width(), 10),
                        height: parseInt(e.height(), 10),
                        left: parseInt(e.css("left"), 10),
                        top: parseInt(e.css("top"), 10)
                    })
                })
            };
            "object" != typeof i.alsoResize || i.alsoResize.parentNode ? s(i.alsoResize) : i.alsoResize.length ? (i.alsoResize = i.alsoResize[0], s(i.alsoResize)) : t.each(i.alsoResize,
            function(t) {
                s(t)
            })
        },
        resize: function(e, i) {
            var s = t(this).data("ui-resizable"),
            n = s.options,
            a = s.originalSize,
            o = s.originalPosition,
            r = {
                height: s.size.height - a.height || 0,
                width: s.size.width - a.width || 0,
                top: s.position.top - o.top || 0,
                left: s.position.left - o.left || 0
            },
            h = function(e, s) {
                t(e).each(function() {
                    var e = t(this),
                    n = t(this).data("ui-resizable-alsoresize"),
                    a = {},
                    o = s && s.length ? s: e.parents(i.originalElement[0]).length ? ["width", "height"] : ["width", "height", "top", "left"];
                    t.each(o,
                    function(t, e) {
                        var i = (n[e] || 0) + (r[e] || 0);
                        i && i >= 0 && (a[e] = i || null)
                    }),
                    e.css(a)
                })
            };
            "object" != typeof n.alsoResize || n.alsoResize.nodeType ? h(n.alsoResize) : t.each(n.alsoResize,
            function(t, e) {
                h(t, e)
            })
        },
        stop: function() {
            t(this).removeData("resizable-alsoresize")
        }
    }),
    t.ui.plugin.add("resizable", "ghost", {
        start: function() {
            var e = t(this).data("ui-resizable"),
            i = e.options,
            s = e.size;
            e.ghost = e.originalElement.clone(),
            e.ghost.css({
                opacity: .25,
                display: "block",
                position: "relative",
                height: s.height,
                width: s.width,
                margin: 0,
                left: 0,
                top: 0
            }).addClass("ui-resizable-ghost").addClass("string" == typeof i.ghost ? i.ghost: ""),
            e.ghost.appendTo(e.helper)
        },
        resize: function() {
            var e = t(this).data("ui-resizable");
            e.ghost && e.ghost.css({
                position: "relative",
                height: e.size.height,
                width: e.size.width
            })
        },
        stop: function() {
            var e = t(this).data("ui-resizable");
            e.ghost && e.helper && e.helper.get(0).removeChild(e.ghost.get(0))
        }
    }),
    t.ui.plugin.add("resizable", "grid", {
        resize: function() {
            var e = t(this).data("ui-resizable"),
            i = e.options,
            s = e.size,
            n = e.originalSize,
            a = e.originalPosition,
            o = e.axis,
            r = "number" == typeof i.grid ? [i.grid, i.grid] : i.grid,
            h = r[0] || 1,
            l = r[1] || 1,
            c = Math.round((s.width - n.width) / h) * h,
            u = Math.round((s.height - n.height) / l) * l,
            d = n.width + c,
            p = n.height + u,
            f = i.maxWidth && d > i.maxWidth,
            g = i.maxHeight && p > i.maxHeight,
            m = i.minWidth && i.minWidth > d,
            v = i.minHeight && i.minHeight > p;
            i.grid = r,
            m && (d += h),
            v && (p += l),
            f && (d -= h),
            g && (p -= l),
            /^(se|s|e)$/.test(o) ? (e.size.width = d, e.size.height = p) : /^(ne)$/.test(o) ? (e.size.width = d, e.size.height = p, e.position.top = a.top - u) : /^(sw)$/.test(o) ? (e.size.width = d, e.size.height = p, e.position.left = a.left - c) : (e.size.width = d, e.size.height = p, e.position.top = a.top - u, e.position.left = a.left - c)
        }
    })
} (jQuery),
function(t) {
    t.widget("ui.selectable", t.ui.mouse, {
        version: "1.10.3",
        options: {
            appendTo: "body",
            autoRefresh: !0,
            distance: 0,
            filter: "*",
            tolerance: "touch",
            selected: null,
            selecting: null,
            start: null,
            stop: null,
            unselected: null,
            unselecting: null
        },
        _create: function() {
            var e, i = this;
            this.element.addClass("ui-selectable"),
            this.dragged = !1,
            this.refresh = function() {
                e = t(i.options.filter, i.element[0]),
                e.addClass("ui-selectee"),
                e.each(function() {
                    var e = t(this),
                    i = e.offset();
                    t.data(this, "selectable-item", {
                        element: this,
                        $element: e,
                        left: i.left,
                        top: i.top,
                        right: i.left + e.outerWidth(),
                        bottom: i.top + e.outerHeight(),
                        startselected: !1,
                        selected: e.hasClass("ui-selected"),
                        selecting: e.hasClass("ui-selecting"),
                        unselecting: e.hasClass("ui-unselecting")
                    })
                })
            },
            this.refresh(),
            this.selectees = e.addClass("ui-selectee"),
            this._mouseInit(),
            this.helper = t("<div class='ui-selectable-helper'></div>")
        },
        _destroy: function() {
            this.selectees.removeClass("ui-selectee").removeData("selectable-item"),
            this.element.removeClass("ui-selectable ui-selectable-disabled"),
            this._mouseDestroy()
        },
        _mouseStart: function(e) {
            var i = this,
            s = this.options;
            this.opos = [e.pageX, e.pageY],
            this.options.disabled || (this.selectees = t(s.filter, this.element[0]), this._trigger("start", e), t(s.appendTo).append(this.helper), this.helper.css({
                left: e.pageX,
                top: e.pageY,
                width: 0,
                height: 0
            }), s.autoRefresh && this.refresh(), this.selectees.filter(".ui-selected").each(function() {
                var s = t.data(this, "selectable-item");
                s.startselected = !0,
                e.metaKey || e.ctrlKey || (s.$element.removeClass("ui-selected"), s.selected = !1, s.$element.addClass("ui-unselecting"), s.unselecting = !0, i._trigger("unselecting", e, {
                    unselecting: s.element
                }))
            }), t(e.target).parents().addBack().each(function() {
                var s, n = t.data(this, "selectable-item");
                return n ? (s = !e.metaKey && !e.ctrlKey || !n.$element.hasClass("ui-selected"), n.$element.removeClass(s ? "ui-unselecting": "ui-selected").addClass(s ? "ui-selecting": "ui-unselecting"), n.unselecting = !s, n.selecting = s, n.selected = s, s ? i._trigger("selecting", e, {
                    selecting: n.element
                }) : i._trigger("unselecting", e, {
                    unselecting: n.element
                }), !1) : void 0
            }))
        },
        _mouseDrag: function(e) {
            if (this.dragged = !0, !this.options.disabled) {
                var i, s = this,
                n = this.options,
                a = this.opos[0],
                o = this.opos[1],
                r = e.pageX,
                l = e.pageY;
                return a > r && (i = r, r = a, a = i),
                o > l && (i = l, l = o, o = i),
                this.helper.css({
                    left: a,
                    top: o,
                    width: r - a,
                    height: l - o
                }),
                this.selectees.each(function() {
                    var i = t.data(this, "selectable-item"),
                    h = !1;
                    i && i.element !== s.element[0] && ("touch" === n.tolerance ? h = !(i.left > r || a > i.right || i.top > l || o > i.bottom) : "fit" === n.tolerance && (h = i.left > a && r > i.right && i.top > o && l > i.bottom), h ? (i.selected && (i.$element.removeClass("ui-selected"), i.selected = !1), i.unselecting && (i.$element.removeClass("ui-unselecting"), i.unselecting = !1), i.selecting || (i.$element.addClass("ui-selecting"), i.selecting = !0, s._trigger("selecting", e, {
                        selecting: i.element
                    }))) : (i.selecting && ((e.metaKey || e.ctrlKey) && i.startselected ? (i.$element.removeClass("ui-selecting"), i.selecting = !1, i.$element.addClass("ui-selected"), i.selected = !0) : (i.$element.removeClass("ui-selecting"), i.selecting = !1, i.startselected && (i.$element.addClass("ui-unselecting"), i.unselecting = !0), s._trigger("unselecting", e, {
                        unselecting: i.element
                    }))), i.selected && (e.metaKey || e.ctrlKey || i.startselected || (i.$element.removeClass("ui-selected"), i.selected = !1, i.$element.addClass("ui-unselecting"), i.unselecting = !0, s._trigger("unselecting", e, {
                        unselecting: i.element
                    })))))
                }),
                !1
            }
        },
        _mouseStop: function(e) {
            var i = this;
            return this.dragged = !1,
            t(".ui-unselecting", this.element[0]).each(function() {
                var s = t.data(this, "selectable-item");
                s.$element.removeClass("ui-unselecting"),
                s.unselecting = !1,
                s.startselected = !1,
                i._trigger("unselected", e, {
                    unselected: s.element
                })
            }),
            t(".ui-selecting", this.element[0]).each(function() {
                var s = t.data(this, "selectable-item");
                s.$element.removeClass("ui-selecting").addClass("ui-selected"),
                s.selecting = !1,
                s.selected = !0,
                s.startselected = !0,
                i._trigger("selected", e, {
                    selected: s.element
                })
            }),
            this._trigger("stop", e),
            this.helper.remove(),
            !1
        }
    })
} (jQuery),
function(t) {
    function e(t, e, i) {
        return t > e && e + i > t
    }
    function i(t) {
        return /left|right/.test(t.css("float")) || /inline|table-cell/.test(t.css("display"))
    }
    t.widget("ui.sortable", t.ui.mouse, {
        version: "1.10.3",
        widgetEventPrefix: "sort",
        ready: !1,
        options: {
            appendTo: "parent",
            axis: !1,
            connectWith: !1,
            containment: !1,
            cursor: "auto",
            cursorAt: !1,
            dropOnEmpty: !0,
            forcePlaceholderSize: !1,
            forceHelperSize: !1,
            grid: !1,
            handle: !1,
            helper: "original",
            items: "> *",
            opacity: !1,
            placeholder: !1,
            revert: !1,
            scroll: !0,
            scrollSensitivity: 20,
            scrollSpeed: 20,
            scope: "default",
            tolerance: "intersect",
            zIndex: 1e3,
            activate: null,
            beforeStop: null,
            change: null,
            deactivate: null,
            out: null,
            over: null,
            receive: null,
            remove: null,
            sort: null,
            start: null,
            stop: null,
            update: null
        },
        _create: function() {
            var t = this.options;
            this.containerCache = {},
            this.element.addClass("ui-sortable"),
            this.refresh(),
            this.floating = this.items.length ? "x" === t.axis || i(this.items[0].item) : !1,
            this.offset = this.element.offset(),
            this._mouseInit(),
            this.ready = !0
        },
        _destroy: function() {
            this.element.removeClass("ui-sortable ui-sortable-disabled"),
            this._mouseDestroy();
            for (var t = this.items.length - 1; t >= 0; t--) this.items[t].item.removeData(this.widgetName + "-item");
            return this
        },
        _setOption: function(e, i) {
            "disabled" === e ? (this.options[e] = i, this.widget().toggleClass("ui-sortable-disabled", !!i)) : t.Widget.prototype._setOption.apply(this, arguments)
        },
        _mouseCapture: function(e, i) {
            var s = null,
            n = !1,
            o = this;
            return this.reverting ? !1 : this.options.disabled || "static" === this.options.type ? !1 : (this._refreshItems(e), t(e.target).parents().each(function() {
                return t.data(this, o.widgetName + "-item") === o ? (s = t(this), !1) : void 0
            }), t.data(e.target, o.widgetName + "-item") === o && (s = t(e.target)), s ? !this.options.handle || i || (t(this.options.handle, s).find("*").addBack().each(function() {
                this === e.target && (n = !0)
            }), n) ? (this.currentItem = s, this._removeCurrentsFromItems(), !0) : !1 : !1)
        },
        _mouseStart: function(e, i, s) {
            var n, o, a = this.options;
            if (this.currentContainer = this, this.refreshPositions(), this.helper = this._createHelper(e), this._cacheHelperProportions(), this._cacheMargins(), this.scrollParent = this.helper.scrollParent(), this.offset = this.currentItem.offset(), this.offset = {
                top: this.offset.top - this.margins.top,
                left: this.offset.left - this.margins.left
            },
            t.extend(this.offset, {
                click: {
                    left: e.pageX - this.offset.left,
                    top: e.pageY - this.offset.top
                },
                parent: this._getParentOffset(),
                relative: this._getRelativeOffset()
            }), this.helper.css("position", "absolute"), this.cssPosition = this.helper.css("position"), this.originalPosition = this._generatePosition(e), this.originalPageX = e.pageX, this.originalPageY = e.pageY, a.cursorAt && this._adjustOffsetFromHelper(a.cursorAt), this.domPosition = {
                prev: this.currentItem.prev()[0],
                parent: this.currentItem.parent()[0]
            },
            this.helper[0] !== this.currentItem[0] && this.currentItem.hide(), this._createPlaceholder(), a.containment && this._setContainment(), a.cursor && "auto" !== a.cursor && (o = this.document.find("body"), this.storedCursor = o.css("cursor"), o.css("cursor", a.cursor), this.storedStylesheet = t("<style>*{ cursor: " + a.cursor + " !important; }</style>").appendTo(o)), a.opacity && (this.helper.css("opacity") && (this._storedOpacity = this.helper.css("opacity")), this.helper.css("opacity", a.opacity)), a.zIndex && (this.helper.css("zIndex") && (this._storedZIndex = this.helper.css("zIndex")), this.helper.css("zIndex", a.zIndex)), this.scrollParent[0] !== document && "HTML" !== this.scrollParent[0].tagName && (this.overflowOffset = this.scrollParent.offset()), this._trigger("start", e, this._uiHash()), this._preserveHelperProportions || this._cacheHelperProportions(), !s) for (n = this.containers.length - 1; n >= 0; n--) this.containers[n]._trigger("activate", e, this._uiHash(this));
            return t.ui.ddmanager && (t.ui.ddmanager.current = this),
            t.ui.ddmanager && !a.dropBehaviour && t.ui.ddmanager.prepareOffsets(this, e),
            this.dragging = !0,
            this.helper.addClass("ui-sortable-helper"),
            this._mouseDrag(e),
            !0
        },
        _mouseDrag: function(e) {
            var i, s, n, o, a = this.options,
            r = !1;
            for (this.position = this._generatePosition(e), this.positionAbs = this._convertPositionTo("absolute"), this.lastPositionAbs || (this.lastPositionAbs = this.positionAbs), this.options.scroll && (this.scrollParent[0] !== document && "HTML" !== this.scrollParent[0].tagName ? (this.overflowOffset.top + this.scrollParent[0].offsetHeight - e.pageY < a.scrollSensitivity ? this.scrollParent[0].scrollTop = r = this.scrollParent[0].scrollTop + a.scrollSpeed: e.pageY - this.overflowOffset.top < a.scrollSensitivity && (this.scrollParent[0].scrollTop = r = this.scrollParent[0].scrollTop - a.scrollSpeed), this.overflowOffset.left + this.scrollParent[0].offsetWidth - e.pageX < a.scrollSensitivity ? this.scrollParent[0].scrollLeft = r = this.scrollParent[0].scrollLeft + a.scrollSpeed: e.pageX - this.overflowOffset.left < a.scrollSensitivity && (this.scrollParent[0].scrollLeft = r = this.scrollParent[0].scrollLeft - a.scrollSpeed)) : (e.pageY - t(document).scrollTop() < a.scrollSensitivity ? r = t(document).scrollTop(t(document).scrollTop() - a.scrollSpeed) : t(window).height() - (e.pageY - t(document).scrollTop()) < a.scrollSensitivity && (r = t(document).scrollTop(t(document).scrollTop() + a.scrollSpeed)), e.pageX - t(document).scrollLeft() < a.scrollSensitivity ? r = t(document).scrollLeft(t(document).scrollLeft() - a.scrollSpeed) : t(window).width() - (e.pageX - t(document).scrollLeft()) < a.scrollSensitivity && (r = t(document).scrollLeft(t(document).scrollLeft() + a.scrollSpeed))), r !== !1 && t.ui.ddmanager && !a.dropBehaviour && t.ui.ddmanager.prepareOffsets(this, e)), this.positionAbs = this._convertPositionTo("absolute"), this.options.axis && "y" === this.options.axis || (this.helper[0].style.left = this.position.left + "px"), this.options.axis && "x" === this.options.axis || (this.helper[0].style.top = this.position.top + "px"), i = this.items.length - 1; i >= 0; i--) if (s = this.items[i], n = s.item[0], o = this._intersectsWithPointer(s), o && s.instance === this.currentContainer && n !== this.currentItem[0] && this.placeholder[1 === o ? "next": "prev"]()[0] !== n && !t.contains(this.placeholder[0], n) && ("semi-dynamic" === this.options.type ? !t.contains(this.element[0], n) : !0)) {
                if (this.direction = 1 === o ? "down": "up", "pointer" !== this.options.tolerance && !this._intersectsWithSides(s)) break;
                this._rearrange(e, s),
                this._trigger("change", e, this._uiHash());
                break
            }
            return this._contactContainers(e),
            t.ui.ddmanager && t.ui.ddmanager.drag(this, e),
            this._trigger("sort", e, this._uiHash()),
            this.lastPositionAbs = this.positionAbs,
            !1
        },
        _mouseStop: function(e, i) {
            if (e) {
                if (t.ui.ddmanager && !this.options.dropBehaviour && t.ui.ddmanager.drop(this, e), this.options.revert) {
                    var s = this,
                    n = this.placeholder.offset(),
                    o = this.options.axis,
                    a = {};
                    o && "x" !== o || (a.left = n.left - this.offset.parent.left - this.margins.left + (this.offsetParent[0] === document.body ? 0 : this.offsetParent[0].scrollLeft)),
                    o && "y" !== o || (a.top = n.top - this.offset.parent.top - this.margins.top + (this.offsetParent[0] === document.body ? 0 : this.offsetParent[0].scrollTop)),
                    this.reverting = !0,
                    t(this.helper).animate(a, parseInt(this.options.revert, 10) || 500,
                    function() {
                        s._clear(e)
                    })
                } else this._clear(e, i);
                return ! 1
            }
        },
        cancel: function() {
            if (this.dragging) {
                this._mouseUp({
                    target: null
                }),
                "original" === this.options.helper ? this.currentItem.css(this._storedCSS).removeClass("ui-sortable-helper") : this.currentItem.show();
                for (var e = this.containers.length - 1; e >= 0; e--) this.containers[e]._trigger("deactivate", null, this._uiHash(this)),
                this.containers[e].containerCache.over && (this.containers[e]._trigger("out", null, this._uiHash(this)), this.containers[e].containerCache.over = 0)
            }
            return this.placeholder && (this.placeholder[0].parentNode && this.placeholder[0].parentNode.removeChild(this.placeholder[0]), "original" !== this.options.helper && this.helper && this.helper[0].parentNode && this.helper.remove(), t.extend(this, {
                helper: null,
                dragging: !1,
                reverting: !1,
                _noFinalSort: null
            }), this.domPosition.prev ? t(this.domPosition.prev).after(this.currentItem) : t(this.domPosition.parent).prepend(this.currentItem)),
            this
        },
        serialize: function(e) {
            var i = this._getItemsAsjQuery(e && e.connected),
            s = [];
            return e = e || {},
            t(i).each(function() {
                var i = (t(e.item || this).attr(e.attribute || "id") || "").match(e.expression || /(.+)[\-=_](.+)/);
                i && s.push((e.key || i[1] + "[]") + "=" + (e.key && e.expression ? i[1] : i[2]))
            }),
            !s.length && e.key && s.push(e.key + "="),
            s.join("&")
        },
        toArray: function(e) {
            var i = this._getItemsAsjQuery(e && e.connected),
            s = [];
            return e = e || {},
            i.each(function() {
                s.push(t(e.item || this).attr(e.attribute || "id") || "")
            }),
            s
        },
        _intersectsWith: function(t) {
            var e = this.positionAbs.left,
            i = e + this.helperProportions.width,
            s = this.positionAbs.top,
            n = s + this.helperProportions.height,
            o = t.left,
            a = o + t.width,
            r = t.top,
            h = r + t.height,
            l = this.offset.click.top,
            c = this.offset.click.left,
            u = "x" === this.options.axis || s + l > r && h > s + l,
            d = "y" === this.options.axis || e + c > o && a > e + c,
            p = u && d;
            return "pointer" === this.options.tolerance || this.options.forcePointerForContainers || "pointer" !== this.options.tolerance && this.helperProportions[this.floating ? "width": "height"] > t[this.floating ? "width": "height"] ? p: e + this.helperProportions.width / 2 > o && a > i - this.helperProportions.width / 2 && s + this.helperProportions.height / 2 > r && h > n - this.helperProportions.height / 2
        },
        _intersectsWithPointer: function(t) {
            var i = "x" === this.options.axis || e(this.positionAbs.top + this.offset.click.top, t.top, t.height),
            s = "y" === this.options.axis || e(this.positionAbs.left + this.offset.click.left, t.left, t.width),
            n = i && s,
            o = this._getDragVerticalDirection(),
            a = this._getDragHorizontalDirection();
            return n ? this.floating ? a && "right" === a || "down" === o ? 2 : 1 : o && ("down" === o ? 2 : 1) : !1
        },
        _intersectsWithSides: function(t) {
            var i = e(this.positionAbs.top + this.offset.click.top, t.top + t.height / 2, t.height),
            s = e(this.positionAbs.left + this.offset.click.left, t.left + t.width / 2, t.width),
            n = this._getDragVerticalDirection(),
            o = this._getDragHorizontalDirection();
            return this.floating && o ? "right" === o && s || "left" === o && !s: n && ("down" === n && i || "up" === n && !i)
        },
        _getDragVerticalDirection: function() {
            var t = this.positionAbs.top - this.lastPositionAbs.top;
            return 0 !== t && (t > 0 ? "down": "up")
        },
        _getDragHorizontalDirection: function() {
            var t = this.positionAbs.left - this.lastPositionAbs.left;
            return 0 !== t && (t > 0 ? "right": "left")
        },
        refresh: function(t) {
            return this._refreshItems(t),
            this.refreshPositions(),
            this
        },
        _connectWith: function() {
            var t = this.options;
            return t.connectWith.constructor === String ? [t.connectWith] : t.connectWith
        },
        _getItemsAsjQuery: function(e) {
            var i, s, n, o, a = [],
            r = [],
            h = this._connectWith();
            if (h && e) for (i = h.length - 1; i >= 0; i--) for (n = t(h[i]), s = n.length - 1; s >= 0; s--) o = t.data(n[s], this.widgetFullName),
            o && o !== this && !o.options.disabled && r.push([t.isFunction(o.options.items) ? o.options.items.call(o.element) : t(o.options.items, o.element).not(".ui-sortable-helper").not(".ui-sortable-placeholder"), o]);
            for (r.push([t.isFunction(this.options.items) ? this.options.items.call(this.element, null, {
                options: this.options,
                item: this.currentItem
            }) : t(this.options.items, this.element).not(".ui-sortable-helper").not(".ui-sortable-placeholder"), this]), i = r.length - 1; i >= 0; i--) r[i][0].each(function() {
                a.push(this)
            });
            return t(a)
        },
        _removeCurrentsFromItems: function() {
            var e = this.currentItem.find(":data(" + this.widgetName + "-item)");
            this.items = t.grep(this.items,
            function(t) {
                for (var i = 0; e.length > i; i++) if (e[i] === t.item[0]) return ! 1;
                return ! 0
            })
        },
        _refreshItems: function(e) {
            this.items = [],
            this.containers = [this];
            var i, s, n, o, a, r, h, l, c = this.items,
            u = [[t.isFunction(this.options.items) ? this.options.items.call(this.element[0], e, {
                item: this.currentItem
            }) : t(this.options.items, this.element), this]],
            d = this._connectWith();
            if (d && this.ready) for (i = d.length - 1; i >= 0; i--) for (n = t(d[i]), s = n.length - 1; s >= 0; s--) o = t.data(n[s], this.widgetFullName),
            o && o !== this && !o.options.disabled && (u.push([t.isFunction(o.options.items) ? o.options.items.call(o.element[0], e, {
                item: this.currentItem
            }) : t(o.options.items, o.element), o]), this.containers.push(o));
            for (i = u.length - 1; i >= 0; i--) for (a = u[i][1], r = u[i][0], s = 0, l = r.length; l > s; s++) h = t(r[s]),
            h.data(this.widgetName + "-item", a),
            c.push({
                item: h,
                instance: a,
                width: 0,
                height: 0,
                left: 0,
                top: 0
            })
        },
        refreshPositions: function(e) {
            this.offsetParent && this.helper && (this.offset.parent = this._getParentOffset());
            var i, s, n, o;
            for (i = this.items.length - 1; i >= 0; i--) s = this.items[i],
            s.instance !== this.currentContainer && this.currentContainer && s.item[0] !== this.currentItem[0] || (n = this.options.toleranceElement ? t(this.options.toleranceElement, s.item) : s.item, e || (s.width = n.outerWidth(), s.height = n.outerHeight()), o = n.offset(), s.left = o.left, s.top = o.top);
            if (this.options.custom && this.options.custom.refreshContainers) this.options.custom.refreshContainers.call(this);
            else for (i = this.containers.length - 1; i >= 0; i--) o = this.containers[i].element.offset(),
            this.containers[i].containerCache.left = o.left,
            this.containers[i].containerCache.top = o.top,
            this.containers[i].containerCache.width = this.containers[i].element.outerWidth(),
            this.containers[i].containerCache.height = this.containers[i].element.outerHeight();
            return this
        },

        _createPlaceholder: function(e) {
            e = e || this;
            var i, s = e.options;
            s.placeholder && s.placeholder.constructor !== String || (i = s.placeholder, s.placeholder = {
                element: function() {
                    var s = e.currentItem[0].nodeName.toLowerCase(),
                    n = t("<" + s + ">", e.document[0]).addClass(i || e.currentItem[0].className + " ui-sortable-placeholder").removeClass("ui-sortable-helper");
                    return "tr" === s ? e.currentItem.children().each(function() {
                        t("<td>&#160;</td>", e.document[0]).attr("colspan", t(this).attr("colspan") || 1).appendTo(n)
                    }) : "img" === s && n.attr("src", e.currentItem.attr("src")),
                    i || n.css("visibility", "hidden"),
                    n
                },
                update: function(t, n) { (!i || s.forcePlaceholderSize) && (n.height() || n.height(e.currentItem.innerHeight() - parseInt(e.currentItem.css("paddingTop") || 0, 10) - parseInt(e.currentItem.css("paddingBottom") || 0, 10)), n.width() || n.width(e.currentItem.innerWidth() - parseInt(e.currentItem.css("paddingLeft") || 0, 10) - parseInt(e.currentItem.css("paddingRight") || 0, 10)))
                }
            }),
            e.placeholder = t(s.placeholder.element.call(e.element, e.currentItem)),
            e.currentItem.after(e.placeholder),
            s.placeholder.update(e, e.placeholder)
        },
        _contactContainers: function(s) {
            var n, o, a, r, h, l, c, u, d, p, f = null,
            g = null;
            for (n = this.containers.length - 1; n >= 0; n--) if (!t.contains(this.currentItem[0], this.containers[n].element[0])) if (this._intersectsWith(this.containers[n].containerCache)) {
                if (f && t.contains(this.containers[n].element[0], f.element[0])) continue;
                f = this.containers[n],
                g = n
            } else this.containers[n].containerCache.over && (this.containers[n]._trigger("out", s, this._uiHash(this)), this.containers[n].containerCache.over = 0);
            if (f) if (1 === this.containers.length) this.containers[g].containerCache.over || (this.containers[g]._trigger("over", s, this._uiHash(this)), this.containers[g].containerCache.over = 1);
            else {
                for (a = 1e4, r = null, p = f.floating || i(this.currentItem), h = p ? "left": "top", l = p ? "width": "height", c = this.positionAbs[h] + this.offset.click[h], o = this.items.length - 1; o >= 0; o--) t.contains(this.containers[g].element[0], this.items[o].item[0]) && this.items[o].item[0] !== this.currentItem[0] && (!p || e(this.positionAbs.top + this.offset.click.top, this.items[o].top, this.items[o].height)) && (u = this.items[o].item.offset()[h], d = !1, Math.abs(u - c) > Math.abs(u + this.items[o][l] - c) && (d = !0, u += this.items[o][l]), a > Math.abs(u - c) && (a = Math.abs(u - c), r = this.items[o], this.direction = d ? "up": "down"));
                if (!r && !this.options.dropOnEmpty) return;
                if (this.currentContainer === this.containers[g]) return;
                r ? this._rearrange(s, r, null, !0) : this._rearrange(s, null, this.containers[g].element, !0),
                this._trigger("change", s, this._uiHash()),
                this.containers[g]._trigger("change", s, this._uiHash(this)),
                this.currentContainer = this.containers[g],
                this.options.placeholder.update(this.currentContainer, this.placeholder),
                this.containers[g]._trigger("over", s, this._uiHash(this)),
                this.containers[g].containerCache.over = 1
            }
        },
        _createHelper: function(e) {
            var i = this.options,
            s = t.isFunction(i.helper) ? t(i.helper.apply(this.element[0], [e, this.currentItem])) : "clone" === i.helper ? this.currentItem.clone() : this.currentItem;
            return s.parents("body").length || t("parent" !== i.appendTo ? i.appendTo: this.currentItem[0].parentNode)[0].appendChild(s[0]),
            s[0] === this.currentItem[0] && (this._storedCSS = {
                width: this.currentItem[0].style.width,
                height: this.currentItem[0].style.height,
                position: this.currentItem.css("position"),
                top: this.currentItem.css("top"),
                left: this.currentItem.css("left")
            }),
            (!s[0].style.width || i.forceHelperSize) && s.width(this.currentItem.width()),
            (!s[0].style.height || i.forceHelperSize) && s.height(this.currentItem.height()),
            s
        },
        _adjustOffsetFromHelper: function(e) {
            "string" == typeof e && (e = e.split(" ")),
            t.isArray(e) && (e = {
                left: +e[0],
                top: +e[1] || 0
            }),
            "left" in e && (this.offset.click.left = e.left + this.margins.left),
            "right" in e && (this.offset.click.left = this.helperProportions.width - e.right + this.margins.left),
            "top" in e && (this.offset.click.top = e.top + this.margins.top),
            "bottom" in e && (this.offset.click.top = this.helperProportions.height - e.bottom + this.margins.top)
        },
        _getParentOffset: function() {
            this.offsetParent = this.helper.offsetParent();
            var e = this.offsetParent.offset();
            return "absolute" === this.cssPosition && this.scrollParent[0] !== document && t.contains(this.scrollParent[0], this.offsetParent[0]) && (e.left += this.scrollParent.scrollLeft(), e.top += this.scrollParent.scrollTop()),
            (this.offsetParent[0] === document.body || this.offsetParent[0].tagName && "html" === this.offsetParent[0].tagName.toLowerCase() && t.ui.ie) && (e = {
                top: 0,
                left: 0
            }),
            {
                top: e.top + (parseInt(this.offsetParent.css("borderTopWidth"), 10) || 0),
                left: e.left + (parseInt(this.offsetParent.css("borderLeftWidth"), 10) || 0)
            }
        },
        _getRelativeOffset: function() {
            if ("relative" === this.cssPosition) {
                var t = this.currentItem.position();
                return {
                    top: t.top - (parseInt(this.helper.css("top"), 10) || 0) + this.scrollParent.scrollTop(),
                    left: t.left - (parseInt(this.helper.css("left"), 10) || 0) + this.scrollParent.scrollLeft()
                }
            }
            return {
                top: 0,
                left: 0
            }
        },
        _cacheMargins: function() {
            this.margins = {
                left: parseInt(this.currentItem.css("marginLeft"), 10) || 0,
                top: parseInt(this.currentItem.css("marginTop"), 10) || 0
            }
        },
        _cacheHelperProportions: function() {
            this.helperProportions = {
                width: this.helper.outerWidth(),
                height: this.helper.outerHeight()
            }
        },
        _setContainment: function() {
            var e, i, s, n = this.options;
            "parent" === n.containment && (n.containment = this.helper[0].parentNode),
            ("document" === n.containment || "window" === n.containment) && (this.containment = [0 - this.offset.relative.left - this.offset.parent.left, 0 - this.offset.relative.top - this.offset.parent.top, t("document" === n.containment ? document: window).width() - this.helperProportions.width - this.margins.left, (t("document" === n.containment ? document: window).height() || document.body.parentNode.scrollHeight) - this.helperProportions.height - this.margins.top]),
            /^(document|window|parent)$/.test(n.containment) || (e = t(n.containment)[0], i = t(n.containment).offset(), s = "hidden" !== t(e).css("overflow"), this.containment = [i.left + (parseInt(t(e).css("borderLeftWidth"), 10) || 0) + (parseInt(t(e).css("paddingLeft"), 10) || 0) - this.margins.left, i.top + (parseInt(t(e).css("borderTopWidth"), 10) || 0) + (parseInt(t(e).css("paddingTop"), 10) || 0) - this.margins.top, i.left + (s ? Math.max(e.scrollWidth, e.offsetWidth) : e.offsetWidth) - (parseInt(t(e).css("borderLeftWidth"), 10) || 0) - (parseInt(t(e).css("paddingRight"), 10) || 0) - this.helperProportions.width - this.margins.left, i.top + (s ? Math.max(e.scrollHeight, e.offsetHeight) : e.offsetHeight) - (parseInt(t(e).css("borderTopWidth"), 10) || 0) - (parseInt(t(e).css("paddingBottom"), 10) || 0) - this.helperProportions.height - this.margins.top])
        },
        _convertPositionTo: function(e, i) {
            i || (i = this.position);
            var s = "absolute" === e ? 1 : -1,
            n = "absolute" !== this.cssPosition || this.scrollParent[0] !== document && t.contains(this.scrollParent[0], this.offsetParent[0]) ? this.scrollParent: this.offsetParent,
            o = /(html|body)/i.test(n[0].tagName);
            return {
                top: i.top + this.offset.relative.top * s + this.offset.parent.top * s - ("fixed" === this.cssPosition ? -this.scrollParent.scrollTop() : o ? 0 : n.scrollTop()) * s,
                left: i.left + this.offset.relative.left * s + this.offset.parent.left * s - ("fixed" === this.cssPosition ? -this.scrollParent.scrollLeft() : o ? 0 : n.scrollLeft()) * s
            }
        },
        _generatePosition: function(e) {
            var i, s, n = this.options,
            o = e.pageX,
            a = e.pageY,
            r = "absolute" !== this.cssPosition || this.scrollParent[0] !== document && t.contains(this.scrollParent[0], this.offsetParent[0]) ? this.scrollParent: this.offsetParent,
            h = /(html|body)/i.test(r[0].tagName);
            return "relative" !== this.cssPosition || this.scrollParent[0] !== document && this.scrollParent[0] !== this.offsetParent[0] || (this.offset.relative = this._getRelativeOffset()),
            this.originalPosition && (this.containment && (e.pageX - this.offset.click.left < this.containment[0] && (o = this.containment[0] + this.offset.click.left), e.pageY - this.offset.click.top < this.containment[1] && (a = this.containment[1] + this.offset.click.top), e.pageX - this.offset.click.left > this.containment[2] && (o = this.containment[2] + this.offset.click.left), e.pageY - this.offset.click.top > this.containment[3] && (a = this.containment[3] + this.offset.click.top)), n.grid && (i = this.originalPageY + Math.round((a - this.originalPageY) / n.grid[1]) * n.grid[1], a = this.containment ? i - this.offset.click.top >= this.containment[1] && i - this.offset.click.top <= this.containment[3] ? i: i - this.offset.click.top >= this.containment[1] ? i - n.grid[1] : i + n.grid[1] : i, s = this.originalPageX + Math.round((o - this.originalPageX) / n.grid[0]) * n.grid[0], o = this.containment ? s - this.offset.click.left >= this.containment[0] && s - this.offset.click.left <= this.containment[2] ? s: s - this.offset.click.left >= this.containment[0] ? s - n.grid[0] : s + n.grid[0] : s)),
            {
                top: a - this.offset.click.top - this.offset.relative.top - this.offset.parent.top + ("fixed" === this.cssPosition ? -this.scrollParent.scrollTop() : h ? 0 : r.scrollTop()),
                left: o - this.offset.click.left - this.offset.relative.left - this.offset.parent.left + ("fixed" === this.cssPosition ? -this.scrollParent.scrollLeft() : h ? 0 : r.scrollLeft())
            }
        },
        _rearrange: function(t, e, i, s) {
            i ? i[0].appendChild(this.placeholder[0]) : e.item[0].parentNode.insertBefore(this.placeholder[0], "down" === this.direction ? e.item[0] : e.item[0].nextSibling),
            this.counter = this.counter ? ++this.counter: 1;
            var n = this.counter;
            this._delay(function() {
                n === this.counter && this.refreshPositions(!s)
            })
        },
        _clear: function(t, e) {
            this.reverting = !1;
            var i, s = [];
            if (!this._noFinalSort && this.currentItem.parent().length && this.placeholder.before(this.currentItem), this._noFinalSort = null, this.helper[0] === this.currentItem[0]) {
                for (i in this._storedCSS)("auto" === this._storedCSS[i] || "static" === this._storedCSS[i]) && (this._storedCSS[i] = "");
                this.currentItem.css(this._storedCSS).removeClass("ui-sortable-helper")
            } else this.currentItem.show();
            for (this.fromOutside && !e && s.push(function(t) {
                this._trigger("receive", t, this._uiHash(this.fromOutside))
            }), !this.fromOutside && this.domPosition.prev === this.currentItem.prev().not(".ui-sortable-helper")[0] && this.domPosition.parent === this.currentItem.parent()[0] || e || s.push(function(t) {
                this._trigger("update", t, this._uiHash())
            }), this !== this.currentContainer && (e || (s.push(function(t) {
                this._trigger("remove", t, this._uiHash())
            }), s.push(function(t) {
                return function(e) {
                    t._trigger("receive", e, this._uiHash(this))
                }
            }.call(this, this.currentContainer)), s.push(function(t) {
                return function(e) {
                    t._trigger("update", e, this._uiHash(this))
                }
            }.call(this, this.currentContainer)))), i = this.containers.length - 1; i >= 0; i--) e || s.push(function(t) {
                return function(e) {
                    t._trigger("deactivate", e, this._uiHash(this))
                }
            }.call(this, this.containers[i])),
            this.containers[i].containerCache.over && (s.push(function(t) {
                return function(e) {
                    t._trigger("out", e, this._uiHash(this))
                }
            }.call(this, this.containers[i])), this.containers[i].containerCache.over = 0);
            if (this.storedCursor && (this.document.find("body").css("cursor", this.storedCursor), this.storedStylesheet.remove()), this._storedOpacity && this.helper.css("opacity", this._storedOpacity), this._storedZIndex && this.helper.css("zIndex", "auto" === this._storedZIndex ? "": this._storedZIndex), this.dragging = !1, this.cancelHelperRemoval) {
                if (!e) {
                    for (this._trigger("beforeStop", t, this._uiHash()), i = 0; s.length > i; i++) s[i].call(this, t);
                    this._trigger("stop", t, this._uiHash())
                }
                return this.fromOutside = !1,
                !1
            }
            if (e || this._trigger("beforeStop", t, this._uiHash()), this.placeholder[0].parentNode.removeChild(this.placeholder[0]), this.helper[0] !== this.currentItem[0] && this.helper.remove(), this.helper = null, !e) {
                for (i = 0; s.length > i; i++) s[i].call(this, t);
                this._trigger("stop", t, this._uiHash())
            }
            return this.fromOutside = !1,
            !0
        },
        _trigger: function() {
            t.Widget.prototype._trigger.apply(this, arguments) === !1 && this.cancel()
        },
        _uiHash: function(e) {
            var i = e || this;
            return {
                helper: i.helper,
                placeholder: i.placeholder || t([]),
                position: i.position,
                originalPosition: i.originalPosition,
                offset: i.positionAbs,
                item: i.currentItem,
                sender: e ? e.element: null
            }
        }
    })
} (jQuery),
function(e) { 
    var t = 0,
    i = {},
    a = {};
    i.height = i.paddingTop = i.paddingBottom = i.borderTopWidth = i.borderBottomWidth = "hide",
    a.height = a.paddingTop = a.paddingBottom = a.borderTopWidth = a.borderBottomWidth = "show",
    e.widget("ui.accordion", {
        version: "1.10.3",
        options: {
            active: 0,
            animate: {},
            collapsible: !1,
            event: "click",
            header: "> li > :first-child,> :not(li):even",
            heightStyle: "auto",
            icons: {
                activeHeader: "ui-icon-triangle-1-s",
                header: "ui-icon-triangle-1-e"
            },
            activate: null,
            beforeActivate: null
        },
        _create: function() {
            var t = this.options;
            this.prevShow = this.prevHide = e(),
            this.element.addClass("ui-accordion ui-widget ui-helper-reset").attr("role", "tablist"),
            t.collapsible || t.active !== !1 && null != t.active || (t.active = 0),
            this._processPanels(),
            0 > t.active && (t.active += this.headers.length),
            this._refresh()
        },
        _getCreateEventData: function() {
            return {
                header: this.active,
                panel: this.active.length ? this.active.next() : e(),
                content: this.active.length ? this.active.next() : e()
            }
        },
        _createIcons: function() {
            var t = this.options.icons;
            t && (e("<span>").addClass("ui-accordion-header-icon ui-icon " + t.header).prependTo(this.headers), this.active.children(".ui-accordion-header-icon").removeClass(t.header).addClass(t.activeHeader), this.headers.addClass("ui-accordion-icons"))
        },
        _destroyIcons: function() {
            this.headers.removeClass("ui-accordion-icons").children(".ui-accordion-header-icon").remove()
        },
        _destroy: function() {
            var e;
            this.element.removeClass("ui-accordion ui-widget ui-helper-reset").removeAttr("role"),
            this.headers.removeClass("ui-accordion-header ui-accordion-header-active ui-helper-reset ui-state-default ui-corner-all ui-state-active ui-state-disabled ui-corner-top").removeAttr("role").removeAttr("aria-selected").removeAttr("aria-controls").removeAttr("tabIndex").each(function() { / ^ui - accordion / .test(this.id) && this.removeAttribute("id")
            }),
            this._destroyIcons(),
            e = this.headers.next().css("display", "").removeAttr("role").removeAttr("aria-expanded").removeAttr("aria-hidden").removeAttr("aria-labelledby").removeClass("ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content ui-accordion-content-active ui-state-disabled").each(function() { / ^ui - accordion / .test(this.id) && this.removeAttribute("id")
            }),
            "content" !== this.options.heightStyle && e.css("height", "")
        },
        _setOption: function(e, t) {
            return "active" === e ? (this._activate(t), void 0) : ("event" === e && (this.options.event && this._off(this.headers, this.options.event), this._setupEvents(t)), this._super(e, t), "collapsible" !== e || t || this.options.active !== !1 || this._activate(0), "icons" === e && (this._destroyIcons(), t && this._createIcons()), "disabled" === e && this.headers.add(this.headers.next()).toggleClass("ui-state-disabled", !!t), void 0)
        },
        _keydown: function(t) {
            if (!t.altKey && !t.ctrlKey) {
                var i = e.ui.keyCode,
                a = this.headers.length,
                s = this.headers.index(t.target),
                n = !1;
                switch (t.keyCode) {
                case i.RIGHT:
                case i.DOWN:
                    n = this.headers[(s + 1) % a];
                    break;
                case i.LEFT:
                case i.UP:
                    n = this.headers[(s - 1 + a) % a];
                    break;
                case i.SPACE:
                case i.ENTER:
                    this._eventHandler(t);
                    break;
                case i.HOME:
                    n = this.headers[0];
                    break;
                case i.END:
                    n = this.headers[a - 1]
                }
                n && (e(t.target).attr("tabIndex", -1), e(n).attr("tabIndex", 0), n.focus(), t.preventDefault())
            }
        },
        _panelKeyDown: function(t) {
            t.keyCode === e.ui.keyCode.UP && t.ctrlKey && e(t.currentTarget).prev().focus()
        },
        refresh: function() {
            var t = this.options;
            this._processPanels(),
            t.active === !1 && t.collapsible === !0 || !this.headers.length ? (t.active = !1, this.active = e()) : t.active === !1 ? this._activate(0) : this.active.length && !e.contains(this.element[0], this.active[0]) ? this.headers.length === this.headers.find(".ui-state-disabled").length ? (t.active = !1, this.active = e()) : this._activate(Math.max(0, t.active - 1)) : t.active = this.headers.index(this.active),
            this._destroyIcons(),
            this._refresh()
        },
        _processPanels: function() {
            this.headers = this.element.find(this.options.header).addClass("ui-accordion-header ui-helper-reset ui-state-default ui-corner-all"),
            this.headers.next().addClass("ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom").filter(":not(.ui-accordion-content-active)").hide()
        },
        _refresh: function() {
            var i, a = this.options,
            s = a.heightStyle,
            n = this.element.parent(),
            r = this.accordionId = "ui-accordion-" + (this.element.attr("id") || ++t);
            this.active = this._findActive(a.active).addClass("ui-accordion-header-active ui-state-active ui-corner-top").removeClass("ui-corner-all"),
            this.active.next().addClass("ui-accordion-content-active").show(),
            this.headers.attr("role", "tab").each(function(t) {
                var i = e(this),
                a = i.attr("id"),
                s = i.next(),
                n = s.attr("id");
                a || (a = r + "-header-" + t, i.attr("id", a)),
                n || (n = r + "-panel-" + t, s.attr("id", n)),
                i.attr("aria-controls", n),
                s.attr("aria-labelledby", a)
            }).next().attr("role", "tabpanel"),
            this.headers.not(this.active).attr({
                "aria-selected": "false",
                tabIndex: -1
            }).next().attr({
                "aria-expanded": "false",
                "aria-hidden": "true"
            }).hide(),
            this.active.length ? this.active.attr({
                "aria-selected": "true",
                tabIndex: 0
            }).next().attr({
                "aria-expanded": "true",
                "aria-hidden": "false"
            }) : this.headers.eq(0).attr("tabIndex", 0),
            this._createIcons(),
            this._setupEvents(a.event),
            "fill" === s ? (i = n.height(), this.element.siblings(":visible").each(function() {
                var t = e(this),
                a = t.css("position");
                "absolute" !== a && "fixed" !== a && (i -= t.outerHeight(!0))
            }), this.headers.each(function() {
                i -= e(this).outerHeight(!0)
            }), this.headers.next().each(function() {
                e(this).height(Math.max(0, i - e(this).innerHeight() + e(this).height()))
            }).css("overflow", "auto")) : "auto" === s && (i = 0, this.headers.next().each(function() {
                i = Math.max(i, e(this).css("height", "").height())
            }).height(i))
        },
        _activate: function(t) {
            var i = this._findActive(t)[0];
            i !== this.active[0] && (i = i || this.active[0], this._eventHandler({
                target: i,
                currentTarget: i,
                preventDefault: e.noop
            }))
        },
        _findActive: function(t) {
            return "number" == typeof t ? this.headers.eq(t) : e()
        },
        _setupEvents: function(t) {
            var i = {
                keydown: "_keydown"
            };
            t && e.each(t.split(" "),
            function(e, t) {
                i[t] = "_eventHandler"
            }),
            this._off(this.headers.add(this.headers.next())),
            this._on(this.headers, i),
            this._on(this.headers.next(), {
                keydown: "_panelKeyDown"
            }),
            this._hoverable(this.headers),
            this._focusable(this.headers)
        },
        _eventHandler: function(t) {
            var i = this.options,
            a = this.active,
            s = e(t.currentTarget),
            n = s[0] === a[0],
            r = n && i.collapsible,
            o = r ? e() : s.next(),
            h = a.next(),
            d = {
                oldHeader: a,
                oldPanel: h,
                newHeader: r ? e() : s,
                newPanel: o
            };
            t.preventDefault(),
            n && !i.collapsible || this._trigger("beforeActivate", t, d) === !1 || (i.active = r ? !1 : this.headers.index(s), this.active = n ? e() : s, this._toggle(d), a.removeClass("ui-accordion-header-active ui-state-active"), i.icons && a.children(".ui-accordion-header-icon").removeClass(i.icons.activeHeader).addClass(i.icons.header), n || (s.removeClass("ui-corner-all").addClass("ui-accordion-header-active ui-state-active ui-corner-top"), i.icons && s.children(".ui-accordion-header-icon").removeClass(i.icons.header).addClass(i.icons.activeHeader), s.next().addClass("ui-accordion-content-active")))
        },
        _toggle: function(t) {
            var i = t.newPanel,
            a = this.prevShow.length ? this.prevShow: t.oldPanel;
            this.prevShow.add(this.prevHide).stop(!0, !0),
            this.prevShow = i,
            this.prevHide = a,
            this.options.animate ? this._animate(i, a, t) : (a.hide(), i.show(), this._toggleComplete(t)),
            a.attr({
                "aria-expanded": "false",
                "aria-hidden": "true"
            }),
            a.prev().attr("aria-selected", "false"),
            i.length && a.length ? a.prev().attr("tabIndex", -1) : i.length && this.headers.filter(function() {
                return 0 === e(this).attr("tabIndex")
            }).attr("tabIndex", -1),
            i.attr({
                "aria-expanded": "true",
                "aria-hidden": "false"
            }).prev().attr({
                "aria-selected": "true",
                tabIndex: 0
            })
        },
        _animate: function(e, t, s) {
            var n, r, o, h = this,
            d = 0,
            c = e.length && (!t.length || e.index() < t.index()),
            l = this.options.animate || {},
            u = c && l.down || l,
            v = function() {
                h._toggleComplete(s)
            };
            return "number" == typeof u && (o = u),
            "string" == typeof u && (r = u),
            r = r || u.easing || l.easing,
            o = o || u.duration || l.duration,
            t.length ? e.length ? (n = e.show().outerHeight(), t.animate(i, {
                duration: o,
                easing: r,
                step: function(e, t) {
                    t.now = Math.round(e)
                }
            }), e.hide().animate(a, {
                duration: o,
                easing: r,
                complete: v,
                step: function(e, i) {
                    i.now = Math.round(e),
                    "height" !== i.prop ? d += i.now: "content" !== h.options.heightStyle && (i.now = Math.round(n - t.outerHeight() - d), d = 0)
                }
            }), void 0) : t.animate(i, o, r, v) : e.animate(a, o, r, v)
        },
        _toggleComplete: function(e) {
            var t = e.oldPanel;
            t.removeClass("ui-accordion-content-active").prev().removeClass("ui-corner-top").addClass("ui-corner-all"),
            t.length && (t.parent()[0].className = t.parent()[0].className),
            this._trigger("activate", null, e)
        }
    })
} (jQuery),
function(e) {
    var t = 0;
    e.widget("ui.autocomplete", {
        version: "1.10.3",
        defaultElement: "<input>",
        options: {
            appendTo: null,
            autoFocus: !1,
            delay: 300,
            minLength: 1,
            position: {
                my: "left top",
                at: "left bottom",
                collision: "none"
            },
            source: null,
            change: null,
            close: null,
            focus: null,
            open: null,
            response: null,
            search: null,
            select: null
        },
        pending: 0,
        _create: function() {
            var t, i, s, n = this.element[0].nodeName.toLowerCase(),
            a = "textarea" === n,
            o = "input" === n;
            this.isMultiLine = a ? !0 : o ? !1 : this.element.prop("isContentEditable"),
            this.valueMethod = this.element[a || o ? "val": "text"],
            this.isNewMenu = !0,
            this.element.addClass("ui-autocomplete-input").attr("autocomplete", "off"),
            this._on(this.element, {
                keydown: function(n) {
                    if (this.element.prop("readOnly")) return t = !0,
                    s = !0,
                    i = !0,
                    void 0;
                    t = !1,
                    s = !1,
                    i = !1;
                    var a = e.ui.keyCode;
                    switch (n.keyCode) {
                    case a.PAGE_UP:
                        t = !0,
                        this._move("previousPage", n);
                        break;
                    case a.PAGE_DOWN:
                        t = !0,
                        this._move("nextPage", n);
                        break;
                    case a.UP:
                        t = !0,
                        this._keyEvent("previous", n);
                        break;
                    case a.DOWN:
                        t = !0,
                        this._keyEvent("next", n);
                        break;
                    case a.ENTER:
                    case a.NUMPAD_ENTER:
                        this.menu.active && (t = !0, n.preventDefault(), this.menu.select(n));
                        break;
                    case a.TAB:
                        this.menu.active && this.menu.select(n);
                        break;
                    case a.ESCAPE:
                        this.menu.element.is(":visible") && (this._value(this.term), this.close(n), n.preventDefault());
                        break;
                    default:
                        i = !0,
                        this._searchTimeout(n)
                    }
                },
                keypress: function(s) {
                    if (t) return t = !1,
                    (!this.isMultiLine || this.menu.element.is(":visible")) && s.preventDefault(),
                    void 0;
                    if (!i) {
                        var n = e.ui.keyCode;
                        switch (s.keyCode) {
                        case n.PAGE_UP:
                            this._move("previousPage", s);
                            break;
                        case n.PAGE_DOWN:
                            this._move("nextPage", s);
                            break;
                        case n.UP:
                            this._keyEvent("previous", s);
                            break;
                        case n.DOWN:
                            this._keyEvent("next", s)
                        }
                    }
                },
                input: function(e) {
                    return s ? (s = !1, e.preventDefault(), void 0) : (this._searchTimeout(e), void 0)
                },
                focus: function() {
                    this.selectedItem = null,
                    this.previous = this._value()
                },
                blur: function(e) {
                    return this.cancelBlur ? (delete this.cancelBlur, void 0) : (clearTimeout(this.searching), this.close(e), this._change(e), void 0)
                }
            }),
            this._initSource(),
            this.menu = e("<ul>").addClass("ui-autocomplete ui-front").appendTo(this._appendTo()).menu({
                role: null
            }).hide().data("ui-menu"),
            this._on(this.menu.element, {
                mousedown: function(t) {
                    t.preventDefault(),
                    this.cancelBlur = !0,
                    this._delay(function() {
                        delete this.cancelBlur
                    });
                    var i = this.menu.element[0];
                    e(t.target).closest(".ui-menu-item").length || this._delay(function() {
                        var t = this;
                        this.document.one("mousedown",
                        function(s) {
                            s.target === t.element[0] || s.target === i || e.contains(i, s.target) || t.close()
                        })
                    })
                },
                menufocus: function(t, i) {
                    if (this.isNewMenu && (this.isNewMenu = !1, t.originalEvent && /^mouse/.test(t.originalEvent.type))) return this.menu.blur(),
                    this.document.one("mousemove",
                    function() {
                        e(t.target).trigger(t.originalEvent)
                    }),
                    void 0;
                    var s = i.item.data("ui-autocomplete-item"); ! 1 !== this._trigger("focus", t, {
                        item: s
                    }) ? t.originalEvent && /^key/.test(t.originalEvent.type) && this._value(s.value) : this.liveRegion.text(s.value)
                },
                menuselect: function(e, t) {
                    var i = t.item.data("ui-autocomplete-item"),
                    s = this.previous;
                    this.element[0] !== this.document[0].activeElement && (this.element.focus(), this.previous = s, this._delay(function() {
                        this.previous = s,
                        this.selectedItem = i
                    })),
                    !1 !== this._trigger("select", e, {
                        item: i
                    }) && this._value(i.value),
                    this.term = this._value(),
                    this.close(e),
                    this.selectedItem = i
                }
            }),
            this.liveRegion = e("<span>", {
                role: "status",
                "aria-live": "polite"
            }).addClass("ui-helper-hidden-accessible").insertBefore(this.element),
            this._on(this.window, {
                beforeunload: function() {
                    this.element.removeAttr("autocomplete")
                }
            })
        },
        _destroy: function() {
            clearTimeout(this.searching),
            this.element.removeClass("ui-autocomplete-input").removeAttr("autocomplete"),
            this.menu.element.remove(),
            this.liveRegion.remove()
        },
        _setOption: function(e, t) {
            this._super(e, t),
            "source" === e && this._initSource(),
            "appendTo" === e && this.menu.element.appendTo(this._appendTo()),
            "disabled" === e && t && this.xhr && this.xhr.abort()
        },
        _appendTo: function() {
            var t = this.options.appendTo;
            return t && (t = t.jquery || t.nodeType ? e(t) : this.document.find(t).eq(0)),
            t || (t = this.element.closest(".ui-front")),
            t.length || (t = this.document[0].body),
            t
        },
        _initSource: function() {
            var t, i, s = this;
            e.isArray(this.options.source) ? (t = this.options.source, this.source = function(i, s) {
                s(e.ui.autocomplete.filter(t, i.term))
            }) : "string" == typeof this.options.source ? (i = this.options.source, this.source = function(t, n) {
                s.xhr && s.xhr.abort(),
                s.xhr = e.ajax({
                    url: i,
                    data: t,
                    dataType: "json",
                    success: function(e) {
                        n(e)
                    },
                    error: function() {
                        n([])
                    }
                })
            }) : this.source = this.options.source
        },
        _searchTimeout: function(e) {
            clearTimeout(this.searching),
            this.searching = this._delay(function() {
                this.term !== this._value() && (this.selectedItem = null, this.search(null, e))
            },
            this.options.delay)
        },
        search: function(e, t) {
            return e = null != e ? e: this._value(),
            this.term = this._value(),
            e.length < this.options.minLength ? this.close(t) : this._trigger("search", t) !== !1 ? this._search(e) : void 0
        },
        _search: function(e) {
            this.pending++,
            this.element.addClass("ui-autocomplete-loading"),
            this.cancelSearch = !1,
            this.source({
                term: e
            },
            this._response())
        },
        _response: function() {
            var e = this,
            i = ++t;
            return function(s) {
                i === t && e.__response(s),
                e.pending--,
                e.pending || e.element.removeClass("ui-autocomplete-loading")
            }
        },
        __response: function(e) {
            e && (e = this._normalize(e)),
            this._trigger("response", null, {
                content: e
            }),
            !this.options.disabled && e && e.length && !this.cancelSearch ? (this._suggest(e), this._trigger("open")) : this._close()
        },
        close: function(e) {
            this.cancelSearch = !0,
            this._close(e)
        },
        _close: function(e) {
            this.menu.element.is(":visible") && (this.menu.element.hide(), this.menu.blur(), this.isNewMenu = !0, this._trigger("close", e))
        },
        _change: function(e) {
            this.previous !== this._value() && this._trigger("change", e, {
                item: this.selectedItem
            })
        },
        _normalize: function(t) {
            return t.length && t[0].label && t[0].value ? t: e.map(t,
            function(t) {
                return "string" == typeof t ? {
                    label: t,
                    value: t
                }: e.extend({
                    label: t.label || t.value,
                    value: t.value || t.label
                },
                t)
            })
        },
        _suggest: function(t) {
            var i = this.menu.element.empty();
            this._renderMenu(i, t),
            this.isNewMenu = !0,
            this.menu.refresh(),
            i.show(),
            this._resizeMenu(),
            i.position(e.extend({
                of: this.element
            },
            this.options.position)),
            this.options.autoFocus && this.menu.next()
        },
        _resizeMenu: function() {
            var e = this.menu.element;
            e.outerWidth(Math.max(e.width("").outerWidth() + 1, this.element.outerWidth()))
        },
        _renderMenu: function(t, i) {
            var s = this;
            e.each(i,
            function(e, i) {
                s._renderItemData(t, i)
            })
        },
        _renderItemData: function(e, t) {
            return this._renderItem(e, t).data("ui-autocomplete-item", t)
        },
        _renderItem: function(t, i) {
            return e("<li>").append(e("<a>").text(i.label)).appendTo(t)
        },
        _move: function(e, t) {
            return this.menu.element.is(":visible") ? this.menu.isFirstItem() && /^previous/.test(e) || this.menu.isLastItem() && /^next/.test(e) ? (this._value(this.term), this.menu.blur(), void 0) : (this.menu[e](t), void 0) : (this.search(null, t), void 0)
        },
        widget: function() {
            return this.menu.element
        },
        _value: function() {
            return this.valueMethod.apply(this.element, arguments)
        },
        _keyEvent: function(e, t) { (!this.isMultiLine || this.menu.element.is(":visible")) && (this._move(e, t), t.preventDefault())
        }
    }),
    e.extend(e.ui.autocomplete, {
        escapeRegex: function(e) {
            return e.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, "\\$&")
        },
        filter: function(t, i) {
            var s = RegExp(e.ui.autocomplete.escapeRegex(i), "i");
            return e.grep(t,
            function(e) {
                return s.test(e.label || e.value || e)
            })
        }
    }),
    e.widget("ui.autocomplete", e.ui.autocomplete, {
        options: {
            messages: {
                noResults: "No search results.",
                results: function(e) {
                    return e + (e > 1 ? " results are": " result is") + " available, use up and down arrow keys to navigate."
                }
            }
        },
        __response: function(e) {
            var t;
            this._superApply(arguments),
            this.options.disabled || this.cancelSearch || (t = e && e.length ? this.options.messages.results(e.length) : this.options.messages.noResults, this.liveRegion.text(t))
        }
    })
} (jQuery),
function(e) {
    var t, i, n, s, a = "ui-button ui-widget ui-state-default ui-corner-all",
    o = "ui-state-hover ui-state-active ",
    r = "ui-button-icons-only ui-button-icon-only ui-button-text-icons ui-button-text-icon-primary ui-button-text-icon-secondary ui-button-text-only",
    h = function() {
        var t = e(this);
        setTimeout(function() {
            t.find(":ui-button").button("refresh")
        },
        1)
    },
    u = function(t) {
        var i = t.name,
        n = t.form,
        s = e([]);
        return i && (i = i.replace(/'/g, "\\'"), s = n ? e(n).find("[name='" + i + "']") : e("[name='" + i + "']", t.ownerDocument).filter(function() {
            return ! this.form
        })),
        s
    };
    e.widget("ui.button", {
        version: "1.10.3",
        defaultElement: "<button>",
        options: {
            disabled: null,
            text: !0,
            label: null,
            icons: {
                primary: null,
                secondary: null
            }
        },
        _create: function() {
            this.element.closest("form").unbind("reset" + this.eventNamespace).bind("reset" + this.eventNamespace, h),
            "boolean" != typeof this.options.disabled ? this.options.disabled = !!this.element.prop("disabled") : this.element.prop("disabled", this.options.disabled),
            this._determineButtonType(),
            this.hasTitle = !!this.buttonElement.attr("title");
            var o = this,
            r = this.options,
            l = "checkbox" === this.type || "radio" === this.type,
            c = l ? "": "ui-state-active",
            d = "ui-state-focus";
            null === r.label && (r.label = "input" === this.type ? this.buttonElement.val() : this.buttonElement.html()),
            this._hoverable(this.buttonElement),
            this.buttonElement.addClass(a).attr("role", "button").bind("mouseenter" + this.eventNamespace,
            function() {
                r.disabled || this === t && e(this).addClass("ui-state-active")
            }).bind("mouseleave" + this.eventNamespace,
            function() {
                r.disabled || e(this).removeClass(c)
            }).bind("click" + this.eventNamespace,
            function(e) {
                r.disabled && (e.preventDefault(), e.stopImmediatePropagation())
            }),
            this.element.bind("focus" + this.eventNamespace,
            function() {
                o.buttonElement.addClass(d)
            }).bind("blur" + this.eventNamespace,
            function() {
                o.buttonElement.removeClass(d)
            }),
            l && (this.element.bind("change" + this.eventNamespace,
            function() {
                s || o.refresh()
            }), this.buttonElement.bind("mousedown" + this.eventNamespace,
            function(e) {
                r.disabled || (s = !1, i = e.pageX, n = e.pageY)
            }).bind("mouseup" + this.eventNamespace,
            function(e) {
                r.disabled || (i !== e.pageX || n !== e.pageY) && (s = !0)
            })),
            "checkbox" === this.type ? this.buttonElement.bind("click" + this.eventNamespace,
            function() {
                return r.disabled || s ? !1 : void 0
            }) : "radio" === this.type ? this.buttonElement.bind("click" + this.eventNamespace,
            function() {
                if (r.disabled || s) return ! 1;
                e(this).addClass("ui-state-active"),
                o.buttonElement.attr("aria-pressed", "true");
                var t = o.element[0];
                u(t).not(t).map(function() {
                    return e(this).button("widget")[0]
                }).removeClass("ui-state-active").attr("aria-pressed", "false")
            }) : (this.buttonElement.bind("mousedown" + this.eventNamespace,
            function() {
                return r.disabled ? !1 : (e(this).addClass("ui-state-active"), t = this, o.document.one("mouseup",
                function() {
                    t = null
                }), void 0)
            }).bind("mouseup" + this.eventNamespace,
            function() {
                return r.disabled ? !1 : (e(this).removeClass("ui-state-active"), void 0)
            }).bind("keydown" + this.eventNamespace,
            function(t) {
                return r.disabled ? !1 : ((t.keyCode === e.ui.keyCode.SPACE || t.keyCode === e.ui.keyCode.ENTER) && e(this).addClass("ui-state-active"), void 0)
            }).bind("keyup" + this.eventNamespace + " blur" + this.eventNamespace,
            function() {
                e(this).removeClass("ui-state-active")
            }), this.buttonElement.is("a") && this.buttonElement.keyup(function(t) {
                t.keyCode === e.ui.keyCode.SPACE && e(this).click()
            })),
            this._setOption("disabled", r.disabled),
            this._resetButton()
        },
        _determineButtonType: function() {
            var e, t, i;
            this.type = this.element.is("[type=checkbox]") ? "checkbox": this.element.is("[type=radio]") ? "radio": this.element.is("input") ? "input": "button",
            "checkbox" === this.type || "radio" === this.type ? (e = this.element.parents().last(), t = "label[for='" + this.element.attr("id") + "']", this.buttonElement = e.find(t), this.buttonElement.length || (e = e.length ? e.siblings() : this.element.siblings(), this.buttonElement = e.filter(t), this.buttonElement.length || (this.buttonElement = e.find(t))), this.element.addClass("ui-helper-hidden-accessible"), i = this.element.is(":checked"), i && this.buttonElement.addClass("ui-state-active"), this.buttonElement.prop("aria-pressed", i)) : this.buttonElement = this.element
        },
        widget: function() {
            return this.buttonElement
        },
        _destroy: function() {
            this.element.removeClass("ui-helper-hidden-accessible"),
            this.buttonElement.removeClass(a + " " + o + " " + r).removeAttr("role").removeAttr("aria-pressed").html(this.buttonElement.find(".ui-button-text").html()),
            this.hasTitle || this.buttonElement.removeAttr("title")
        },
        _setOption: function(e, t) {
            return this._super(e, t),
            "disabled" === e ? (t ? this.element.prop("disabled", !0) : this.element.prop("disabled", !1), void 0) : (this._resetButton(), void 0)
        },
        refresh: function() {
            var t = this.element.is("input, button") ? this.element.is(":disabled") : this.element.hasClass("ui-button-disabled");
            t !== this.options.disabled && this._setOption("disabled", t),
            "radio" === this.type ? u(this.element[0]).each(function() {
                e(this).is(":checked") ? e(this).button("widget").addClass("ui-state-active").attr("aria-pressed", "true") : e(this).button("widget").removeClass("ui-state-active").attr("aria-pressed", "false")
            }) : "checkbox" === this.type && (this.element.is(":checked") ? this.buttonElement.addClass("ui-state-active").attr("aria-pressed", "true") : this.buttonElement.removeClass("ui-state-active").attr("aria-pressed", "false"))
        },
        _resetButton: function() {
            if ("input" === this.type) return this.options.label && this.element.val(this.options.label),
            void 0;
            var t = this.buttonElement.removeClass(r),
            i = e("<span></span>", this.document[0]).addClass("ui-button-text").html(this.options.label).appendTo(t.empty()).text(),
            n = this.options.icons,
            s = n.primary && n.secondary,
            a = [];
            n.primary || n.secondary ? (this.options.text && a.push("ui-button-text-icon" + (s ? "s": n.primary ? "-primary": "-secondary")), n.primary && t.prepend("<span class='ui-button-icon-primary ui-icon " + n.primary + "'></span>"), n.secondary && t.append("<span class='ui-button-icon-secondary ui-icon " + n.secondary + "'></span>"), this.options.text || (a.push(s ? "ui-button-icons-only": "ui-button-icon-only"), this.hasTitle || t.attr("title", e.trim(i)))) : a.push("ui-button-text-only"),
            t.addClass(a.join(" "))
        }
    }),
    e.widget("ui.buttonset", {
        version: "1.10.3",
        options: {
            items: "button, input[type=button], input[type=submit], input[type=reset], input[type=checkbox], input[type=radio], a, :data(ui-button)"
        },
        _create: function() {
            this.element.addClass("ui-buttonset")
        },
        _init: function() {
            this.refresh()
        },
        _setOption: function(e, t) {
            "disabled" === e && this.buttons.button("option", e, t),
            this._super(e, t)
        },
        refresh: function() {
            var t = "rtl" === this.element.css("direction");
            this.buttons = this.element.find(this.options.items).filter(":ui-button").button("refresh").end().not(":ui-button").button().end().map(function() {
                return e(this).button("widget")[0]
            }).removeClass("ui-corner-all ui-corner-left ui-corner-right").filter(":first").addClass(t ? "ui-corner-right": "ui-corner-left").end().filter(":last").addClass(t ? "ui-corner-left": "ui-corner-right").end().end()
        },
        _destroy: function() {
            this.element.removeClass("ui-buttonset"),
            this.buttons.map(function() {
                return e(this).button("widget")[0]
            }).removeClass("ui-corner-left ui-corner-right").end().button("destroy")
        }
    })
} (jQuery),
function(e, t) {
    function i() {
        this._curInst = null,
        this._keyEvent = !1,
        this._disabledInputs = [],
        this._datepickerShowing = !1,
        this._inDialog = !1,
        this._mainDivId = "ui-datepicker-div",
        this._inlineClass = "ui-datepicker-inline",
        this._appendClass = "ui-datepicker-append",
        this._triggerClass = "ui-datepicker-trigger",
        this._dialogClass = "ui-datepicker-dialog",
        this._disableClass = "ui-datepicker-disabled",
        this._unselectableClass = "ui-datepicker-unselectable",
        this._currentClass = "ui-datepicker-current-day",
        this._dayOverClass = "ui-datepicker-days-cell-over",
        this.regional = [],
        this.regional[""] = {
            closeText: "Done",
            prevText: "Prev",
            nextText: "Next",
            currentText: "Today",
            monthNames: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
            monthNamesShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            dayNames: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
            dayNamesShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
            dayNamesMin: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
            weekHeader: "Wk",
            dateFormat: "mm/dd/yy",
            firstDay: 0,
            isRTL: !1,
            showMonthAfterYear: !1,
            yearSuffix: ""
        },
        this._defaults = {
            showOn: "focus",
            showAnim: "fadeIn",
            showOptions: {},
            defaultDate: null,
            appendText: "",
            buttonText: "...",
            buttonImage: "",
            buttonImageOnly: !1,
            hideIfNoPrevNext: !1,
            navigationAsDateFormat: !1,
            gotoCurrent: !1,
            changeMonth: !1,
            changeYear: !1,
            yearRange: "c-10:c+10",
            showOtherMonths: !1,
            selectOtherMonths: !1,
            showWeek: !1,
            calculateWeek: this.iso8601Week,
            shortYearCutoff: "+10",
            minDate: null,
            maxDate: null,
            duration: "fast",
            beforeShowDay: null,
            beforeShow: null,
            onSelect: null,
            onChangeMonthYear: null,
            onClose: null,
            numberOfMonths: 1,
            showCurrentAtPos: 0,
            stepMonths: 1,
            stepBigMonths: 12,
            altField: "",
            altFormat: "",
            constrainInput: !0,
            showButtonPanel: !1,
            autoSize: !1,
            disabled: !1
        },
        e.extend(this._defaults, this.regional[""]),
        this.dpDiv = a(e("<div id='" + this._mainDivId + "' class='ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all'></div>"))
    }
    function a(t) {
        var i = "button, .ui-datepicker-prev, .ui-datepicker-next, .ui-datepicker-calendar td a";
        return t.delegate(i, "mouseout",
        function() {
            e(this).removeClass("ui-state-hover"),
            -1 !== this.className.indexOf("ui-datepicker-prev") && e(this).removeClass("ui-datepicker-prev-hover"),
            -1 !== this.className.indexOf("ui-datepicker-next") && e(this).removeClass("ui-datepicker-next-hover")
        }).delegate(i, "mouseover",
        function() {
            e.datepicker._isDisabledDatepicker(n.inline ? t.parent()[0] : n.input[0]) || (e(this).parents(".ui-datepicker-calendar").find("a").removeClass("ui-state-hover"), e(this).addClass("ui-state-hover"), -1 !== this.className.indexOf("ui-datepicker-prev") && e(this).addClass("ui-datepicker-prev-hover"), -1 !== this.className.indexOf("ui-datepicker-next") && e(this).addClass("ui-datepicker-next-hover"))
        })
    }
    function s(t, i) {
        e.extend(t, i);
        for (var a in i) null == i[a] && (t[a] = i[a]);
        return t
    }
    e.extend(e.ui, {
        datepicker: {
            version: "1.10.3"
        }
    });
    var n, r = "datepicker";
    e.extend(i.prototype, {
        markerClassName: "hasDatepicker",
        maxRows: 4,
        _widgetDatepicker: function() {
            return this.dpDiv
        },
        setDefaults: function(e) {
            return s(this._defaults, e || {}),
            this
        },
        _attachDatepicker: function(t, i) {
            var a, s, n;
            a = t.nodeName.toLowerCase(),
            s = "div" === a || "span" === a,
            t.id || (this.uuid += 1, t.id = "dp" + this.uuid),
            n = this._newInst(e(t), s),
            n.settings = e.extend({},
            i || {}),
            "input" === a ? this._connectDatepicker(t, n) : s && this._inlineDatepicker(t, n)
        },
        _newInst: function(t, i) {
            var s = t[0].id.replace(/([^A-Za-z0-9_\-])/g, "\\\\$1");
            return {
                id: s,
                input: t,
                selectedDay: 0,
                selectedMonth: 0,
                selectedYear: 0,
                drawMonth: 0,
                drawYear: 0,
                inline: i,
                dpDiv: i ? a(e("<div class='" + this._inlineClass + " ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all'></div>")) : this.dpDiv
            }
        },
        _connectDatepicker: function(t, i) {

            var a = e(t);
            i.append = e([]),
            i.trigger = e([]),
            a.hasClass(this.markerClassName) || (this._attachments(a, i), a.addClass(this.markerClassName).keydown(this._doKeyDown).keypress(this._doKeyPress).keyup(this._doKeyUp), this._autoSize(i), e.data(t, r, i), i.settings.disabled && this._disableDatepicker(t))
        },
        _attachments: function(t, i) {
            var a, s, n, r = this._get(i, "appendText"),
            o = this._get(i, "isRTL");
            i.append && i.append.remove(),
            r && (i.append = e("<span class='" + this._appendClass + "'>" + r + "</span>"), t[o ? "before": "after"](i.append)),
            t.unbind("focus", this._showDatepicker),
            i.trigger && i.trigger.remove(),
            a = this._get(i, "showOn"),
            ("focus" === a || "both" === a) && t.focus(this._showDatepicker),
            ("button" === a || "both" === a) && (s = this._get(i, "buttonText"), n = this._get(i, "buttonImage"), i.trigger = e(this._get(i, "buttonImageOnly") ? e("<img/>").addClass(this._triggerClass).attr({
                src: n,
                alt: s,
                title: s
            }) : e("<button type='button'></button>").addClass(this._triggerClass).html(n ? e("<img/>").attr({
                src: n,
                alt: s,
                title: s
            }) : s)), t[o ? "before": "after"](i.trigger), i.trigger.click(function() {
                return e.datepicker._datepickerShowing && e.datepicker._lastInput === t[0] ? e.datepicker._hideDatepicker() : e.datepicker._datepickerShowing && e.datepicker._lastInput !== t[0] ? (e.datepicker._hideDatepicker(), e.datepicker._showDatepicker(t[0])) : e.datepicker._showDatepicker(t[0]),
                !1
            }))
        },
        _autoSize: function(e) {
            if (this._get(e, "autoSize") && !e.inline) {
                var t, i, a, s, n = new Date(2009, 11, 20),
                r = this._get(e, "dateFormat");
                r.match(/[DM]/) && (t = function(e) {
                    for (i = 0, a = 0, s = 0; e.length > s; s++) e[s].length > i && (i = e[s].length, a = s);
                    return a
                },
                n.setMonth(t(this._get(e, r.match(/MM/) ? "monthNames": "monthNamesShort"))), n.setDate(t(this._get(e, r.match(/DD/) ? "dayNames": "dayNamesShort")) + 20 - n.getDay())),
                e.input.attr("size", this._formatDate(e, n).length)
            }
        },
        _inlineDatepicker: function(t, i) {
            var a = e(t);
            a.hasClass(this.markerClassName) || (a.addClass(this.markerClassName).append(i.dpDiv), e.data(t, r, i), this._setDate(i, this._getDefaultDate(i), !0), this._updateDatepicker(i), this._updateAlternate(i), i.settings.disabled && this._disableDatepicker(t), i.dpDiv.css("display", "block"))
        },
        _dialogDatepicker: function(t, i, a, n, o) {
            var u, c, l, h, d, p = this._dialogInst;
            return p || (this.uuid += 1, u = "dp" + this.uuid, this._dialogInput = e("<input type='text' id='" + u + "' style='position: absolute; top: -100px; width: 0px;'/>"), this._dialogInput.keydown(this._doKeyDown), e("body").append(this._dialogInput), p = this._dialogInst = this._newInst(this._dialogInput, !1), p.settings = {},
            e.data(this._dialogInput[0], r, p)),
            s(p.settings, n || {}),
            i = i && i.constructor === Date ? this._formatDate(p, i) : i,
            this._dialogInput.val(i),
            this._pos = o ? o.length ? o: [o.pageX, o.pageY] : null,
            this._pos || (c = document.documentElement.clientWidth, l = document.documentElement.clientHeight, h = document.documentElement.scrollLeft || document.body.scrollLeft, d = document.documentElement.scrollTop || document.body.scrollTop, this._pos = [c / 2 - 100 + h, l / 2 - 150 + d]),
            this._dialogInput.css("left", this._pos[0] + 20 + "px").css("top", this._pos[1] + "px"),
            p.settings.onSelect = a,
            this._inDialog = !0,
            this.dpDiv.addClass(this._dialogClass),
            this._showDatepicker(this._dialogInput[0]),
            e.blockUI && e.blockUI(this.dpDiv),
            e.data(this._dialogInput[0], r, p),
            this
        },
        _destroyDatepicker: function(t) {
            var i, a = e(t),
            s = e.data(t, r);
            a.hasClass(this.markerClassName) && (i = t.nodeName.toLowerCase(), e.removeData(t, r), "input" === i ? (s.append.remove(), s.trigger.remove(), a.removeClass(this.markerClassName).unbind("focus", this._showDatepicker).unbind("keydown", this._doKeyDown).unbind("keypress", this._doKeyPress).unbind("keyup", this._doKeyUp)) : ("div" === i || "span" === i) && a.removeClass(this.markerClassName).empty())
        },
        _enableDatepicker: function(t) {
            var i, a, s = e(t),
            n = e.data(t, r);
            s.hasClass(this.markerClassName) && (i = t.nodeName.toLowerCase(), "input" === i ? (t.disabled = !1, n.trigger.filter("button").each(function() {
                this.disabled = !1
            }).end().filter("img").css({
                opacity: "1.0",
                cursor: ""
            })) : ("div" === i || "span" === i) && (a = s.children("." + this._inlineClass), a.children().removeClass("ui-state-disabled"), a.find("select.ui-datepicker-month, select.ui-datepicker-year").prop("disabled", !1)), this._disabledInputs = e.map(this._disabledInputs,
            function(e) {
                return e === t ? null: e
            }))
        },
        _disableDatepicker: function(t) {
            var i, a, s = e(t),
            n = e.data(t, r);
            s.hasClass(this.markerClassName) && (i = t.nodeName.toLowerCase(), "input" === i ? (t.disabled = !0, n.trigger.filter("button").each(function() {
                this.disabled = !0
            }).end().filter("img").css({
                opacity: "0.5",
                cursor: "default"
            })) : ("div" === i || "span" === i) && (a = s.children("." + this._inlineClass), a.children().addClass("ui-state-disabled"), a.find("select.ui-datepicker-month, select.ui-datepicker-year").prop("disabled", !0)), this._disabledInputs = e.map(this._disabledInputs,
            function(e) {
                return e === t ? null: e
            }), this._disabledInputs[this._disabledInputs.length] = t)
        },
        _isDisabledDatepicker: function(e) {
            if (!e) return ! 1;
            for (var t = 0; this._disabledInputs.length > t; t++) if (this._disabledInputs[t] === e) return ! 0;
            return ! 1
        },
        _getInst: function(t) {
            try {
                return e.data(t, r)
            } catch(i) {
                throw "Missing instance data for this datepicker"
            }
        },
        _optionDatepicker: function(i, a, n) {
            var r, o, u, c, l = this._getInst(i);
            return 2 === arguments.length && "string" == typeof a ? "defaults" === a ? e.extend({},
            e.datepicker._defaults) : l ? "all" === a ? e.extend({},
            l.settings) : this._get(l, a) : null: (r = a || {},
            "string" == typeof a && (r = {},
            r[a] = n), l && (this._curInst === l && this._hideDatepicker(), o = this._getDateDatepicker(i, !0), u = this._getMinMaxDate(l, "min"), c = this._getMinMaxDate(l, "max"), s(l.settings, r), null !== u && r.dateFormat !== t && r.minDate === t && (l.settings.minDate = this._formatDate(l, u)), null !== c && r.dateFormat !== t && r.maxDate === t && (l.settings.maxDate = this._formatDate(l, c)), "disabled" in r && (r.disabled ? this._disableDatepicker(i) : this._enableDatepicker(i)), this._attachments(e(i), l), this._autoSize(l), this._setDate(l, o), this._updateAlternate(l), this._updateDatepicker(l)), t)
        },
        _changeDatepicker: function(e, t, i) {
            this._optionDatepicker(e, t, i)
        },
        _refreshDatepicker: function(e) {
            var t = this._getInst(e);
            t && this._updateDatepicker(t)
        },
        _setDateDatepicker: function(e, t) {
            var i = this._getInst(e);
            i && (this._setDate(i, t), this._updateDatepicker(i), this._updateAlternate(i))
        },
        _getDateDatepicker: function(e, t) {
            var i = this._getInst(e);
            return i && !i.inline && this._setDateFromField(i, t),
            i ? this._getDate(i) : null
        },
        _doKeyDown: function(t) {
            var i, a, s, n = e.datepicker._getInst(t.target),
            r = !0,
            o = n.dpDiv.is(".ui-datepicker-rtl");
            if (n._keyEvent = !0, e.datepicker._datepickerShowing) switch (t.keyCode) {
            case 9:
                e.datepicker._hideDatepicker(),
                r = !1;
                break;
            case 13:
                return s = e("td." + e.datepicker._dayOverClass + ":not(." + e.datepicker._currentClass + ")", n.dpDiv),
                s[0] && e.datepicker._selectDay(t.target, n.selectedMonth, n.selectedYear, s[0]),
                i = e.datepicker._get(n, "onSelect"),
                i ? (a = e.datepicker._formatDate(n), i.apply(n.input ? n.input[0] : null, [a, n])) : e.datepicker._hideDatepicker(),
                !1;
            case 27:
                e.datepicker._hideDatepicker();
                break;
            case 33:
                e.datepicker._adjustDate(t.target, t.ctrlKey ? -e.datepicker._get(n, "stepBigMonths") : -e.datepicker._get(n, "stepMonths"), "M");
                break;
            case 34:
                e.datepicker._adjustDate(t.target, t.ctrlKey ? +e.datepicker._get(n, "stepBigMonths") : +e.datepicker._get(n, "stepMonths"), "M");
                break;
            case 35:
                (t.ctrlKey || t.metaKey) && e.datepicker._clearDate(t.target),
                r = t.ctrlKey || t.metaKey;
                break;
            case 36:
                (t.ctrlKey || t.metaKey) && e.datepicker._gotoToday(t.target),
                r = t.ctrlKey || t.metaKey;
                break;
            case 37:
                (t.ctrlKey || t.metaKey) && e.datepicker._adjustDate(t.target, o ? 1 : -1, "D"),
                r = t.ctrlKey || t.metaKey,
                t.originalEvent.altKey && e.datepicker._adjustDate(t.target, t.ctrlKey ? -e.datepicker._get(n, "stepBigMonths") : -e.datepicker._get(n, "stepMonths"), "M");
                break;
            case 38:
                (t.ctrlKey || t.metaKey) && e.datepicker._adjustDate(t.target, -7, "D"),
                r = t.ctrlKey || t.metaKey;
                break;
            case 39:
                (t.ctrlKey || t.metaKey) && e.datepicker._adjustDate(t.target, o ? -1 : 1, "D"),
                r = t.ctrlKey || t.metaKey,
                t.originalEvent.altKey && e.datepicker._adjustDate(t.target, t.ctrlKey ? +e.datepicker._get(n, "stepBigMonths") : +e.datepicker._get(n, "stepMonths"), "M");
                break;
            case 40:
                (t.ctrlKey || t.metaKey) && e.datepicker._adjustDate(t.target, 7, "D"),
                r = t.ctrlKey || t.metaKey;
                break;
            default:
                r = !1
            } else 36 === t.keyCode && t.ctrlKey ? e.datepicker._showDatepicker(this) : r = !1;
            r && (t.preventDefault(), t.stopPropagation())
        },
        _doKeyPress: function(i) {
            var a, s, n = e.datepicker._getInst(i.target);
            return e.datepicker._get(n, "constrainInput") ? (a = e.datepicker._possibleChars(e.datepicker._get(n, "dateFormat")), s = String.fromCharCode(null == i.charCode ? i.keyCode: i.charCode), i.ctrlKey || i.metaKey || " " > s || !a || a.indexOf(s) > -1) : t
        },
        _doKeyUp: function(t) {
            var i, a = e.datepicker._getInst(t.target);
            if (a.input.val() !== a.lastVal) try {
                i = e.datepicker.parseDate(e.datepicker._get(a, "dateFormat"), a.input ? a.input.val() : null, e.datepicker._getFormatConfig(a)),
                i && (e.datepicker._setDateFromField(a), e.datepicker._updateAlternate(a), e.datepicker._updateDatepicker(a))
            } catch(s) {}
            return ! 0
        },
        _showDatepicker: function(t) {
            if (t = t.target || t, "input" !== t.nodeName.toLowerCase() && (t = e("input", t.parentNode)[0]), !e.datepicker._isDisabledDatepicker(t) && e.datepicker._lastInput !== t) {
                var i, a, n, r, o, u, c;
                i = e.datepicker._getInst(t),
                e.datepicker._curInst && e.datepicker._curInst !== i && (e.datepicker._curInst.dpDiv.stop(!0, !0), i && e.datepicker._datepickerShowing && e.datepicker._hideDatepicker(e.datepicker._curInst.input[0])),
                a = e.datepicker._get(i, "beforeShow"),
                n = a ? a.apply(t, [t, i]) : {},
                n !== !1 && (s(i.settings, n), i.lastVal = null, e.datepicker._lastInput = t, e.datepicker._setDateFromField(i), e.datepicker._inDialog && (t.value = ""), e.datepicker._pos || (e.datepicker._pos = e.datepicker._findPos(t), e.datepicker._pos[1] += t.offsetHeight), r = !1, e(t).parents().each(function() {
                    return r |= "fixed" === e(this).css("position"),
                    !r
                }), o = {
                    left: e.datepicker._pos[0],
                    top: e.datepicker._pos[1]
                },
                e.datepicker._pos = null, i.dpDiv.empty(), i.dpDiv.css({
                    position: "absolute",
                    display: "block",
                    top: "-1000px"
                }), e.datepicker._updateDatepicker(i), o = e.datepicker._checkOffset(i, o, r), i.dpDiv.css({
                    position: e.datepicker._inDialog && e.blockUI ? "static": r ? "fixed": "absolute",
                    display: "none",
                    left: o.left + "px",
                    top: o.top + "px"
                }), i.inline || (u = e.datepicker._get(i, "showAnim"), c = e.datepicker._get(i, "duration"), i.dpDiv.zIndex(e(t).zIndex() + 1), e.datepicker._datepickerShowing = !0, e.effects && e.effects.effect[u] ? i.dpDiv.show(u, e.datepicker._get(i, "showOptions"), c) : i.dpDiv[u || "show"](u ? c: null), e.datepicker._shouldFocusInput(i) && i.input.focus(), e.datepicker._curInst = i))
            }
        },
        _updateDatepicker: function(t) {
            this.maxRows = 4,
            n = t,
            t.dpDiv.empty().append(this._generateHTML(t)),
            this._attachHandlers(t),
            t.dpDiv.find("." + this._dayOverClass + " a").mouseover();
            var i, a = this._getNumberOfMonths(t),
            s = a[1],
            r = 17;
            t.dpDiv.removeClass("ui-datepicker-multi-2 ui-datepicker-multi-3 ui-datepicker-multi-4").width(""),
            s > 1 && t.dpDiv.addClass("ui-datepicker-multi-" + s).css("width", r * s + "em"),
            t.dpDiv[(1 !== a[0] || 1 !== a[1] ? "add": "remove") + "Class"]("ui-datepicker-multi"),
            t.dpDiv[(this._get(t, "isRTL") ? "add": "remove") + "Class"]("ui-datepicker-rtl"),
            t === e.datepicker._curInst && e.datepicker._datepickerShowing && e.datepicker._shouldFocusInput(t) && t.input.focus(),
            t.yearshtml && (i = t.yearshtml, setTimeout(function() {
                i === t.yearshtml && t.yearshtml && t.dpDiv.find("select.ui-datepicker-year:first").replaceWith(t.yearshtml),
                i = t.yearshtml = null
            },
            0))
        },
        _shouldFocusInput: function(e) {
            return e.input && e.input.is(":visible") && !e.input.is(":disabled") && !e.input.is(":focus")
        },
        _checkOffset: function(t, i, a) {
            var s = t.dpDiv.outerWidth(),
            n = t.dpDiv.outerHeight(),
            r = t.input ? t.input.outerWidth() : 0,
            o = t.input ? t.input.outerHeight() : 0,
            u = document.documentElement.clientWidth + (a ? 0 : e(document).scrollLeft()),
            c = document.documentElement.clientHeight + (a ? 0 : e(document).scrollTop());
            return i.left -= this._get(t, "isRTL") ? s - r: 0,
            i.left -= a && i.left === t.input.offset().left ? e(document).scrollLeft() : 0,
            i.top -= a && i.top === t.input.offset().top + o ? e(document).scrollTop() : 0,
            i.left -= Math.min(i.left, i.left + s > u && u > s ? Math.abs(i.left + s - u) : 0),
            i.top -= Math.min(i.top, i.top + n > c && c > n ? Math.abs(n + o) : 0),
            i
        },
        _findPos: function(t) {
            for (var i, a = this._getInst(t), s = this._get(a, "isRTL"); t && ("hidden" === t.type || 1 !== t.nodeType || e.expr.filters.hidden(t));) t = t[s ? "previousSibling": "nextSibling"];
            return i = e(t).offset(),
            [i.left, i.top]
        },
        _hideDatepicker: function(t) {
            var i, a, s, n, o = this._curInst; ! o || t && o !== e.data(t, r) || this._datepickerShowing && (i = this._get(o, "showAnim"), a = this._get(o, "duration"), s = function() {
                e.datepicker._tidyDialog(o)
            },
            e.effects && (e.effects.effect[i] || e.effects[i]) ? o.dpDiv.hide(i, e.datepicker._get(o, "showOptions"), a, s) : o.dpDiv["slideDown" === i ? "slideUp": "fadeIn" === i ? "fadeOut": "hide"](i ? a: null, s), i || s(), this._datepickerShowing = !1, n = this._get(o, "onClose"), n && n.apply(o.input ? o.input[0] : null, [o.input ? o.input.val() : "", o]), this._lastInput = null, this._inDialog && (this._dialogInput.css({
                position: "absolute",
                left: "0",
                top: "-100px"
            }), e.blockUI && (e.unblockUI(), e("body").append(this.dpDiv))), this._inDialog = !1)
        },
        _tidyDialog: function(e) {
            e.dpDiv.removeClass(this._dialogClass).unbind(".ui-datepicker-calendar")
        },
        _checkExternalClick: function(t) {
            if (e.datepicker._curInst) {
                var i = e(t.target),
                a = e.datepicker._getInst(i[0]); (i[0].id !== e.datepicker._mainDivId && 0 === i.parents("#" + e.datepicker._mainDivId).length && !i.hasClass(e.datepicker.markerClassName) && !i.closest("." + e.datepicker._triggerClass).length && e.datepicker._datepickerShowing && (!e.datepicker._inDialog || !e.blockUI) || i.hasClass(e.datepicker.markerClassName) && e.datepicker._curInst !== a) && e.datepicker._hideDatepicker()
            }
        },
        _adjustDate: function(t, i, a) {
            var s = e(t),
            n = this._getInst(s[0]);
            this._isDisabledDatepicker(s[0]) || (this._adjustInstDate(n, i + ("M" === a ? this._get(n, "showCurrentAtPos") : 0), a), this._updateDatepicker(n))
        },
        _gotoToday: function(t) {
            var i, a = e(t),
            s = this._getInst(a[0]);
            this._get(s, "gotoCurrent") && s.currentDay ? (s.selectedDay = s.currentDay, s.drawMonth = s.selectedMonth = s.currentMonth, s.drawYear = s.selectedYear = s.currentYear) : (i = new Date, s.selectedDay = i.getDate(), s.drawMonth = s.selectedMonth = i.getMonth(), s.drawYear = s.selectedYear = i.getFullYear()),
            this._notifyChange(s),
            this._adjustDate(a)
        },
        _selectMonthYear: function(t, i, a) {
            var s = e(t),
            n = this._getInst(s[0]);
            n["selected" + ("M" === a ? "Month": "Year")] = n["draw" + ("M" === a ? "Month": "Year")] = parseInt(i.options[i.selectedIndex].value, 10),
            this._notifyChange(n),
            this._adjustDate(s)
        },
        _selectDay: function(t, i, a, s) {
            var n, r = e(t);
            e(s).hasClass(this._unselectableClass) || this._isDisabledDatepicker(r[0]) || (n = this._getInst(r[0]), n.selectedDay = n.currentDay = e("a", s).html(), n.selectedMonth = n.currentMonth = i, n.selectedYear = n.currentYear = a, this._selectDate(t, this._formatDate(n, n.currentDay, n.currentMonth, n.currentYear)))
        },
        _clearDate: function(t) {
            var i = e(t);
            this._selectDate(i, "")
        },
        _selectDate: function(t, i) {
            var a, s = e(t),
            n = this._getInst(s[0]);
            i = null != i ? i: this._formatDate(n),
            n.input && n.input.val(i),
            this._updateAlternate(n),
            a = this._get(n, "onSelect"),
            a ? a.apply(n.input ? n.input[0] : null, [i, n]) : n.input && n.input.trigger("change"),
            n.inline ? this._updateDatepicker(n) : (this._hideDatepicker(), this._lastInput = n.input[0], "object" != typeof n.input[0] && n.input.focus(), this._lastInput = null)
        },
        _updateAlternate: function(t) {
            var i, a, s, n = this._get(t, "altField");
            n && (i = this._get(t, "altFormat") || this._get(t, "dateFormat"), a = this._getDate(t), s = this.formatDate(i, a, this._getFormatConfig(t)), e(n).each(function() {
                e(this).val(s)
            }))
        },
        noWeekends: function(e) {
            var t = e.getDay();
            return [t > 0 && 6 > t, ""]
        },
        iso8601Week: function(e) {
            var t, i = new Date(e.getTime());
            return i.setDate(i.getDate() + 4 - (i.getDay() || 7)),
            t = i.getTime(),
            i.setMonth(0),
            i.setDate(1),
            Math.floor(Math.round((t - i) / 864e5) / 7) + 1
        },
        parseDate: function(i, a, s) {
            if (null == i || null == a) throw "Invalid arguments";
            if (a = "object" == typeof a ? "" + a: a + "", "" === a) return null;
            var n, r, o, u, c = 0,
            l = (s ? s.shortYearCutoff: null) || this._defaults.shortYearCutoff,
            h = "string" != typeof l ? l: (new Date).getFullYear() % 100 + parseInt(l, 10),
            d = (s ? s.dayNamesShort: null) || this._defaults.dayNamesShort,
            p = (s ? s.dayNames: null) || this._defaults.dayNames,
            g = (s ? s.monthNamesShort: null) || this._defaults.monthNamesShort,
            m = (s ? s.monthNames: null) || this._defaults.monthNames,
            f = -1,
            _ = -1,
            v = -1,
            k = -1,
            b = !1,
            y = function(e) {
                var t = i.length > n + 1 && i.charAt(n + 1) === e;
                return t && n++,
                t
            },
            D = function(e) {
                var t = y(e),
                i = "@" === e ? 14 : "!" === e ? 20 : "y" === e && t ? 4 : "o" === e ? 3 : 2,
                s = RegExp("^\\d{1," + i + "}"),
                n = a.substring(c).match(s);
                if (!n) throw "Missing number at position " + c;
                return c += n[0].length,
                parseInt(n[0], 10)
            },
            w = function(i, s, n) {
                var r = -1,
                o = e.map(y(i) ? n: s,
                function(e, t) {
                    return [[t, e]]
                }).sort(function(e, t) {
                    return - (e[1].length - t[1].length)
                });
                if (e.each(o,
                function(e, i) {
                    var s = i[1];
                    return a.substr(c, s.length).toLowerCase() === s.toLowerCase() ? (r = i[0], c += s.length, !1) : t
                }), -1 !== r) return r + 1;
                throw "Unknown name at position " + c
            },
            M = function() {
                if (a.charAt(c) !== i.charAt(n)) throw "Unexpected literal at position " + c;
                c++
            };
            for (n = 0; i.length > n; n++) if (b)"'" !== i.charAt(n) || y("'") ? M() : b = !1;
            else switch (i.charAt(n)) {
            case "d":
                v = D("d");
                break;
            case "D":
                w("D", d, p);
                break;
            case "o":
                k = D("o");
                break;
            case "m":
                _ = D("m");
                break;
            case "M":
                _ = w("M", g, m);
                break;
            case "y":
                f = D("y");
                break;
            case "@":
                u = new Date(D("@")),
                f = u.getFullYear(),
                _ = u.getMonth() + 1,
                v = u.getDate();
                break;
            case "!":
                u = new Date((D("!") - this._ticksTo1970) / 1e4),
                f = u.getFullYear(),
                _ = u.getMonth() + 1,
                v = u.getDate();
                break;
            case "'":
                y("'") ? M() : b = !0;
                break;
            default:
                M()
            }
            if (a.length > c && (o = a.substr(c), !/^\s+/.test(o))) throw "Extra/unparsed characters found in date: " + o;
            if ( - 1 === f ? f = (new Date).getFullYear() : 100 > f && (f += (new Date).getFullYear() - (new Date).getFullYear() % 100 + (h >= f ? 0 : -100)), k > -1) for (_ = 1, v = k; r = this._getDaysInMonth(f, _ - 1), !(r >= v);) _++,
            v -= r;
            if (u = this._daylightSavingAdjust(new Date(f, _ - 1, v)), u.getFullYear() !== f || u.getMonth() + 1 !== _ || u.getDate() !== v) throw "Invalid date";
            return u
        },
        ATOM: "yy-mm-dd",
        COOKIE: "D, dd M yy",
        ISO_8601: "yy-mm-dd",
        RFC_822: "D, d M y",
        RFC_850: "DD, dd-M-y",
        RFC_1036: "D, d M y",
        RFC_1123: "D, d M yy",
        RFC_2822: "D, d M yy",
        RSS: "D, d M y",
        TICKS: "!",
        TIMESTAMP: "@",
        W3C: "yy-mm-dd",
        _ticksTo1970: 864e9 * (718685 + Math.floor(492.5) - Math.floor(19.7) + Math.floor(4.925)),
        formatDate: function(e, t, i) {
            if (!t) return "";
            var a, s = (i ? i.dayNamesShort: null) || this._defaults.dayNamesShort,
            n = (i ? i.dayNames: null) || this._defaults.dayNames,
            r = (i ? i.monthNamesShort: null) || this._defaults.monthNamesShort,
            o = (i ? i.monthNames: null) || this._defaults.monthNames,
            u = function(t) {
                var i = e.length > a + 1 && e.charAt(a + 1) === t;
                return i && a++,
                i
            },
            c = function(e, t, i) {
                var a = "" + t;
                if (u(e)) for (; i > a.length;) a = "0" + a;
                return a
            },
            l = function(e, t, i, a) {
                return u(e) ? a[t] : i[t]
            },
            h = "",
            d = !1;
            if (t) for (a = 0; e.length > a; a++) if (d)"'" !== e.charAt(a) || u("'") ? h += e.charAt(a) : d = !1;
            else switch (e.charAt(a)) {
            case "d":
                h += c("d", t.getDate(), 2);
                break;
            case "D":
                h += l("D", t.getDay(), s, n);
                break;
            case "o":
                h += c("o", Math.round((new Date(t.getFullYear(), t.getMonth(), t.getDate()).getTime() - new Date(t.getFullYear(), 0, 0).getTime()) / 864e5), 3);
                break;
            case "m":
                h += c("m", t.getMonth() + 1, 2);
                break;
            case "M":
                h += l("M", t.getMonth(), r, o);
                break;
            case "y":
                h += u("y") ? t.getFullYear() : (10 > t.getYear() % 100 ? "0": "") + t.getYear() % 100;
                break;
            case "@":
                h += t.getTime();
                break;
            case "!":
                h += 1e4 * t.getTime() + this._ticksTo1970;
                break;
            case "'":
                u("'") ? h += "'": d = !0;
                break;
            default:
                h += e.charAt(a)
            }
            return h
        },
        _possibleChars: function(e) {
            var t, i = "",
            a = !1,
            s = function(i) {
                var a = e.length > t + 1 && e.charAt(t + 1) === i;
                return a && t++,
                a
            };
            for (t = 0; e.length > t; t++) if (a)"'" !== e.charAt(t) || s("'") ? i += e.charAt(t) : a = !1;
            else switch (e.charAt(t)) {
            case "d":
            case "m":
            case "y":
            case "@":
                i += "0123456789";
                break;
            case "D":
            case "M":
                return null;
            case "'":
                s("'") ? i += "'": a = !0;
                break;
            default:
                i += e.charAt(t)
            }
            return i
        },
        _get: function(e, i) {
            return e.settings[i] !== t ? e.settings[i] : this._defaults[i]
        },
        _setDateFromField: function(e, t) {
            if (e.input.val() !== e.lastVal) {
                var i = this._get(e, "dateFormat"),
                a = e.lastVal = e.input ? e.input.val() : null,
                s = this._getDefaultDate(e),
                n = s,
                r = this._getFormatConfig(e);
                try {
                    n = this.parseDate(i, a, r) || s
                } catch(o) {
                    a = t ? "": a
                }
                e.selectedDay = n.getDate(),
                e.drawMonth = e.selectedMonth = n.getMonth(),
                e.drawYear = e.selectedYear = n.getFullYear(),
                e.currentDay = a ? n.getDate() : 0,
                e.currentMonth = a ? n.getMonth() : 0,
                e.currentYear = a ? n.getFullYear() : 0,
                this._adjustInstDate(e)
            }
        },
        _getDefaultDate: function(e) {
            return this._restrictMinMax(e, this._determineDate(e, this._get(e, "defaultDate"), new Date))
        },
        _determineDate: function(t, i, a) {
            var s = function(e) {
                var t = new Date;
                return t.setDate(t.getDate() + e),
                t
            },
            n = function(i) {
                try {
                    return e.datepicker.parseDate(e.datepicker._get(t, "dateFormat"), i, e.datepicker._getFormatConfig(t))
                } catch(a) {}
                for (var s = (i.toLowerCase().match(/^c/) ? e.datepicker._getDate(t) : null) || new Date, n = s.getFullYear(), r = s.getMonth(), o = s.getDate(), u = /([+\-]?[0-9]+)\s*(d|D|w|W|m|M|y|Y)?/g, c = u.exec(i); c;) {
                    switch (c[2] || "d") {
                    case "d":
                    case "D":
                        o += parseInt(c[1], 10);
                        break;
                    case "w":
                    case "W":
                        o += 7 * parseInt(c[1], 10);
                        break;
                    case "m":
                    case "M":
                        r += parseInt(c[1], 10),
                        o = Math.min(o, e.datepicker._getDaysInMonth(n, r));
                        break;
                    case "y":
                    case "Y":
                        n += parseInt(c[1], 10),
                        o = Math.min(o, e.datepicker._getDaysInMonth(n, r))
                    }
                    c = u.exec(i)
                }
                return new Date(n, r, o)
            },
            r = null == i || "" === i ? a: "string" == typeof i ? n(i) : "number" == typeof i ? isNaN(i) ? a: s(i) : new Date(i.getTime());
            return r = r && "Invalid Date" == "" + r ? a: r,
            r && (r.setHours(0), r.setMinutes(0), r.setSeconds(0), r.setMilliseconds(0)),
            this._daylightSavingAdjust(r)
        },
        _daylightSavingAdjust: function(e) {
            return e ? (e.setHours(e.getHours() > 12 ? e.getHours() + 2 : 0), e) : null
        },
        _setDate: function(e, t, i) {
            var a = !t,
            s = e.selectedMonth,
            n = e.selectedYear,
            r = this._restrictMinMax(e, this._determineDate(e, t, new Date));
            e.selectedDay = e.currentDay = r.getDate(),
            e.drawMonth = e.selectedMonth = e.currentMonth = r.getMonth(),
            e.drawYear = e.selectedYear = e.currentYear = r.getFullYear(),
            s === e.selectedMonth && n === e.selectedYear || i || this._notifyChange(e),
            this._adjustInstDate(e),
            e.input && e.input.val(a ? "": this._formatDate(e))
        },
        _getDate: function(e) {
            var t = !e.currentYear || e.input && "" === e.input.val() ? null: this._daylightSavingAdjust(new Date(e.currentYear, e.currentMonth, e.currentDay));
            return t
        },
        _attachHandlers: function(t) {
            var i = this._get(t, "stepMonths"),
            a = "#" + t.id.replace(/\\\\/g, "\\");
            t.dpDiv.find("[data-handler]").map(function() {
                var t = {
                    prev: function() {
                        e.datepicker._adjustDate(a, -i, "M")
                    },
                    next: function() {
                        e.datepicker._adjustDate(a, +i, "M")
                    },
                    hide: function() {
                        e.datepicker._hideDatepicker()
                    },
                    today: function() {
                        e.datepicker._gotoToday(a)
                    },
                    selectDay: function() {
                        return e.datepicker._selectDay(a, +this.getAttribute("data-month"), +this.getAttribute("data-year"), this),
                        !1
                    },
                    selectMonth: function() {
                        return e.datepicker._selectMonthYear(a, this, "M"),
                        !1
                    },
                    selectYear: function() {
                        return e.datepicker._selectMonthYear(a, this, "Y"),
                        !1
                    }
                };
                e(this).bind(this.getAttribute("data-event"), t[this.getAttribute("data-handler")])
            })
        },
        _generateHTML: function(e) {
            var t, i, a, s, n, r, o, u, c, l, h, d, p, g, m, f, _, v, k, b, y, D, w, M, C, x, I, N, T, A, E, S, Y, F, P, O, j, K, R, H = new Date,
            W = this._daylightSavingAdjust(new Date(H.getFullYear(), H.getMonth(), H.getDate())),
            L = this._get(e, "isRTL"),
            U = this._get(e, "showButtonPanel"),
            B = this._get(e, "hideIfNoPrevNext"),
            z = this._get(e, "navigationAsDateFormat"),
            q = this._getNumberOfMonths(e),
            G = this._get(e, "showCurrentAtPos"),
            J = this._get(e, "stepMonths"),
            Q = 1 !== q[0] || 1 !== q[1],
            V = this._daylightSavingAdjust(e.currentDay ? new Date(e.currentYear, e.currentMonth, e.currentDay) : new Date(9999, 9, 9)),
            $ = this._getMinMaxDate(e, "min"),
            X = this._getMinMaxDate(e, "max"),
            Z = e.drawMonth - G,
            et = e.drawYear;
            if (0 > Z && (Z += 12, et--), X) for (t = this._daylightSavingAdjust(new Date(X.getFullYear(), X.getMonth() - q[0] * q[1] + 1, X.getDate())), t = $ && $ > t ? $: t; this._daylightSavingAdjust(new Date(et, Z, 1)) > t;) Z--,
            0 > Z && (Z = 11, et--);
            for (e.drawMonth = Z, e.drawYear = et, i = this._get(e, "prevText"), i = z ? this.formatDate(i, this._daylightSavingAdjust(new Date(et, Z - J, 1)), this._getFormatConfig(e)) : i, a = this._canAdjustMonth(e, -1, et, Z) ? "<a class='ui-datepicker-prev ui-corner-all' data-handler='prev' data-event='click' title='" + i + "'><span class='ui-icon ui-icon-circle-triangle-" + (L ? "e": "w") + "'>" + i + "</span></a>": B ? "": "<a class='ui-datepicker-prev ui-corner-all ui-state-disabled' title='" + i + "'><span class='ui-icon ui-icon-circle-triangle-" + (L ? "e": "w") + "'>" + i + "</span></a>", s = this._get(e, "nextText"), s = z ? this.formatDate(s, this._daylightSavingAdjust(new Date(et, Z + J, 1)), this._getFormatConfig(e)) : s, n = this._canAdjustMonth(e, 1, et, Z) ? "<a class='ui-datepicker-next ui-corner-all' data-handler='next' data-event='click' title='" + s + "'><span class='ui-icon ui-icon-circle-triangle-" + (L ? "w": "e") + "'>" + s + "</span></a>": B ? "": "<a class='ui-datepicker-next ui-corner-all ui-state-disabled' title='" + s + "'><span class='ui-icon ui-icon-circle-triangle-" + (L ? "w": "e") + "'>" + s + "</span></a>", r = this._get(e, "currentText"), o = this._get(e, "gotoCurrent") && e.currentDay ? V: W, r = z ? this.formatDate(r, o, this._getFormatConfig(e)) : r, u = e.inline ? "": "<button type='button' class='ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all' data-handler='hide' data-event='click'>" + this._get(e, "closeText") + "</button>", c = U ? "<div class='ui-datepicker-buttonpane ui-widget-content'>" + (L ? u: "") + (this._isInRange(e, o) ? "<button type='button' class='ui-datepicker-current ui-state-default ui-priority-secondary ui-corner-all' data-handler='today' data-event='click'>" + r + "</button>": "") + (L ? "": u) + "</div>": "", l = parseInt(this._get(e, "firstDay"), 10), l = isNaN(l) ? 0 : l, h = this._get(e, "showWeek"), d = this._get(e, "dayNames"), p = this._get(e, "dayNamesMin"), g = this._get(e, "monthNames"), m = this._get(e, "monthNamesShort"), f = this._get(e, "beforeShowDay"), _ = this._get(e, "showOtherMonths"), v = this._get(e, "selectOtherMonths"), k = this._getDefaultDate(e), b = "", D = 0; q[0] > D; D++) {
                for (w = "", this.maxRows = 4, M = 0; q[1] > M; M++) {
                    if (C = this._daylightSavingAdjust(new Date(et, Z, e.selectedDay)), x = " ui-corner-all", I = "", Q) {
                        if (I += "<div class='ui-datepicker-group", q[1] > 1) switch (M) {
                        case 0:
                            I += " ui-datepicker-group-first",
                            x = " ui-corner-" + (L ? "right": "left");
                            break;
                        case q[1] - 1 : I += " ui-datepicker-group-last",
                            x = " ui-corner-" + (L ? "left": "right");
                            break;
                        default:
                            I += " ui-datepicker-group-middle",
                            x = ""
                        }
                        I += "'>"
                    }
                    for (I += "<div class='ui-datepicker-header ui-widget-header ui-helper-clearfix" + x + "'>" + (/all|left/.test(x) && 0 === D ? L ? n: a: "") + (/all|right/.test(x) && 0 === D ? L ? a: n: "") + this._generateMonthYearHeader(e, Z, et, $, X, D > 0 || M > 0, g, m) + "</div><table class='ui-datepicker-calendar'><thead><tr>", N = h ? "<th class='ui-datepicker-week-col'>" + this._get(e, "weekHeader") + "</th>": "", y = 0; 7 > y; y++) T = (y + l) % 7,
                    N += "<th" + ((y + l + 6) % 7 >= 5 ? " class='ui-datepicker-week-end'": "") + "><span title='" + d[T] + "'>" + p[T] + "</span></th>";
                    for (I += N + "</tr></thead><tbody>", A = this._getDaysInMonth(et, Z), et === e.selectedYear && Z === e.selectedMonth && (e.selectedDay = Math.min(e.selectedDay, A)), E = (this._getFirstDayOfMonth(et, Z) - l + 7) % 7, S = Math.ceil((E + A) / 7), Y = Q ? this.maxRows > S ? this.maxRows: S: S, this.maxRows = Y, F = this._daylightSavingAdjust(new Date(et, Z, 1 - E)), P = 0; Y > P; P++) {
                        for (I += "<tr>", O = h ? "<td class='ui-datepicker-week-col'>" + this._get(e, "calculateWeek")(F) + "</td>": "", y = 0; 7 > y; y++) j = f ? f.apply(e.input ? e.input[0] : null, [F]) : [!0, ""],
                        K = F.getMonth() !== Z,
                        R = K && !v || !j[0] || $ && $ > F || X && F > X,
                        O += "<td class='" + ((y + l + 6) % 7 >= 5 ? " ui-datepicker-week-end": "") + (K ? " ui-datepicker-other-month": "") + (F.getTime() === C.getTime() && Z === e.selectedMonth && e._keyEvent || k.getTime() === F.getTime() && k.getTime() === C.getTime() ? " " + this._dayOverClass: "") + (R ? " " + this._unselectableClass + " ui-state-disabled": "") + (K && !_ ? "": " " + j[1] + (F.getTime() === V.getTime() ? " " + this._currentClass: "") + (F.getTime() === W.getTime() ? " ui-datepicker-today": "")) + "'" + (K && !_ || !j[2] ? "": " title='" + j[2].replace(/'/g, "&#39;") + "'") + (R ? "": " data-handler='selectDay' data-event='click' data-month='" + F.getMonth() + "' data-year='" + F.getFullYear() + "'") + ">" + (K && !_ ? "&#xa0;": R ? "<span class='ui-state-default'>" + F.getDate() + "</span>": "<a class='ui-state-default" + (F.getTime() === W.getTime() ? " ui-state-highlight": "") + (F.getTime() === V.getTime() ? " ui-state-active": "") + (K ? " ui-priority-secondary": "") + "' href='#'>" + F.getDate() + "</a>") + "</td>",
                        F.setDate(F.getDate() + 1),
                        F = this._daylightSavingAdjust(F);
                        I += O + "</tr>"
                    }
                    Z++,
                    Z > 11 && (Z = 0, et++),
                    I += "</tbody></table>" + (Q ? "</div>" + (q[0] > 0 && M === q[1] - 1 ? "<div class='ui-datepicker-row-break'></div>": "") : ""),
                    w += I
                }
                b += w
            }
            return b += c,
            e._keyEvent = !1,
            b
        },
        _generateMonthYearHeader: function(e, t, i, a, s, n, r, o) {
            var u, c, l, h, d, p, g, m, f = this._get(e, "changeMonth"),
            _ = this._get(e, "changeYear"),
            v = this._get(e, "showMonthAfterYear"),
            k = "<div class='ui-datepicker-title'>",
            b = "";
            if (n || !f) b += "<span class='ui-datepicker-month'>" + r[t] + "</span>";
            else {
                for (u = a && a.getFullYear() === i, c = s && s.getFullYear() === i, b += "<select class='ui-datepicker-month' data-handler='selectMonth' data-event='change'>", l = 0; 12 > l; l++)(!u || l >= a.getMonth()) && (!c || s.getMonth() >= l) && (b += "<option value='" + l + "'" + (l === t ? " selected='selected'": "") + ">" + o[l] + "</option>");
                b += "</select>"
            }
            if (v || (k += b + (!n && f && _ ? "": "&#xa0;")), !e.yearshtml) if (e.yearshtml = "", n || !_) k += "<span class='ui-datepicker-year'>" + i + "</span>";
            else {
                for (h = this._get(e, "yearRange").split(":"), d = (new Date).getFullYear(), p = function(e) {
                    var t = e.match(/c[+\-].*/) ? i + parseInt(e.substring(1), 10) : e.match(/[+\-].*/) ? d + parseInt(e, 10) : parseInt(e, 10);
                    return isNaN(t) ? d: t
                },
                g = p(h[0]), m = Math.max(g, p(h[1] || "")), g = a ? Math.max(g, a.getFullYear()) : g, m = s ? Math.min(m, s.getFullYear()) : m, e.yearshtml += "<select class='ui-datepicker-year' data-handler='selectYear' data-event='change'>"; m >= g; g++) e.yearshtml += "<option value='" + g + "'" + (g === i ? " selected='selected'": "") + ">" + g + "</option>";
                e.yearshtml += "</select>",
                k += e.yearshtml,
                e.yearshtml = null
            }
            return k += this._get(e, "yearSuffix"),
            v && (k += (!n && f && _ ? "": "&#xa0;") + b),
            k += "</div>"
        },
        _adjustInstDate: function(e, t, i) {
            var a = e.drawYear + ("Y" === i ? t: 0),
            s = e.drawMonth + ("M" === i ? t: 0),
            n = Math.min(e.selectedDay, this._getDaysInMonth(a, s)) + ("D" === i ? t: 0),
            r = this._restrictMinMax(e, this._daylightSavingAdjust(new Date(a, s, n)));
            e.selectedDay = r.getDate(),
            e.drawMonth = e.selectedMonth = r.getMonth(),
            e.drawYear = e.selectedYear = r.getFullYear(),
            ("M" === i || "Y" === i) && this._notifyChange(e)
        },
        _restrictMinMax: function(e, t) {
            var i = this._getMinMaxDate(e, "min"),
            a = this._getMinMaxDate(e, "max"),
            s = i && i > t ? i: t;
            return a && s > a ? a: s
        },
        _notifyChange: function(e) {
            var t = this._get(e, "onChangeMonthYear");
            t && t.apply(e.input ? e.input[0] : null, [e.selectedYear, e.selectedMonth + 1, e])
        },
        _getNumberOfMonths: function(e) {
            var t = this._get(e, "numberOfMonths");
            return null == t ? [1, 1] : "number" == typeof t ? [1, t] : t
        },
        _getMinMaxDate: function(e, t) {
            return this._determineDate(e, this._get(e, t + "Date"), null)
        },
        _getDaysInMonth: function(e, t) {
            return 32 - this._daylightSavingAdjust(new Date(e, t, 32)).getDate()
        },
        _getFirstDayOfMonth: function(e, t) {
            return new Date(e, t, 1).getDay()
        },
        _canAdjustMonth: function(e, t, i, a) {
            var s = this._getNumberOfMonths(e),
            n = this._daylightSavingAdjust(new Date(i, a + (0 > t ? t: s[0] * s[1]), 1));
            return 0 > t && n.setDate(this._getDaysInMonth(n.getFullYear(), n.getMonth())),
            this._isInRange(e, n)
        },
        _isInRange: function(e, t) {
            var i, a, s = this._getMinMaxDate(e, "min"),
            n = this._getMinMaxDate(e, "max"),
            r = null,
            o = null,
            u = this._get(e, "yearRange");
            return u && (i = u.split(":"), a = (new Date).getFullYear(), r = parseInt(i[0], 10), o = parseInt(i[1], 10), i[0].match(/[+\-].*/) && (r += a), i[1].match(/[+\-].*/) && (o += a)),
            (!s || t.getTime() >= s.getTime()) && (!n || t.getTime() <= n.getTime()) && (!r || t.getFullYear() >= r) && (!o || o >= t.getFullYear())
        },
        _getFormatConfig: function(e) {
            var t = this._get(e, "shortYearCutoff");
            return t = "string" != typeof t ? t: (new Date).getFullYear() % 100 + parseInt(t, 10),
            {
                shortYearCutoff: t,
                dayNamesShort: this._get(e, "dayNamesShort"),
                dayNames: this._get(e, "dayNames"),
                monthNamesShort: this._get(e, "monthNamesShort"),
                monthNames: this._get(e, "monthNames")
            }
        },
        _formatDate: function(e, t, i, a) {
            t || (e.currentDay = e.selectedDay, e.currentMonth = e.selectedMonth, e.currentYear = e.selectedYear);
            var s = t ? "object" == typeof t ? t: this._daylightSavingAdjust(new Date(a, i, t)) : this._daylightSavingAdjust(new Date(e.currentYear, e.currentMonth, e.currentDay));
            return this.formatDate(this._get(e, "dateFormat"), s, this._getFormatConfig(e))
        }
    }),
    e.fn.datepicker = function(t) {
        if (!this.length) return this;
        e.datepicker.initialized || (e(document).mousedown(e.datepicker._checkExternalClick), e.datepicker.initialized = !0),
        0 === e("#" + e.datepicker._mainDivId).length && e("body").append(e.datepicker.dpDiv);
        var i = Array.prototype.slice.call(arguments, 1);
        return "string" != typeof t || "isDisabled" !== t && "getDate" !== t && "widget" !== t ? "option" === t && 2 === arguments.length && "string" == typeof arguments[1] ? e.datepicker["_" + t + "Datepicker"].apply(e.datepicker, [this[0]].concat(i)) : this.each(function() {
            "string" == typeof t ? e.datepicker["_" + t + "Datepicker"].apply(e.datepicker, [this].concat(i)) : e.datepicker._attachDatepicker(this, t)
        }) : e.datepicker["_" + t + "Datepicker"].apply(e.datepicker, [this[0]].concat(i))
    },
    e.datepicker = new i,
    e.datepicker.initialized = !1,
    e.datepicker.uuid = (new Date).getTime(),
    e.datepicker.version = "1.10.3"
} (jQuery),
function(e) {
    var t = {
        buttons: !0,
        height: !0,
        maxHeight: !0,
        maxWidth: !0,
        minHeight: !0,
        minWidth: !0,
        width: !0
    },
    i = {
        maxHeight: !0,
        maxWidth: !0,
        minHeight: !0,
        minWidth: !0
    };
    e.widget("ui.dialog", {
        version: "1.10.3",
        options: {
            appendTo: "body",
            autoOpen: !0,
            buttons: [],
            closeOnEscape: !0,
            closeText: "close",
            dialogClass: "",
            draggable: !0,
            hide: null,
            height: "auto",
            maxHeight: null,
            maxWidth: null,
            minHeight: 150,
            minWidth: 150,
            modal: !1,
            position: {
                my: "center",
                at: "center",
                of: window,
                collision: "fit",
                using: function(t) {
                    var i = e(this).css(t).offset().top;
                    0 > i && e(this).css("top", t.top - i)
                }
            },
            resizable: !0,
            show: null,
            title: null,
            width: 300,
            beforeClose: null,
            close: null,
            drag: null,
            dragStart: null,
            dragStop: null,
            focus: null,
            open: null,
            resize: null,
            resizeStart: null,
            resizeStop: null
        },
        _create: function() {
            this.originalCss = {
                display: this.element[0].style.display,
                width: this.element[0].style.width,
                minHeight: this.element[0].style.minHeight,
                maxHeight: this.element[0].style.maxHeight,
                height: this.element[0].style.height
            },
            this.originalPosition = {
                parent: this.element.parent(),
                index: this.element.parent().children().index(this.element)
            },
            this.originalTitle = this.element.attr("title"),
            this.options.title = this.options.title || this.originalTitle,
            this._createWrapper(),
            this.element.show().removeAttr("title").addClass("ui-dialog-content ui-widget-content").appendTo(this.uiDialog),
            this._createTitlebar(),
            this._createButtonPane(),
            this.options.draggable && e.fn.draggable && this._makeDraggable(),
            this.options.resizable && e.fn.resizable && this._makeResizable(),
            this._isOpen = !1
        },
        _init: function() {
            this.options.autoOpen && this.open()
        },
        _appendTo: function() {
            var t = this.options.appendTo;
            return t && (t.jquery || t.nodeType) ? e(t) : this.document.find(t || "body").eq(0)
        },
        _destroy: function() {
            var e, t = this.originalPosition;
            this._destroyOverlay(),
            this.element.removeUniqueId().removeClass("ui-dialog-content ui-widget-content").css(this.originalCss).detach(),
            this.uiDialog.stop(!0, !0).remove(),
            this.originalTitle && this.element.attr("title", this.originalTitle),
            e = t.parent.children().eq(t.index),
            e.length && e[0] !== this.element[0] ? e.before(this.element) : t.parent.append(this.element)
        },
        widget: function() {
            return this.uiDialog
        },
        disable: e.noop,
        enable: e.noop,
        close: function(t) {
            var i = this;
            this._isOpen && this._trigger("beforeClose", t) !== !1 && (this._isOpen = !1, this._destroyOverlay(), this.opener.filter(":focusable").focus().length || e(this.document[0].activeElement).blur(), this._hide(this.uiDialog, this.options.hide,
            function() {
                i._trigger("close", t)
            }))
        },
        isOpen: function() {
            return this._isOpen
        },
        moveToTop: function() {
            this._moveToTop()
        },
        _moveToTop: function(e, t) {
            var i = !!this.uiDialog.nextAll(":visible").insertBefore(this.uiDialog).length;
            return i && !t && this._trigger("focus", e),
            i
        },
        open: function() {
            var t = this;
            return this._isOpen ? (this._moveToTop() && this._focusTabbable(), void 0) : (this._isOpen = !0, this.opener = e(this.document[0].activeElement), this._size(), this._position(), this._createOverlay(), this._moveToTop(null, !0), this._show(this.uiDialog, this.options.show,
            function() {
                t._focusTabbable(),
                t._trigger("focus")
            }), this._trigger("open"), void 0)
        },
        _focusTabbable: function() {
            var e = this.element.find("[autofocus]");
            e.length || (e = this.element.find(":tabbable")),
            e.length || (e = this.uiDialogButtonPane.find(":tabbable")),
            e.length || (e = this.uiDialogTitlebarClose.filter(":tabbable")),
            e.length || (e = this.uiDialog),
            e.eq(0).focus()
        },
        _keepFocus: function(t) {
            function i() {
                var t = this.document[0].activeElement,
                i = this.uiDialog[0] === t || e.contains(this.uiDialog[0], t);
                i || this._focusTabbable()
            }
            t.preventDefault(),
            i.call(this),
            this._delay(i)
        },
        _createWrapper: function() {
            this.uiDialog = e("<div>").addClass("ui-dialog ui-widget ui-widget-content ui-corner-all ui-front " + this.options.dialogClass).hide().attr({
                tabIndex: -1,
                role: "dialog"
            }).appendTo(this._appendTo()),
            this._on(this.uiDialog, {
                keydown: function(t) {
                    if (this.options.closeOnEscape && !t.isDefaultPrevented() && t.keyCode && t.keyCode === e.ui.keyCode.ESCAPE) return t.preventDefault(),
                    this.close(t),
                    void 0;
                    if (t.keyCode === e.ui.keyCode.TAB) {
                        var i = this.uiDialog.find(":tabbable"),
                        a = i.filter(":first"),
                        s = i.filter(":last");
                        t.target !== s[0] && t.target !== this.uiDialog[0] || t.shiftKey ? t.target !== a[0] && t.target !== this.uiDialog[0] || !t.shiftKey || (s.focus(1), t.preventDefault()) : (a.focus(1), t.preventDefault())
                    }
                },
                mousedown: function(e) {
                    this._moveToTop(e) && this._focusTabbable()
                }
            }),
            this.element.find("[aria-describedby]").length || this.uiDialog.attr({
                "aria-describedby": this.element.uniqueId().attr("id")
            })
        },
        _createTitlebar: function() {
            var t;
            this.uiDialogTitlebar = e("<div>").addClass("ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix").prependTo(this.uiDialog),
            this._on(this.uiDialogTitlebar, {
                mousedown: function(t) {
                    e(t.target).closest(".ui-dialog-titlebar-close") || this.uiDialog.focus()
                }
            }),
            this.uiDialogTitlebarClose = e("<button></button>").button({
                label: this.options.closeText,
                icons: {
                    primary: "ui-icon-closethick"
                },
                text: !1
            }).addClass("ui-dialog-titlebar-close").appendTo(this.uiDialogTitlebar),
            this._on(this.uiDialogTitlebarClose, {
                click: function(e) {
                    e.preventDefault(),
                    this.close(e)
                }
            }),
            t = e("<span>").uniqueId().addClass("ui-dialog-title").prependTo(this.uiDialogTitlebar),
            this._title(t),
            this.uiDialog.attr({
                "aria-labelledby": t.attr("id")
            })
        },
        _title: function(e) {
            this.options.title || e.html("&#160;"),
            e.text(this.options.title)
        },
        _createButtonPane: function() {
            this.uiDialogButtonPane = e("<div>").addClass("ui-dialog-buttonpane ui-widget-content ui-helper-clearfix"),
            this.uiButtonSet = e("<div>").addClass("ui-dialog-buttonset").appendTo(this.uiDialogButtonPane),
            this._createButtons()
        },
        _createButtons: function() {
            var t = this,
            i = this.options.buttons;
            return this.uiDialogButtonPane.remove(),
            this.uiButtonSet.empty(),
            e.isEmptyObject(i) || e.isArray(i) && !i.length ? (this.uiDialog.removeClass("ui-dialog-buttons"), void 0) : (e.each(i,
            function(i, a) {
                var s, n;
                a = e.isFunction(a) ? {
                    click: a,
                    text: i
                }: a,
                a = e.extend({
                    type: "button"
                },
                a),
                s = a.click,
                a.click = function() {
                    s.apply(t.element[0], arguments)
                },
                n = {
                    icons: a.icons,
                    text: a.showText
                },
                delete a.icons,
                delete a.showText,
                e("<button></button>", a).button(n).appendTo(t.uiButtonSet)
            }), this.uiDialog.addClass("ui-dialog-buttons"), this.uiDialogButtonPane.appendTo(this.uiDialog), void 0)
        },
        _makeDraggable: function() {
            function t(e) {
                return {
                    position: e.position,
                    offset: e.offset
                }
            }
            var i = this,
            a = this.options;
            this.uiDialog.draggable({
                cancel: ".ui-dialog-content, .ui-dialog-titlebar-close",
                handle: ".ui-dialog-titlebar",
                containment: "document",
                start: function(a, s) {
                    e(this).addClass("ui-dialog-dragging"),
                    i._blockFrames(),
                    i._trigger("dragStart", a, t(s))
                },
                drag: function(e, a) {
                    i._trigger("drag", e, t(a))
                },
                stop: function(s, n) {
                    a.position = [n.position.left - i.document.scrollLeft(), n.position.top - i.document.scrollTop()],
                    e(this).removeClass("ui-dialog-dragging"),
                    i._unblockFrames(),
                    i._trigger("dragStop", s, t(n))
                }
            })
        },
        _makeResizable: function() {
            function t(e) {
                return {
                    originalPosition: e.originalPosition,
                    originalSize: e.originalSize,
                    position: e.position,
                    size: e.size
                }
            }
            var i = this,
            a = this.options,
            s = a.resizable,
            n = this.uiDialog.css("position"),
            r = "string" == typeof s ? s: "n,e,s,w,se,sw,ne,nw";
            this.uiDialog.resizable({
                cancel: ".ui-dialog-content",
                containment: "document",
                alsoResize: this.element,
                maxWidth: a.maxWidth,
                maxHeight: a.maxHeight,
                minWidth: a.minWidth,
                minHeight: this._minHeight(),
                handles: r,
                start: function(a, s) {
                    e(this).addClass("ui-dialog-resizing"),
                    i._blockFrames(),
                    i._trigger("resizeStart", a, t(s))
                },
                resize: function(e, a) {
                    i._trigger("resize", e, t(a))
                },
                stop: function(s, n) {
                    a.height = e(this).height(),
                    a.width = e(this).width(),
                    e(this).removeClass("ui-dialog-resizing"),
                    i._unblockFrames(),
                    i._trigger("resizeStop", s, t(n))
                }
            }).css("position", n)
        },
        _minHeight: function() {
            var e = this.options;
            return "auto" === e.height ? e.minHeight: Math.min(e.minHeight, e.height)
        },
        _position: function() {
            var e = this.uiDialog.is(":visible");
            e || this.uiDialog.show(),
            this.uiDialog.position(this.options.position),
            e || this.uiDialog.hide()
        },
        _setOptions: function(a) {
            var s = this,
            n = !1,
            r = {};
            e.each(a,
            function(e, a) {
                s._setOption(e, a),
                e in t && (n = !0),
                e in i && (r[e] = a)
            }),
            n && (this._size(), this._position()),
            this.uiDialog.is(":data(ui-resizable)") && this.uiDialog.resizable("option", r)
        },
        _setOption: function(e, t) {
            var i, a, s = this.uiDialog;
            "dialogClass" === e && s.removeClass(this.options.dialogClass).addClass(t),
            "disabled" !== e && (this._super(e, t), "appendTo" === e && this.uiDialog.appendTo(this._appendTo()), "buttons" === e && this._createButtons(), "closeText" === e && this.uiDialogTitlebarClose.button({
                label: "" + t
            }), "draggable" === e && (i = s.is(":data(ui-draggable)"), i && !t && s.draggable("destroy"), !i && t && this._makeDraggable()), "position" === e && this._position(), "resizable" === e && (a = s.is(":data(ui-resizable)"), a && !t && s.resizable("destroy"), a && "string" == typeof t && s.resizable("option", "handles", t), a || t === !1 || this._makeResizable()), "title" === e && this._title(this.uiDialogTitlebar.find(".ui-dialog-title")))
        },
        _size: function() {
            var e, t, i, a = this.options;
            this.element.show().css({
                width: "auto",
                minHeight: 0,
                maxHeight: "none",
                height: 0
            }),
            a.minWidth > a.width && (a.width = a.minWidth),
            e = this.uiDialog.css({
                height: "auto",
                width: a.width
            }).outerHeight(),
            t = Math.max(0, a.minHeight - e),
            i = "number" == typeof a.maxHeight ? Math.max(0, a.maxHeight - e) : "none",
            "auto" === a.height ? this.element.css({
                minHeight: t,
                maxHeight: i,
                height: "auto"
            }) : this.element.height(Math.max(0, a.height - e)),
            this.uiDialog.is(":data(ui-resizable)") && this.uiDialog.resizable("option", "minHeight", this._minHeight())
        },
        _blockFrames: function() {
            this.iframeBlocks = this.document.find("iframe").map(function() {
                var t = e(this);
                return e("<div>").css({
                    position: "absolute",
                    width: t.outerWidth(),
                    height: t.outerHeight()
                }).appendTo(t.parent()).offset(t.offset())[0]
            })
        },
        _unblockFrames: function() {
            this.iframeBlocks && (this.iframeBlocks.remove(), delete this.iframeBlocks)
        },
        _allowInteraction: function(t) {
            return e(t.target).closest(".ui-dialog").length ? !0 : !!e(t.target).closest(".ui-datepicker").length
        },
        _createOverlay: function() {
            if (this.options.modal) {
                var t = this,
                i = this.widgetFullName;
                e.ui.dialog.overlayInstances || this._delay(function() {
                    e.ui.dialog.overlayInstances && this.document.bind("focusin.dialog",
                    function(a) {
                        t._allowInteraction(a) || (a.preventDefault(), e(".ui-dialog:visible:last .ui-dialog-content").data(i)._focusTabbable())
                    })
                }),
                this.overlay = e("<div>").addClass("ui-widget-overlay ui-front").appendTo(this._appendTo()),
                this._on(this.overlay, {
                    mousedown: "_keepFocus"
                }),
                e.ui.dialog.overlayInstances++
            }
        },
        _destroyOverlay: function() {
            this.options.modal && this.overlay && (e.ui.dialog.overlayInstances--, e.ui.dialog.overlayInstances || this.document.unbind("focusin.dialog"), this.overlay.remove(), this.overlay = null)
        }
    }),
    e.ui.dialog.overlayInstances = 0,
    e.uiBackCompat !== !1 && e.widget("ui.dialog", e.ui.dialog, {
        _position: function() {
            var t, i = this.options.position,
            a = [],
            s = [0, 0];
            i ? (("string" == typeof i || "object" == typeof i && "0" in i) && (a = i.split ? i.split(" ") : [i[0], i[1]], 1 === a.length && (a[1] = a[0]), e.each(["left", "top"],
            function(e, t) { + a[e] === a[e] && (s[e] = a[e], a[e] = t)
            }), i = {
                my: a[0] + (0 > s[0] ? s[0] : "+" + s[0]) + " " + a[1] + (0 > s[1] ? s[1] : "+" + s[1]),
                at: a.join(" ")
            }), i = e.extend({},
            e.ui.dialog.prototype.options.position, i)) : i = e.ui.dialog.prototype.options.position,
            t = this.uiDialog.is(":visible"),
            t || this.uiDialog.show(),
            this.uiDialog.position(i),
            t || this.uiDialog.hide()
        }
    })
} (jQuery),
function(t) {
    t.widget("ui.menu", {
        version: "1.10.3",
        defaultElement: "<ul>",
        delay: 300,
        options: {
            icons: {
                submenu: "ui-icon-carat-1-e"
            },
            menus: "ul",
            position: {
                my: "left top",
                at: "right top"
            },
            role: "menu",
            blur: null,
            focus: null,
            select: null
        },
        _create: function() {
            this.activeMenu = this.element,
            this.mouseHandled = !1,
            this.element.uniqueId().addClass("ui-menu ui-widget ui-widget-content ui-corner-all").toggleClass("ui-menu-icons", !!this.element.find(".ui-icon").length).attr({
                role: this.options.role,
                tabIndex: 0
            }).bind("click" + this.eventNamespace, t.proxy(function(t) {
                this.options.disabled && t.preventDefault()
            },
            this)),
            this.options.disabled && this.element.addClass("ui-state-disabled").attr("aria-disabled", "true"),
            this._on({
                "mousedown .ui-menu-item > a": function(t) {
                    t.preventDefault()
                },
                "click .ui-state-disabled > a": function(t) {
                    t.preventDefault()
                },
                "click .ui-menu-item:has(a)": function(e) {
                    var i = t(e.target).closest(".ui-menu-item"); ! this.mouseHandled && i.not(".ui-state-disabled").length && (this.mouseHandled = !0, this.select(e), i.has(".ui-menu").length ? this.expand(e) : this.element.is(":focus") || (this.element.trigger("focus", [!0]), this.active && 1 === this.active.parents(".ui-menu").length && clearTimeout(this.timer)))
                },
                "mouseenter .ui-menu-item": function(e) {
                    var i = t(e.currentTarget);
                    i.siblings().children(".ui-state-active").removeClass("ui-state-active"),
                    this.focus(e, i)
                },
                mouseleave: "collapseAll",
                "mouseleave .ui-menu": "collapseAll",
                focus: function(t, e) {
                    var i = this.active || this.element.children(".ui-menu-item").eq(0);
                    e || this.focus(t, i)
                },
                blur: function(e) {
                    this._delay(function() {
                        t.contains(this.element[0], this.document[0].activeElement) || this.collapseAll(e)
                    })
                },
                keydown: "_keydown"
            }),
            this.refresh(),
            this._on(this.document, {
                click: function(e) {
                    t(e.target).closest(".ui-menu").length || this.collapseAll(e),
                    this.mouseHandled = !1
                }
            })
        },
        _destroy: function() {
            this.element.removeAttr("aria-activedescendant").find(".ui-menu").addBack().removeClass("ui-menu ui-widget ui-widget-content ui-corner-all ui-menu-icons").removeAttr("role").removeAttr("tabIndex").removeAttr("aria-labelledby").removeAttr("aria-expanded").removeAttr("aria-hidden").removeAttr("aria-disabled").removeUniqueId().show(),
            this.element.find(".ui-menu-item").removeClass("ui-menu-item").removeAttr("role").removeAttr("aria-disabled").children("a").removeUniqueId().removeClass("ui-corner-all ui-state-hover").removeAttr("tabIndex").removeAttr("role").removeAttr("aria-haspopup").children().each(function() {
                var e = t(this);
                e.data("ui-menu-submenu-carat") && e.remove()
            }),
            this.element.find(".ui-menu-divider").removeClass("ui-menu-divider ui-widget-content")
        },
        _keydown: function(e) {
            function i(t) {
                return t.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, "\\$&")
            }
            var s, n, a, o, r, l = !0;
            switch (e.keyCode) {
            case t.ui.keyCode.PAGE_UP:
                this.previousPage(e);
                break;
            case t.ui.keyCode.PAGE_DOWN:
                this.nextPage(e);
                break;
            case t.ui.keyCode.HOME:
                this._move("first", "first", e);
                break;
            case t.ui.keyCode.END:
                this._move("last", "last", e);
                break;
            case t.ui.keyCode.UP:
                this.previous(e);
                break;
            case t.ui.keyCode.DOWN:
                this.next(e);
                break;
            case t.ui.keyCode.LEFT:
                this.collapse(e);
                break;
            case t.ui.keyCode.RIGHT:
                this.active && !this.active.is(".ui-state-disabled") && this.expand(e);
                break;
            case t.ui.keyCode.ENTER:
            case t.ui.keyCode.SPACE:
                this._activate(e);
                break;
            case t.ui.keyCode.ESCAPE:
                this.collapse(e);
                break;
            default:
                l = !1,
                n = this.previousFilter || "",
                a = String.fromCharCode(e.keyCode),
                o = !1,
                clearTimeout(this.filterTimer),
                a === n ? o = !0 : a = n + a,
                r = RegExp("^" + i(a), "i"),
                s = this.activeMenu.children(".ui-menu-item").filter(function() {
                    return r.test(t(this).children("a").text())
                }),
                s = o && -1 !== s.index(this.active.next()) ? this.active.nextAll(".ui-menu-item") : s,
                s.length || (a = String.fromCharCode(e.keyCode), r = RegExp("^" + i(a), "i"), s = this.activeMenu.children(".ui-menu-item").filter(function() {
                    return r.test(t(this).children("a").text())
                })),
                s.length ? (this.focus(e, s), s.length > 1 ? (this.previousFilter = a, this.filterTimer = this._delay(function() {
                    delete this.previousFilter
                },
                1e3)) : delete this.previousFilter) : delete this.previousFilter
            }
            l && e.preventDefault()
        },
        _activate: function(t) {
            this.active.is(".ui-state-disabled") || (this.active.children("a[aria-haspopup='true']").length ? this.expand(t) : this.select(t))
        },
        refresh: function() {
            var e, i = this.options.icons.submenu,
            s = this.element.find(this.options.menus);
            s.filter(":not(.ui-menu)").addClass("ui-menu ui-widget ui-widget-content ui-corner-all").hide().attr({
                role: this.options.role,
                "aria-hidden": "true",
                "aria-expanded": "false"
            }).each(function() {
                var e = t(this),
                s = e.prev("a"),
                n = t("<span>").addClass("ui-menu-icon ui-icon " + i).data("ui-menu-submenu-carat", !0);
                s.attr("aria-haspopup", "true").prepend(n),
                e.attr("aria-labelledby", s.attr("id"))
            }),
            e = s.add(this.element),
            e.children(":not(.ui-menu-item):has(a)").addClass("ui-menu-item").attr("role", "presentation").children("a").uniqueId().addClass("ui-corner-all").attr({
                tabIndex: -1,
                role: this._itemRole()
            }),
            e.children(":not(.ui-menu-item)").each(function() {
                var e = t(this);
                /[^\-\u2014\u2013\s]/.test(e.text()) || e.addClass("ui-widget-content ui-menu-divider")
            }),
            e.children(".ui-state-disabled").attr("aria-disabled", "true"),
            this.active && !t.contains(this.element[0], this.active[0]) && this.blur()
        },
        _itemRole: function() {
            return {
                menu: "menuitem",
                listbox: "option"
            } [this.options.role]
        },
        _setOption: function(t, e) {
            "icons" === t && this.element.find(".ui-menu-icon").removeClass(this.options.icons.submenu).addClass(e.submenu),
            this._super(t, e)
        },
        focus: function(t, e) {
            var i, s;
            this.blur(t, t && "focus" === t.type),
            this._scrollIntoView(e),
            this.active = e.first(),
            s = this.active.children("a").addClass("ui-state-focus"),
            this.options.role && this.element.attr("aria-activedescendant", s.attr("id")),
            this.active.parent().closest(".ui-menu-item").children("a:first").addClass("ui-state-active"),
            t && "keydown" === t.type ? this._close() : this.timer = this._delay(function() {
                this._close()
            },
            this.delay),
            i = e.children(".ui-menu"),
            i.length && /^mouse/.test(t.type) && this._startOpening(i),
            this.activeMenu = e.parent(),
            this._trigger("focus", t, {
                item: e
            })
        },
        _scrollIntoView: function(e) {
            var i, s, n, a, o, r;
            this._hasScroll() && (i = parseFloat(t.css(this.activeMenu[0], "borderTopWidth")) || 0, s = parseFloat(t.css(this.activeMenu[0], "paddingTop")) || 0, n = e.offset().top - this.activeMenu.offset().top - i - s, a = this.activeMenu.scrollTop(), o = this.activeMenu.height(), r = e.height(), 0 > n ? this.activeMenu.scrollTop(a + n) : n + r > o && this.activeMenu.scrollTop(a + n - o + r))
        },
        blur: function(t, e) {
            e || clearTimeout(this.timer),
            this.active && (this.active.children("a").removeClass("ui-state-focus"), this.active = null, this._trigger("blur", t, {
                item: this.active
            }))
        },
        _startOpening: function(t) {
            clearTimeout(this.timer),
            "true" === t.attr("aria-hidden") && (this.timer = this._delay(function() {
                this._close(),
                this._open(t)
            },
            this.delay))
        },
        _open: function(e) {
            var i = t.extend({
                of: this.active
            },
            this.options.position);
            clearTimeout(this.timer),
            this.element.find(".ui-menu").not(e.parents(".ui-menu")).hide().attr("aria-hidden", "true"),
            e.show().removeAttr("aria-hidden").attr("aria-expanded", "true").position(i)
        },
        collapseAll: function(e, i) {
            clearTimeout(this.timer),
            this.timer = this._delay(function() {
                var s = i ? this.element: t(e && e.target).closest(this.element.find(".ui-menu"));
                s.length || (s = this.element),
                this._close(s),
                this.blur(e),
                this.activeMenu = s
            },
            this.delay)
        },
        _close: function(t) {
            t || (t = this.active ? this.active.parent() : this.element),
            t.find(".ui-menu").hide().attr("aria-hidden", "true").attr("aria-expanded", "false").end().find("a.ui-state-active").removeClass("ui-state-active")
        },
        collapse: function(t) {
            var e = this.active && this.active.parent().closest(".ui-menu-item", this.element);
            e && e.length && (this._close(), this.focus(t, e))
        },
        expand: function(t) {
            var e = this.active && this.active.children(".ui-menu ").children(".ui-menu-item").first();
            e && e.length && (this._open(e.parent()), this._delay(function() {
                this.focus(t, e)
            }))
        },
        next: function(t) {
            this._move("next", "first", t)
        },
        previous: function(t) {
            this._move("prev", "last", t)
        },
        isFirstItem: function() {
            return this.active && !this.active.prevAll(".ui-menu-item").length
        },
        isLastItem: function() {
            return this.active && !this.active.nextAll(".ui-menu-item").length
        },
        _move: function(t, e, i) {
            var s;
            this.active && (s = "first" === t || "last" === t ? this.active["first" === t ? "prevAll": "nextAll"](".ui-menu-item").eq( - 1) : this.active[t + "All"](".ui-menu-item").eq(0)),
            s && s.length && this.active || (s = this.activeMenu.children(".ui-menu-item")[e]()),
            this.focus(i, s)
        },
        nextPage: function(e) {
            var i, s, n;
            return this.active ? (this.isLastItem() || (this._hasScroll() ? (s = this.active.offset().top, n = this.element.height(), this.active.nextAll(".ui-menu-item").each(function() {
                return i = t(this),
                0 > i.offset().top - s - n
            }), this.focus(e, i)) : this.focus(e, this.activeMenu.children(".ui-menu-item")[this.active ? "last": "first"]())), void 0) : (this.next(e), void 0)
        },
        previousPage: function(e) {
            var i, s, n;
            return this.active ? (this.isFirstItem() || (this._hasScroll() ? (s = this.active.offset().top, n = this.element.height(), this.active.prevAll(".ui-menu-item").each(function() {
                return i = t(this),
                i.offset().top - s + n > 0
            }), this.focus(e, i)) : this.focus(e, this.activeMenu.children(".ui-menu-item").first())), void 0) : (this.next(e), void 0)
        },
        _hasScroll: function() {
            return this.element.outerHeight() < this.element.prop("scrollHeight")
        },
        select: function(e) {
            this.active = this.active || t(e.target).closest(".ui-menu-item");
            var i = {
                item: this.active
            };
            this.active.has(".ui-menu").length || this.collapseAll(e, !0),
            this._trigger("select", e, i)
        }
    })
} (jQuery),
function(t, e) {
    t.widget("ui.progressbar", {
        version: "1.10.3",
        options: {
            max: 100,
            value: 0,
            change: null,
            complete: null
        },
        min: 0,
        _create: function() {
            this.oldValue = this.options.value = this._constrainedValue(),
            this.element.addClass("ui-progressbar ui-widget ui-widget-content ui-corner-all").attr({
                role: "progressbar",
                "aria-valuemin": this.min
            }),
            this.valueDiv = t("<div class='ui-progressbar-value ui-widget-header ui-corner-left'></div>").appendTo(this.element),
            this._refreshValue()
        },
        _destroy: function() {
            this.element.removeClass("ui-progressbar ui-widget ui-widget-content ui-corner-all").removeAttr("role").removeAttr("aria-valuemin").removeAttr("aria-valuemax").removeAttr("aria-valuenow"),
            this.valueDiv.remove()
        },
        value: function(t) {
            return t === e ? this.options.value: (this.options.value = this._constrainedValue(t), this._refreshValue(), e)
        },
        _constrainedValue: function(t) {
            return t === e && (t = this.options.value),
            this.indeterminate = t === !1,
            "number" != typeof t && (t = 0),
            this.indeterminate ? !1 : Math.min(this.options.max, Math.max(this.min, t))
        },
        _setOptions: function(t) {
            var e = t.value;
            delete t.value,
            this._super(t),
            this.options.value = this._constrainedValue(e),
            this._refreshValue()
        },
        _setOption: function(t, e) {
            "max" === t && (e = Math.max(this.min, e)),
            this._super(t, e)
        },
        _percentage: function() {
            return this.indeterminate ? 100 : 100 * (this.options.value - this.min) / (this.options.max - this.min)
        },
        _refreshValue: function() {
            var e = this.options.value,
            i = this._percentage();
            this.valueDiv.toggle(this.indeterminate || e > this.min).toggleClass("ui-corner-right", e === this.options.max).width(i.toFixed(0) + "%"),
            this.element.toggleClass("ui-progressbar-indeterminate", this.indeterminate),
            this.indeterminate ? (this.element.removeAttr("aria-valuenow"), this.overlayDiv || (this.overlayDiv = t("<div class='ui-progressbar-overlay'></div>").appendTo(this.valueDiv))) : (this.element.attr({
                "aria-valuemax": this.options.max,
                "aria-valuenow": e
            }), this.overlayDiv && (this.overlayDiv.remove(), this.overlayDiv = null)),
            this.oldValue !== e && (this.oldValue = e, this._trigger("change")),
            e === this.options.max && this._trigger("complete")
        }
    })
} (jQuery),
function(t) {
    var e = 5;
    t.widget("ui.slider", t.ui.mouse, {
        version: "1.10.3",
        widgetEventPrefix: "slide",
        options: {
            animate: !1,
            distance: 0,
            max: 100,
            min: 0,
            orientation: "horizontal",
            range: !1,
            step: 1,
            value: 0,
            values: null,
            change: null,
            slide: null,
            start: null,
            stop: null
        },
        _create: function() {
            this._keySliding = !1,
            this._mouseSliding = !1,
            this._animateOff = !0,
            this._handleIndex = null,
            this._detectOrientation(),
            this._mouseInit(),
            this.element.addClass("ui-slider ui-slider-" + this.orientation + " ui-widget ui-widget-content ui-corner-all"),
            this._refresh(),
            this._setOption("disabled", this.options.disabled),
            this._animateOff = !1
        },
        _refresh: function() {
            this._createRange(),
            this._createHandles(),
            this._setupEvents(),
            this._refreshValue()
        },
        _createHandles: function() {
            var e, i, s = this.options,
            n = this.element.find(".ui-slider-handle").addClass("ui-state-default ui-corner-all"),
            a = "<a class='ui-slider-handle ui-state-default ui-corner-all' href='#'></a>",
            o = [];
            for (i = s.values && s.values.length || 1, n.length > i && (n.slice(i).remove(), n = n.slice(0, i)), e = n.length; i > e; e++) o.push(a);
            this.handles = n.add(t(o.join("")).appendTo(this.element)),
            this.handle = this.handles.eq(0),
            this.handles.each(function(e) {
                t(this).data("ui-slider-handle-index", e)
            })
        },
        _createRange: function() {
            var e = this.options,
            i = "";
            e.range ? (e.range === !0 && (e.values ? e.values.length && 2 !== e.values.length ? e.values = [e.values[0], e.values[0]] : t.isArray(e.values) && (e.values = e.values.slice(0)) : e.values = [this._valueMin(), this._valueMin()]), this.range && this.range.length ? this.range.removeClass("ui-slider-range-min ui-slider-range-max").css({
                left: "",
                bottom: ""
            }) : (this.range = t("<div></div>").appendTo(this.element), i = "ui-slider-range ui-widget-header ui-corner-all"), this.range.addClass(i + ("min" === e.range || "max" === e.range ? " ui-slider-range-" + e.range: ""))) : this.range = t([])
        },
        _setupEvents: function() {
            var t = this.handles.add(this.range).filter("a");
            this._off(t),
            this._on(t, this._handleEvents),
            this._hoverable(t),
            this._focusable(t)
        },
        _destroy: function() {
            this.handles.remove(),
            this.range.remove(),
            this.element.removeClass("ui-slider ui-slider-horizontal ui-slider-vertical ui-widget ui-widget-content ui-corner-all"),
            this._mouseDestroy()
        },
        _mouseCapture: function(e) {
            var i, s, n, a, o, r, l, h, u = this,
            c = this.options;
            return c.disabled ? !1 : (this.elementSize = {
                width: this.element.outerWidth(),
                height: this.element.outerHeight()
            },
            this.elementOffset = this.element.offset(), i = {
                x: e.pageX,
                y: e.pageY
            },
            s = this._normValueFromMouse(i), n = this._valueMax() - this._valueMin() + 1, this.handles.each(function(e) {
                var i = Math.abs(s - u.values(e)); (n > i || n === i && (e === u._lastChangedValue || u.values(e) === c.min)) && (n = i, a = t(this), o = e)
            }), r = this._start(e, o), r === !1 ? !1 : (this._mouseSliding = !0, this._handleIndex = o, a.addClass("ui-state-active").focus(), l = a.offset(), h = !t(e.target).parents().addBack().is(".ui-slider-handle"), this._clickOffset = h ? {
                left: 0,
                top: 0
            }: {
                left: e.pageX - l.left - a.width() / 2,
                top: e.pageY - l.top - a.height() / 2 - (parseInt(a.css("borderTopWidth"), 10) || 0) - (parseInt(a.css("borderBottomWidth"), 10) || 0) + (parseInt(a.css("marginTop"), 10) || 0)
            },
            this.handles.hasClass("ui-state-hover") || this._slide(e, o, s), this._animateOff = !0, !0))
        },
        _mouseStart: function() {
            return ! 0
        },
        _mouseDrag: function(t) {
            var e = {
                x: t.pageX,
                y: t.pageY
            },
            i = this._normValueFromMouse(e);
            return this._slide(t, this._handleIndex, i),
            !1
        },
        _mouseStop: function(t) {
            return this.handles.removeClass("ui-state-active"),
            this._mouseSliding = !1,
            this._stop(t, this._handleIndex),
            this._change(t, this._handleIndex),
            this._handleIndex = null,
            this._clickOffset = null,
            this._animateOff = !1,
            !1
        },
        _detectOrientation: function() {
            this.orientation = "vertical" === this.options.orientation ? "vertical": "horizontal"
        },
        _normValueFromMouse: function(t) {
            var e, i, s, n, a;
            return "horizontal" === this.orientation ? (e = this.elementSize.width, i = t.x - this.elementOffset.left - (this._clickOffset ? this._clickOffset.left: 0)) : (e = this.elementSize.height, i = t.y - this.elementOffset.top - (this._clickOffset ? this._clickOffset.top: 0)),
            s = i / e,
            s > 1 && (s = 1),
            0 > s && (s = 0),
            "vertical" === this.orientation && (s = 1 - s),
            n = this._valueMax() - this._valueMin(),
            a = this._valueMin() + s * n,
            this._trimAlignValue(a)
        },
        _start: function(t, e) {
            var i = {
                handle: this.handles[e],
                value: this.value()
            };
            return this.options.values && this.options.values.length && (i.value = this.values(e), i.values = this.values()),
            this._trigger("start", t, i)
        },
        _slide: function(t, e, i) {
            var s, n, a;
            this.options.values && this.options.values.length ? (s = this.values(e ? 0 : 1), 2 === this.options.values.length && this.options.range === !0 && (0 === e && i > s || 1 === e && s > i) && (i = s), i !== this.values(e) && (n = this.values(), n[e] = i, a = this._trigger("slide", t, {
                handle: this.handles[e],
                value: i,
                values: n
            }), s = this.values(e ? 0 : 1), a !== !1 && this.values(e, i, !0))) : i !== this.value() && (a = this._trigger("slide", t, {
                handle: this.handles[e],
                value: i
            }), a !== !1 && this.value(i))
        },
        _stop: function(t, e) {
            var i = {
                handle: this.handles[e],
                value: this.value()
            };
            this.options.values && this.options.values.length && (i.value = this.values(e), i.values = this.values()),
            this._trigger("stop", t, i)
        },
        _change: function(t, e) {
            if (!this._keySliding && !this._mouseSliding) {
                var i = {
                    handle: this.handles[e],
                    value: this.value()
                };
                this.options.values && this.options.values.length && (i.value = this.values(e), i.values = this.values()),
                this._lastChangedValue = e,
                this._trigger("change", t, i)
            }
        },
        value: function(t) {
            return arguments.length ? (this.options.value = this._trimAlignValue(t), this._refreshValue(), this._change(null, 0), void 0) : this._value()
        },
        values: function(e, i) {
            var s, n, a;
            if (arguments.length > 1) return this.options.values[e] = this._trimAlignValue(i),
            this._refreshValue(),
            this._change(null, e),
            void 0;
            if (!arguments.length) return this._values();
            if (!t.isArray(arguments[0])) return this.options.values && this.options.values.length ? this._values(e) : this.value();
            for (s = this.options.values, n = arguments[0], a = 0; s.length > a; a += 1) s[a] = this._trimAlignValue(n[a]),
            this._change(null, a);
            this._refreshValue()
        },
        _setOption: function(e, i) {
            var s, n = 0;
            switch ("range" === e && this.options.range === !0 && ("min" === i ? (this.options.value = this._values(0), this.options.values = null) : "max" === i && (this.options.value = this._values(this.options.values.length - 1), this.options.values = null)), t.isArray(this.options.values) && (n = this.options.values.length), t.Widget.prototype._setOption.apply(this, arguments), e) {
            case "orientation":
                this._detectOrientation(),
                this.element.removeClass("ui-slider-horizontal ui-slider-vertical").addClass("ui-slider-" + this.orientation),
                this._refreshValue();
                break;
            case "value":
                this._animateOff = !0,
                this._refreshValue(),
                this._change(null, 0),
                this._animateOff = !1;
                break;
            case "values":
                for (this._animateOff = !0, this._refreshValue(), s = 0; n > s; s += 1) this._change(null, s);
                this._animateOff = !1;
                break;
            case "min":
            case "max":
                this._animateOff = !0,
                this._refreshValue(),
                this._animateOff = !1;
                break;
            case "range":
                this._animateOff = !0,
                this._refresh(),
                this._animateOff = !1
            }
        },
        _value: function() {
            var t = this.options.value;
            return t = this._trimAlignValue(t)
        },
        _values: function(t) {
            var e, i, s;
            if (arguments.length) return e = this.options.values[t],
            e = this._trimAlignValue(e);
            if (this.options.values && this.options.values.length) {
                for (i = this.options.values.slice(), s = 0; i.length > s; s += 1) i[s] = this._trimAlignValue(i[s]);
                return i
            }
            return []
        },
        _trimAlignValue: function(t) {
            if (this._valueMin() >= t) return this._valueMin();
            if (t >= this._valueMax()) return this._valueMax();
            var e = this.options.step > 0 ? this.options.step: 1,
            i = (t - this._valueMin()) % e,
            s = t - i;
            return 2 * Math.abs(i) >= e && (s += i > 0 ? e: -e),
            parseFloat(s.toFixed(5))
        },
        _valueMin: function() {
            return this.options.min
        },
        _valueMax: function() {
            return this.options.max
        },
        _refreshValue: function() {
            var e, i, s, n, a, o = this.options.range,
            r = this.options,
            l = this,
            h = this._animateOff ? !1 : r.animate,
            u = {};
            this.options.values && this.options.values.length ? this.handles.each(function(s) {
                i = 100 * ((l.values(s) - l._valueMin()) / (l._valueMax() - l._valueMin())),
                u["horizontal" === l.orientation ? "left": "bottom"] = i + "%",
                t(this).stop(1, 1)[h ? "animate": "css"](u, r.animate),
                l.options.range === !0 && ("horizontal" === l.orientation ? (0 === s && l.range.stop(1, 1)[h ? "animate": "css"]({
                    left: i + "%"
                },
                r.animate), 1 === s && l.range[h ? "animate": "css"]({
                    width: i - e + "%"
                },
                {
                    queue: !1,
                    duration: r.animate
                })) : (0 === s && l.range.stop(1, 1)[h ? "animate": "css"]({
                    bottom: i + "%"
                },
                r.animate), 1 === s && l.range[h ? "animate": "css"]({
                    height: i - e + "%"
                },
                {
                    queue: !1,
                    duration: r.animate
                }))),
                e = i
            }) : (s = this.value(), n = this._valueMin(), a = this._valueMax(), i = a !== n ? 100 * ((s - n) / (a - n)) : 0, u["horizontal" === this.orientation ? "left": "bottom"] = i + "%", this.handle.stop(1, 1)[h ? "animate": "css"](u, r.animate), "min" === o && "horizontal" === this.orientation && this.range.stop(1, 1)[h ? "animate": "css"]({
                width: i + "%"
            },
            r.animate), "max" === o && "horizontal" === this.orientation && this.range[h ? "animate": "css"]({
                width: 100 - i + "%"
            },
            {
                queue: !1,
                duration: r.animate
            }), "min" === o && "vertical" === this.orientation && this.range.stop(1, 1)[h ? "animate": "css"]({
                height: i + "%"
            },
            r.animate), "max" === o && "vertical" === this.orientation && this.range[h ? "animate": "css"]({
                height: 100 - i + "%"
            },
            {
                queue: !1,
                duration: r.animate
            }))
        },
        _handleEvents: {
            keydown: function(i) {
                var s, n, a, o, r = t(i.target).data("ui-slider-handle-index");
                switch (i.keyCode) {
                case t.ui.keyCode.HOME:
                case t.ui.keyCode.END:
                case t.ui.keyCode.PAGE_UP:
                case t.ui.keyCode.PAGE_DOWN:
                case t.ui.keyCode.UP:
                case t.ui.keyCode.RIGHT:
                case t.ui.keyCode.DOWN:
                case t.ui.keyCode.LEFT:
                    if (i.preventDefault(), !this._keySliding && (this._keySliding = !0, t(i.target).addClass("ui-state-active"), s = this._start(i, r), s === !1)) return
                }
                switch (o = this.options.step, n = a = this.options.values && this.options.values.length ? this.values(r) : this.value(), i.keyCode) {
                case t.ui.keyCode.HOME:
                    a = this._valueMin();
                    break;
                case t.ui.keyCode.END:
                    a = this._valueMax();
                    break;
                case t.ui.keyCode.PAGE_UP:
                    a = this._trimAlignValue(n + (this._valueMax() - this._valueMin()) / e);
                    break;
                case t.ui.keyCode.PAGE_DOWN:
                    a = this._trimAlignValue(n - (this._valueMax() - this._valueMin()) / e);
                    break;
                case t.ui.keyCode.UP:
                case t.ui.keyCode.RIGHT:
                    if (n === this._valueMax()) return;
                    a = this._trimAlignValue(n + o);
                    break;
                case t.ui.keyCode.DOWN:
                case t.ui.keyCode.LEFT:
                    if (n === this._valueMin()) return;
                    a = this._trimAlignValue(n - o)
                }
                this._slide(i, r, a)
            },
            click: function(t) {
                t.preventDefault()
            },
            keyup: function(e) {
                var i = t(e.target).data("ui-slider-handle-index");
                this._keySliding && (this._keySliding = !1, this._stop(e, i), this._change(e, i), t(e.target).removeClass("ui-state-active"))
            }
        }
    })
} (jQuery),
function(t) {
    function e(t) {
        return function() {
            var e = this.element.val();
            t.apply(this, arguments),
            this._refresh(),
            e !== this.element.val() && this._trigger("change")
        }
    }
    t.widget("ui.spinner", {
        version: "1.10.3",
        defaultElement: "<input>",
        widgetEventPrefix: "spin",
        options: {
            culture: null,
            icons: {
                down: "ui-icon-triangle-1-s",
                up: "ui-icon-triangle-1-n"
            },
            incremental: !0,
            max: null,
            min: null,
            numberFormat: null,
            page: 10,
            step: 1,
            change: null,
            spin: null,
            start: null,
            stop: null
        },
        _create: function() {
            this._setOption("max", this.options.max),
            this._setOption("min", this.options.min),
            this._setOption("step", this.options.step),
            this._value(this.element.val(), !0),
            this._draw(),
            this._on(this._events),
            this._refresh(),
            this._on(this.window, {
                beforeunload: function() {
                    this.element.removeAttr("autocomplete")
                }
            })
        },
        _getCreateOptions: function() {
            var e = {},
            i = this.element;
            return t.each(["min", "max", "step"],
            function(t, s) {
                var n = i.attr(s);
                void 0 !== n && n.length && (e[s] = n)
            }),
            e
        },
        _events: {
            keydown: function(t) {
                this._start(t) && this._keydown(t) && t.preventDefault()
            },
            keyup: "_stop",
            focus: function() {
                this.previous = this.element.val()
            },
            blur: function(t) {
                return this.cancelBlur ? (delete this.cancelBlur, void 0) : (this._stop(), this._refresh(), this.previous !== this.element.val() && this._trigger("change", t), void 0)
            },
            mousewheel: function(t, e) {
                if (e) {
                    if (!this.spinning && !this._start(t)) return ! 1;
                    this._spin((e > 0 ? 1 : -1) * this.options.step, t),
                    clearTimeout(this.mousewheelTimer),
                    this.mousewheelTimer = this._delay(function() {
                        this.spinning && this._stop(t)
                    },
                    100),
                    t.preventDefault()
                }
            },
            "mousedown .ui-spinner-button": function(e) {
                function i() {
                    var t = this.element[0] === this.document[0].activeElement;
                    t || (this.element.focus(), this.previous = s, this._delay(function() {
                        this.previous = s
                    }))
                }
                var s;
                s = this.element[0] === this.document[0].activeElement ? this.previous: this.element.val(),
                e.preventDefault(),
                i.call(this),
                this.cancelBlur = !0,
                this._delay(function() {
                    delete this.cancelBlur,
                    i.call(this)
                }),
                this._start(e) !== !1 && this._repeat(null, t(e.currentTarget).hasClass("ui-spinner-up") ? 1 : -1, e)
            },
            "mouseup .ui-spinner-button": "_stop",
            "mouseenter .ui-spinner-button": function(e) {
                return t(e.currentTarget).hasClass("ui-state-active") ? this._start(e) === !1 ? !1 : (this._repeat(null, t(e.currentTarget).hasClass("ui-spinner-up") ? 1 : -1, e), void 0) : void 0
            },
            "mouseleave .ui-spinner-button": "_stop"
        },
        _draw: function() {
            var t = this.uiSpinner = this.element.addClass("ui-spinner-input").attr("autocomplete", "off").wrap(this._uiSpinnerHtml()).parent().append(this._buttonHtml());
            this.element.attr("role", "spinbutton"),
            this.buttons = t.find(".ui-spinner-button").attr("tabIndex", -1).button().removeClass("ui-corner-all"),
            this.buttons.height() > Math.ceil(.5 * t.height()) && t.height() > 0 && t.height(t.height()),
            this.options.disabled && this.disable()
        },
        _keydown: function(e) {
            var i = this.options,
            s = t.ui.keyCode;
            switch (e.keyCode) {
            case s.UP:
                return this._repeat(null, 1, e),
                !0;
            case s.DOWN:
                return this._repeat(null, -1, e),
                !0;
            case s.PAGE_UP:
                return this._repeat(null, i.page, e),
                !0;
            case s.PAGE_DOWN:
                return this._repeat(null, -i.page, e),
                !0
            }
            return ! 1
        },
        _uiSpinnerHtml: function() {
            return "<span class='ui-spinner ui-widget ui-widget-content ui-corner-all'></span>"
        },
        _buttonHtml: function() {
            return "<a class='ui-spinner-button ui-spinner-up ui-corner-tr'><span class='ui-icon " + this.options.icons.up + "'>&#9650;</span></a><a class='ui-spinner-button ui-spinner-down ui-corner-br'><span class='ui-icon " + this.options.icons.down + "'>&#9660;</span></a>"
        },
        _start: function(t) {
            return this.spinning || this._trigger("start", t) !== !1 ? (this.counter || (this.counter = 1), this.spinning = !0, !0) : !1
        },
        _repeat: function(t, e, i) {
            t = t || 500,
            clearTimeout(this.timer),
            this.timer = this._delay(function() {
                this._repeat(40, e, i)
            },
            t),
            this._spin(e * this.options.step, i)
        },
        _spin: function(t, e) {
            var i = this.value() || 0;
            this.counter || (this.counter = 1),
            i = this._adjustValue(i + t * this._increment(this.counter)),
            this.spinning && this._trigger("spin", e, {
                value: i
            }) === !1 || (this._value(i), this.counter++)
        },
        _increment: function(e) {
            var i = this.options.incremental;
            return i ? t.isFunction(i) ? i(e) : Math.floor(e * e * e / 5e4 - e * e / 500 + 17 * e / 200 + 1) : 1
        },
        _precision: function() {
            var t = this._precisionOf(this.options.step);
            return null !== this.options.min && (t = Math.max(t, this._precisionOf(this.options.min))),
            t
        },
        _precisionOf: function(t) {
            var e = "" + t,
            i = e.indexOf(".");
            return - 1 === i ? 0 : e.length - i - 1
        },
        _adjustValue: function(t) {
            var e, i, s = this.options;
            return e = null !== s.min ? s.min: 0,
            i = t - e,
            i = Math.round(i / s.step) * s.step,
            t = e + i,
            t = parseFloat(t.toFixed(this._precision())),
            null !== s.max && t > s.max ? s.max: null !== s.min && s.min > t ? s.min: t
        },
        _stop: function(t) {
            this.spinning && (clearTimeout(this.timer), clearTimeout(this.mousewheelTimer), this.counter = 0, this.spinning = !1, this._trigger("stop", t))
        },
        _setOption: function(t, e) {
            if ("culture" === t || "numberFormat" === t) {
                var i = this._parse(this.element.val());
                return this.options[t] = e,
                this.element.val(this._format(i)),
                void 0
            } ("max" === t || "min" === t || "step" === t) && "string" == typeof e && (e = this._parse(e)),
            "icons" === t && (this.buttons.first().find(".ui-icon").removeClass(this.options.icons.up).addClass(e.up), this.buttons.last().find(".ui-icon").removeClass(this.options.icons.down).addClass(e.down)),
            this._super(t, e),
            "disabled" === t && (e ? (this.element.prop("disabled", !0), this.buttons.button("disable")) : (this.element.prop("disabled", !1), this.buttons.button("enable")))
        },
        _setOptions: e(function(t) {
            this._super(t),
            this._value(this.element.val())
        }),
        _parse: function(t) {
            return "string" == typeof t && "" !== t && (t = window.Globalize && this.options.numberFormat ? Globalize.parseFloat(t, 10, this.options.culture) : +t),
            "" === t || isNaN(t) ? null: t
        },
        _format: function(t) {
            return "" === t ? "": window.Globalize && this.options.numberFormat ? Globalize.format(t, this.options.numberFormat, this.options.culture) : t
        },
        _refresh: function() {
            this.element.attr({
                "aria-valuemin": this.options.min,
                "aria-valuemax": this.options.max,
                "aria-valuenow": this._parse(this.element.val())
            })
        },
        _value: function(t, e) {
            var i;
            "" !== t && (i = this._parse(t), null !== i && (e || (i = this._adjustValue(i)), t = this._format(i))),
            this.element.val(t),
            this._refresh()
        },
        _destroy: function() {
            this.element.removeClass("ui-spinner-input").prop("disabled", !1).removeAttr("autocomplete").removeAttr("role").removeAttr("aria-valuemin").removeAttr("aria-valuemax").removeAttr("aria-valuenow"),
            this.uiSpinner.replaceWith(this.element)
        },
        stepUp: e(function(t) {
            this._stepUp(t)
        }),
        _stepUp: function(t) {
            this._start() && (this._spin((t || 1) * this.options.step), this._stop())
        },
        stepDown: e(function(t) {
            this._stepDown(t)
        }),
        _stepDown: function(t) {
            this._start() && (this._spin((t || 1) * -this.options.step), this._stop())
        },
        pageUp: e(function(t) {
            this._stepUp((t || 1) * this.options.page)
        }),
        pageDown: e(function(t) {
            this._stepDown((t || 1) * this.options.page)
        }),
        value: function(t) {
            return arguments.length ? (e(this._value).call(this, t), void 0) : this._parse(this.element.val())
        },
        widget: function() {
            return this.uiSpinner
        }
    })
} (jQuery),
function(t, e) {
    function i() {
        return++n
    }
    function s(t) {
        return t.hash.length > 1 && decodeURIComponent(t.href.replace(a, "")) === decodeURIComponent(location.href.replace(a, ""))
    }
    var n = 0,
    a = /#.*$/;
    t.widget("ui.tabs", {
        version: "1.10.3",
        delay: 300,
        options: {
            active: null,
            collapsible: !1,
            event: "click",
            heightStyle: "content",
            hide: null,
            show: null,
            activate: null,
            beforeActivate: null,
            beforeLoad: null,
            load: null
        },
        _create: function() {
            var e = this,
            i = this.options;
            this.running = !1,
            this.element.addClass("ui-tabs ui-widget ui-widget-content ui-corner-all").toggleClass("ui-tabs-collapsible", i.collapsible).delegate(".ui-tabs-nav > li", "mousedown" + this.eventNamespace,
            function(e) {
                t(this).is(".ui-state-disabled") && e.preventDefault()
            }).delegate(".ui-tabs-anchor", "focus" + this.eventNamespace,
            function() {
                t(this).closest("li").is(".ui-state-disabled") && this.blur()
            }),
            this._processTabs(),
            i.active = this._initialActive(),
            t.isArray(i.disabled) && (i.disabled = t.unique(i.disabled.concat(t.map(this.tabs.filter(".ui-state-disabled"),
            function(t) {
                return e.tabs.index(t)
            }))).sort()),
            this.active = this.options.active !== !1 && this.anchors.length ? this._findActive(i.active) : t(),
            this._refresh(),
            this.active.length && this.load(i.active)
        },
        _initialActive: function() {
            var i = this.options.active,
            s = this.options.collapsible,
            n = location.hash.substring(1);
            return null === i && (n && this.tabs.each(function(s, a) {
                return t(a).attr("aria-controls") === n ? (i = s, !1) : e
            }), null === i && (i = this.tabs.index(this.tabs.filter(".ui-tabs-active"))), (null === i || -1 === i) && (i = this.tabs.length ? 0 : !1)),
            i !== !1 && (i = this.tabs.index(this.tabs.eq(i)), -1 === i && (i = s ? !1 : 0)),
            !s && i === !1 && this.anchors.length && (i = 0),
            i
        },
        _getCreateEventData: function() {
            return {
                tab: this.active,
                panel: this.active.length ? this._getPanelForTab(this.active) : t()
            }
        },
        _tabKeydown: function(i) {
            var s = t(this.document[0].activeElement).closest("li"),
            n = this.tabs.index(s),
            a = !0;
            if (!this._handlePageNav(i)) {
                switch (i.keyCode) {
                case t.ui.keyCode.RIGHT:
                case t.ui.keyCode.DOWN:
                    n++;
                    break;
                case t.ui.keyCode.UP:
                case t.ui.keyCode.LEFT:
                    a = !1,
                    n--;
                    break;
                case t.ui.keyCode.END:
                    n = this.anchors.length - 1;
                    break;
                case t.ui.keyCode.HOME:
                    n = 0;
                    break;
                case t.ui.keyCode.SPACE:
                    return i.preventDefault(),
                    clearTimeout(this.activating),
                    this._activate(n),
                    e;
                case t.ui.keyCode.ENTER:
                    return i.preventDefault(),
                    clearTimeout(this.activating),
                    this._activate(n === this.options.active ? !1 : n),
                    e;
                default:
                    return
                }
                i.preventDefault(),
                clearTimeout(this.activating),
                n = this._focusNextTab(n, a),
                i.ctrlKey || (s.attr("aria-selected", "false"), this.tabs.eq(n).attr("aria-selected", "true"), this.activating = this._delay(function() {
                    this.option("active", n)
                },
                this.delay))
            }
        },
        _panelKeydown: function(e) {
            this._handlePageNav(e) || e.ctrlKey && e.keyCode === t.ui.keyCode.UP && (e.preventDefault(), this.active.focus())
        },
        _handlePageNav: function(i) {
            return i.altKey && i.keyCode === t.ui.keyCode.PAGE_UP ? (this._activate(this._focusNextTab(this.options.active - 1, !1)), !0) : i.altKey && i.keyCode === t.ui.keyCode.PAGE_DOWN ? (this._activate(this._focusNextTab(this.options.active + 1, !0)), !0) : e
        },
        _findNextTab: function(e, i) {
            function s() {
                return e > n && (e = 0),
                0 > e && (e = n),
                e
            }
            for (var n = this.tabs.length - 1; - 1 !== t.inArray(s(), this.options.disabled);) e = i ? e + 1 : e - 1;
            return e
        },
        _focusNextTab: function(t, e) {
            return t = this._findNextTab(t, e),
            this.tabs.eq(t).focus(),
            t
        },
        _setOption: function(t, i) {
            return "active" === t ? (this._activate(i), e) : "disabled" === t ? (this._setupDisabled(i), e) : (this._super(t, i), "collapsible" === t && (this.element.toggleClass("ui-tabs-collapsible", i), i || this.options.active !== !1 || this._activate(0)), "event" === t && this._setupEvents(i), "heightStyle" === t && this._setupHeightStyle(i), e)
        },
        _tabId: function(t) {
            return t.attr("aria-controls") || "ui-tabs-" + i()
        },
        _sanitizeSelector: function(t) {
            return t ? t.replace(/[!"$%&'()*+,.\/:;<=>?@\[\]\^`{|}~]/g, "\\$&") : ""
        },
        refresh: function() {
            var e = this.options,
            i = this.tablist.children(":has(a[href])");
            e.disabled = t.map(i.filter(".ui-state-disabled"),
            function(t) {
                return i.index(t)
            }),
            this._processTabs(),
            e.active !== !1 && this.anchors.length ? this.active.length && !t.contains(this.tablist[0], this.active[0]) ? this.tabs.length === e.disabled.length ? (e.active = !1, this.active = t()) : this._activate(this._findNextTab(Math.max(0, e.active - 1), !1)) : e.active = this.tabs.index(this.active) : (e.active = !1, this.active = t()),
            this._refresh()
        },
        _refresh: function() {
            this._setupDisabled(this.options.disabled),
            this._setupEvents(this.options.event),
            this._setupHeightStyle(this.options.heightStyle),
            this.tabs.not(this.active).attr({
                "aria-selected": "false",
                tabIndex: -1
            }),
            this.panels.not(this._getPanelForTab(this.active)).hide().attr({
                "aria-expanded": "false",
                "aria-hidden": "true"
            }),
            this.active.length ? (this.active.addClass("ui-tabs-active ui-state-active").attr({
                "aria-selected": "true",
                tabIndex: 0
            }), this._getPanelForTab(this.active).show().attr({
                "aria-expanded": "true",
                "aria-hidden": "false"
            })) : this.tabs.eq(0).attr("tabIndex", 0)
        },
        _processTabs: function() {
            var e = this;
            this.tablist = this._getList().addClass("ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all").attr("role", "tablist"),
            this.tabs = this.tablist.find("> li:has(a[href])").addClass("ui-state-default ui-corner-top").attr({
                role: "tab",
                tabIndex: -1
            }),
            this.anchors = this.tabs.map(function() {
                return t("a", this)[0]
            }).addClass("ui-tabs-anchor").attr({
                role: "presentation",
                tabIndex: -1
            }),
            this.panels = t(),
            this.anchors.each(function(i, n) {
                var a, o, r, h = t(n).uniqueId().attr("id"),
                l = t(n).closest("li"),
                c = l.attr("aria-controls");
                s(n) ? (a = n.hash, o = e.element.find(e._sanitizeSelector(a))) : (r = e._tabId(l), a = "#" + r, o = e.element.find(a), o.length || (o = e._createPanel(r), o.insertAfter(e.panels[i - 1] || e.tablist)), o.attr("aria-live", "polite")),
                o.length && (e.panels = e.panels.add(o)),
                c && l.data("ui-tabs-aria-controls", c),
                l.attr({
                    "aria-controls": a.substring(1),
                    "aria-labelledby": h
                }),
                o.attr("aria-labelledby", h)
            }),
            this.panels.addClass("ui-tabs-panel ui-widget-content ui-corner-bottom").attr("role", "tabpanel")
        },
        _getList: function() {
            return this.element.find("ol,ul").eq(0)
        },
        _createPanel: function(e) {
            return t("<div>").attr("id", e).addClass("ui-tabs-panel ui-widget-content ui-corner-bottom").data("ui-tabs-destroy", !0)
        },
        _setupDisabled: function(e) {
            t.isArray(e) && (e.length ? e.length === this.anchors.length && (e = !0) : e = !1);
            for (var i, s = 0; i = this.tabs[s]; s++) e === !0 || -1 !== t.inArray(s, e) ? t(i).addClass("ui-state-disabled").attr("aria-disabled", "true") : t(i).removeClass("ui-state-disabled").removeAttr("aria-disabled");
            this.options.disabled = e
        },
        _setupEvents: function(e) {
            var i = {
                click: function(t) {
                    t.preventDefault()
                }
            };
            e && t.each(e.split(" "),
            function(t, e) {
                i[e] = "_eventHandler"
            }),
            this._off(this.anchors.add(this.tabs).add(this.panels)),
            this._on(this.anchors, i),
            this._on(this.tabs, {
                keydown: "_tabKeydown"
            }),
            this._on(this.panels, {
                keydown: "_panelKeydown"
            }),
            this._focusable(this.tabs),
            this._hoverable(this.tabs)
        },
        _setupHeightStyle: function(e) {
            var i, s = this.element.parent();
            "fill" === e ? (i = s.height(), i -= this.element.outerHeight() - this.element.height(), this.element.siblings(":visible").each(function() {
                var e = t(this),
                s = e.css("position");
                "absolute" !== s && "fixed" !== s && (i -= e.outerHeight(!0))
            }), this.element.children().not(this.panels).each(function() {
                i -= t(this).outerHeight(!0)
            }), this.panels.each(function() {
                t(this).height(Math.max(0, i - t(this).innerHeight() + t(this).height()))
            }).css("overflow", "auto")) : "auto" === e && (i = 0, this.panels.each(function() {
                i = Math.max(i, t(this).height("").height())
            }).height(i))
        },
        _eventHandler: function(e) {
            var i = this.options,
            s = this.active,
            n = t(e.currentTarget),
            a = n.closest("li"),
            o = a[0] === s[0],
            r = o && i.collapsible,
            h = r ? t() : this._getPanelForTab(a),
            l = s.length ? this._getPanelForTab(s) : t(),
            c = {
                oldTab: s,
                oldPanel: l,
                newTab: r ? t() : a,
                newPanel: h
            };
            e.preventDefault(),
            a.hasClass("ui-state-disabled") || a.hasClass("ui-tabs-loading") || this.running || o && !i.collapsible || this._trigger("beforeActivate", e, c) === !1 || (i.active = r ? !1 : this.tabs.index(a), this.active = o ? t() : a, this.xhr && this.xhr.abort(), l.length || h.length || t.error("jQuery UI Tabs: Mismatching fragment identifier."), h.length && this.load(this.tabs.index(a), e), this._toggle(e, c))
        },
        _toggle: function(e, i) {
            function s() {
                a.running = !1,
                a._trigger("activate", e, i)
            }
            function n() {
                i.newTab.closest("li").addClass("ui-tabs-active ui-state-active"),
                o.length && a.options.show ? a._show(o, a.options.show, s) : (o.show(), s())
            }
            var a = this,
            o = i.newPanel,
            r = i.oldPanel;
            this.running = !0,
            r.length && this.options.hide ? this._hide(r, this.options.hide,
            function() {
                i.oldTab.closest("li").removeClass("ui-tabs-active ui-state-active"),
                n()
            }) : (i.oldTab.closest("li").removeClass("ui-tabs-active ui-state-active"), r.hide(), n()),
            r.attr({
                "aria-expanded": "false",
                "aria-hidden": "true"
            }),
            i.oldTab.attr("aria-selected", "false"),
            o.length && r.length ? i.oldTab.attr("tabIndex", -1) : o.length && this.tabs.filter(function() {
                return 0 === t(this).attr("tabIndex")
            }).attr("tabIndex", -1),
            o.attr({
                "aria-expanded": "true",
                "aria-hidden": "false"
            }),
            i.newTab.attr({
                "aria-selected": "true",
                tabIndex: 0
            })
        },
        _activate: function(e) {
            var i, s = this._findActive(e);
            s[0] !== this.active[0] && (s.length || (s = this.active), i = s.find(".ui-tabs-anchor")[0], this._eventHandler({
                target: i,
                currentTarget: i,
                preventDefault: t.noop
            }))
        },
        _findActive: function(e) {
            return e === !1 ? t() : this.tabs.eq(e)
        },
        _getIndex: function(t) {
            return "string" == typeof t && (t = this.anchors.index(this.anchors.filter("[href$='" + t + "']"))),
            t
        },
        _destroy: function() {
            this.xhr && this.xhr.abort(),
            this.element.removeClass("ui-tabs ui-widget ui-widget-content ui-corner-all ui-tabs-collapsible"),
            this.tablist.removeClass("ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all").removeAttr("role"),
            this.anchors.removeClass("ui-tabs-anchor").removeAttr("role").removeAttr("tabIndex").removeUniqueId(),
            this.tabs.add(this.panels).each(function() {
                t.data(this, "ui-tabs-destroy") ? t(this).remove() : t(this).removeClass("ui-state-default ui-state-active ui-state-disabled ui-corner-top ui-corner-bottom ui-widget-content ui-tabs-active ui-tabs-panel").removeAttr("tabIndex").removeAttr("aria-live").removeAttr("aria-busy").removeAttr("aria-selected").removeAttr("aria-labelledby").removeAttr("aria-hidden").removeAttr("aria-expanded").removeAttr("role")
            }),
            this.tabs.each(function() {
                var e = t(this),
                i = e.data("ui-tabs-aria-controls");
                i ? e.attr("aria-controls", i).removeData("ui-tabs-aria-controls") : e.removeAttr("aria-controls")
            }),
            this.panels.show(),
            "content" !== this.options.heightStyle && this.panels.css("height", "")
        },
        enable: function(i) {
            var s = this.options.disabled;
            s !== !1 && (i === e ? s = !1 : (i = this._getIndex(i), s = t.isArray(s) ? t.map(s,
            function(t) {
                return t !== i ? t: null
            }) : t.map(this.tabs,
            function(t, e) {
                return e !== i ? e: null
            })), this._setupDisabled(s))
        },
        disable: function(i) {
            var s = this.options.disabled;
            if (s !== !0) {
                if (i === e) s = !0;
                else {
                    if (i = this._getIndex(i), -1 !== t.inArray(i, s)) return;
                    s = t.isArray(s) ? t.merge([i], s).sort() : [i]
                }
                this._setupDisabled(s)
            }
        },
        load: function(e, i) {
            e = this._getIndex(e);
            var n = this,
            a = this.tabs.eq(e),
            o = a.find(".ui-tabs-anchor"),
            r = this._getPanelForTab(a),
            h = {
                tab: a,
                panel: r
            };
            s(o[0]) || (this.xhr = t.ajax(this._ajaxSettings(o, i, h)), this.xhr && "canceled" !== this.xhr.statusText && (a.addClass("ui-tabs-loading"), r.attr("aria-busy", "true"), this.xhr.success(function(t) {
                setTimeout(function() {
                    r.html(t),
                    n._trigger("load", i, h)
                },
                1)
            }).complete(function(t, e) {
                setTimeout(function() {
                    "abort" === e && n.panels.stop(!1, !0),
                    a.removeClass("ui-tabs-loading"),
                    r.removeAttr("aria-busy"),
                    t === n.xhr && delete n.xhr
                },
                1)
            })))
        },
        _ajaxSettings: function(e, i, s) {
            var n = this;
            return {
                url: e.attr("href"),
                beforeSend: function(e, a) {
                    return n._trigger("beforeLoad", i, t.extend({
                        jqXHR: e,
                        ajaxSettings: a
                    },
                    s))
                }
            }
        },
        _getPanelForTab: function(e) {
            var i = t(e).attr("aria-controls");
            return this.element.find(this._sanitizeSelector("#" + i))
        }
    })
} (jQuery),
function(t) {
    function e(e, i) {
        var s = (e.attr("aria-describedby") || "").split(/\s+/);
        s.push(i),
        e.data("ui-tooltip-id", i).attr("aria-describedby", t.trim(s.join(" ")))
    }
    function i(e) {
        var i = e.data("ui-tooltip-id"),
        s = (e.attr("aria-describedby") || "").split(/\s+/),
        n = t.inArray(i, s); - 1 !== n && s.splice(n, 1),
        e.removeData("ui-tooltip-id"),
        s = t.trim(s.join(" ")),
        s ? e.attr("aria-describedby", s) : e.removeAttr("aria-describedby")
    }
    var s = 0;
    t.widget("ui.tooltip", {
        version: "1.10.3",
        options: {
            content: function() {
                var e = t(this).attr("title") || "";
                return t("<a>").text(e).html()
            },
            hide: !0,
            items: "[title]:not([disabled])",
            position: {
                my: "left top+15",
                at: "left bottom",
                collision: "flipfit flip"
            },
            show: !0,
            tooltipClass: null,
            track: !1,
            close: null,
            open: null
        },
        _create: function() {
            this._on({
                mouseover: "open",
                focusin: "open"
            }),
            this.tooltips = {},
            this.parents = {},
            this.options.disabled && this._disable()
        },
        _setOption: function(e, i) {
            var s = this;
            return "disabled" === e ? (this[i ? "_disable": "_enable"](), this.options[e] = i, void 0) : (this._super(e, i), "content" === e && t.each(this.tooltips,
            function(t, e) {
                s._updateContent(e)
            }), void 0)
        },
        _disable: function() {
            var e = this;
            t.each(this.tooltips,
            function(i, s) {
                var n = t.Event("blur");
                n.target = n.currentTarget = s[0],
                e.close(n, !0)
            }),
            this.element.find(this.options.items).addBack().each(function() {
                var e = t(this);
                e.is("[title]") && e.data("ui-tooltip-title", e.attr("title")).attr("title", "")
            })
        },
        _enable: function() {
            this.element.find(this.options.items).addBack().each(function() {
                var e = t(this);
                e.data("ui-tooltip-title") && e.attr("title", e.data("ui-tooltip-title"))
            })
        },
        open: function(e) {
            var i = this,
            s = t(e ? e.target: this.element).closest(this.options.items);
            s.length && !s.data("ui-tooltip-id") && (s.attr("title") && s.data("ui-tooltip-title", s.attr("title")), s.data("ui-tooltip-open", !0), e && "mouseover" === e.type && s.parents().each(function() {
                var e, s = t(this);
                s.data("ui-tooltip-open") && (e = t.Event("blur"), e.target = e.currentTarget = this, i.close(e, !0)),
                s.attr("title") && (s.uniqueId(), i.parents[this.id] = {
                    element: this,
                    title: s.attr("title")
                },
                s.attr("title", ""))
            }), this._updateContent(s, e))
        },
        _updateContent: function(t, e) {
            var i, s = this.options.content,
            n = this,
            o = e ? e.type: null;
            return "string" == typeof s ? this._open(e, t, s) : (i = s.call(t[0],
            function(i) {
                t.data("ui-tooltip-open") && n._delay(function() {
                    e && (e.type = o),
                    this._open(e, t, i)
                })
            }), i && this._open(e, t, i), void 0)
        },
        _open: function(i, s, n) {
            function o(t) {
                l.of = t,
                a.is(":hidden") || a.position(l)
            }
            var a, r, h, l = t.extend({},
            this.options.position);
            if (n) {
                if (a = this._find(s), a.length) return a.find(".ui-tooltip-content").html(n),
                void 0;
                s.is("[title]") && (i && "mouseover" === i.type ? s.attr("title", "") : s.removeAttr("title")),
                a = this._tooltip(s),
                e(s, a.attr("id")),
                a.find(".ui-tooltip-content").html(n),
                this.options.track && i && /^mouse/.test(i.type) ? (this._on(this.document, {
                    mousemove: o
                }), o(i)) : a.position(t.extend({
                    of: s
                },
                this.options.position)),
                a.hide(),
                this._show(a, this.options.show),
                this.options.show && this.options.show.delay && (h = this.delayedShow = setInterval(function() {
                    a.is(":visible") && (o(l.of), clearInterval(h))
                },
                t.fx.interval)),
                this._trigger("open", i, {
                    tooltip: a
                }),
                r = {
                    keyup: function(e) {
                        if (e.keyCode === t.ui.keyCode.ESCAPE) {
                            var i = t.Event(e);
                            i.currentTarget = s[0],
                            this.close(i, !0)
                        }
                    },
                    remove: function() {
                        this._removeTooltip(a)
                    }
                },
                i && "mouseover" !== i.type || (r.mouseleave = "close"),
                i && "focusin" !== i.type || (r.focusout = "close"),
                this._on(!0, s, r)
            }
        },
        close: function(e) {
            var s = this,
            n = t(e ? e.currentTarget: this.element),
            o = this._find(n);
            this.closing || (clearInterval(this.delayedShow), n.data("ui-tooltip-title") && n.attr("title", n.data("ui-tooltip-title")), i(n), o.stop(!0), this._hide(o, this.options.hide,
            function() {
                s._removeTooltip(t(this))
            }), n.removeData("ui-tooltip-open"), this._off(n, "mouseleave focusout keyup"), n[0] !== this.element[0] && this._off(n, "remove"), this._off(this.document, "mousemove"), e && "mouseleave" === e.type && t.each(this.parents,
            function(e, i) {
                t(i.element).attr("title", i.title),
                delete s.parents[e]
            }), this.closing = !0, this._trigger("close", e, {
                tooltip: o
            }), this.closing = !1)
        },
        _tooltip: function(e) {
            var i = "ui-tooltip-" + s++,
            n = t("<div>").attr({
                id: i,
                role: "tooltip"
            }).addClass("ui-tooltip ui-widget ui-corner-all ui-widget-content " + (this.options.tooltipClass || ""));
            return t("<div>").addClass("ui-tooltip-content").appendTo(n),
            n.appendTo(this.document[0].body),
            this.tooltips[i] = e,
            n
        },
        _find: function(e) {
            var i = e.data("ui-tooltip-id");
            return i ? t("#" + i) : t()
        },
        _removeTooltip: function(t) {
            t.remove(),
            delete this.tooltips[t.attr("id")]
        },
        _destroy: function() {
            var e = this;
            t.each(this.tooltips,
            function(i, s) {
                var n = t.Event("blur");
                n.target = n.currentTarget = s[0],
                e.close(n, !0),
                t("#" + i).remove(),
                s.data("ui-tooltip-title") && (s.attr("title", s.data("ui-tooltip-title")), s.removeData("ui-tooltip-title"))
            })
        }
    })
} (jQuery),
function(t, e) {
    var i = "ui-effects-";
    t.effects = {
        effect: {}
    },
    function(t, e) {
        function i(t, e, i) {
            var s = u[e.type] || {};
            return null == t ? i || !e.def ? null: e.def: (t = s.floor ? ~~t: parseFloat(t), isNaN(t) ? e.def: s.mod ? (t + s.mod) % s.mod: 0 > t ? 0 : t > s.max ? s.max: t)
        }
        function s(i) {
            var s = h(),
            n = s._rgba = [];
            return i = i.toLowerCase(),
            f(l,
            function(t, a) {
                var o, r = a.re.exec(i),
                l = r && a.parse(r),
                h = a.space || "rgba";
                return l ? (o = s[h](l), s[c[h].cache] = o[c[h].cache], n = s._rgba = o._rgba, !1) : e
            }),
            n.length ? ("0,0,0,0" === n.join() && t.extend(n, a.transparent), s) : a[i]
        }
        function n(t, e, i) {
            return i = (i + 1) % 1,
            1 > 6 * i ? t + 6 * (e - t) * i: 1 > 2 * i ? e: 2 > 3 * i ? t + 6 * (e - t) * (2 / 3 - i) : t
        }
        var a, o = "backgroundColor borderBottomColor borderLeftColor borderRightColor borderTopColor color columnRuleColor outlineColor textDecorationColor textEmphasisColor",
        r = /^([\-+])=\s*(\d+\.?\d*)/,
        l = [{
            re: /rgba?\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,
            parse: function(t) {
                return [t[1], t[2], t[3], t[4]]
            }
        },
        {
            re: /rgba?\(\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,
            parse: function(t) {
                return [2.55 * t[1], 2.55 * t[2], 2.55 * t[3], t[4]]
            }
        },
        {
            re: /#([a-f0-9]{2})([a-f0-9]{2})([a-f0-9]{2})/,
            parse: function(t) {
                return [parseInt(t[1], 16), parseInt(t[2], 16), parseInt(t[3], 16)]
            }
        },
        {
            re: /#([a-f0-9])([a-f0-9])([a-f0-9])/,
            parse: function(t) {
                return [parseInt(t[1] + t[1], 16), parseInt(t[2] + t[2], 16), parseInt(t[3] + t[3], 16)]
            }
        },
        {
            re: /hsla?\(\s*(\d+(?:\.\d+)?)\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,
            space: "hsla",
            parse: function(t) {
                return [t[1], t[2] / 100, t[3] / 100, t[4]]
            }
        }],
        h = t.Color = function(e, i, s, n) {
            return new t.Color.fn.parse(e, i, s, n)
        },
        c = {
            rgba: {
                props: {
                    red: {
                        idx: 0,
                        type: "byte"
                    },
                    green: {
                        idx: 1,
                        type: "byte"
                    },
                    blue: {
                        idx: 2,
                        type: "byte"
                    }
                }
            },
            hsla: {
                props: {
                    hue: {
                        idx: 0,
                        type: "degrees"
                    },
                    saturation: {
                        idx: 1,
                        type: "percent"
                    },
                    lightness: {
                        idx: 2,
                        type: "percent"
                    }
                }
            }
        },
        u = {
            "byte": {
                floor: !0,
                max: 255
            },
            percent: {
                max: 1
            },
            degrees: {
                mod: 360,
                floor: !0
            }
        },
        d = h.support = {},
        p = t("<p>")[0],
        f = t.each;
        p.style.cssText = "background-color:rgba(1,1,1,.5)",
        d.rgba = p.style.backgroundColor.indexOf("rgba") > -1,
        f(c,
        function(t, e) {
            e.cache = "_" + t,
            e.props.alpha = {
                idx: 3,
                type: "percent",
                def: 1
            }
        }),
        h.fn = t.extend(h.prototype, {
            parse: function(n, o, r, l) {
                if (n === e) return this._rgba = [null, null, null, null],
                this; (n.jquery || n.nodeType) && (n = t(n).css(o), o = e);
                var u = this,
                d = t.type(n),
                p = this._rgba = [];
                return o !== e && (n = [n, o, r, l], d = "array"),
                "string" === d ? this.parse(s(n) || a._default) : "array" === d ? (f(c.rgba.props,
                function(t, e) {
                    p[e.idx] = i(n[e.idx], e)
                }), this) : "object" === d ? (n instanceof h ? f(c,
                function(t, e) {
                    n[e.cache] && (u[e.cache] = n[e.cache].slice())
                }) : f(c,
                function(e, s) {
                    var a = s.cache;
                    f(s.props,
                    function(t, e) {
                        if (!u[a] && s.to) {
                            if ("alpha" === t || null == n[t]) return;
                            u[a] = s.to(u._rgba)
                        }
                        u[a][e.idx] = i(n[t], e, !0)
                    }),
                    u[a] && 0 > t.inArray(null, u[a].slice(0, 3)) && (u[a][3] = 1, s.from && (u._rgba = s.from(u[a])))
                }), this) : e
            },
            is: function(t) {
                var i = h(t),
                s = !0,
                n = this;
                return f(c,
                function(t, a) {
                    var o, r = i[a.cache];
                    return r && (o = n[a.cache] || a.to && a.to(n._rgba) || [], f(a.props,
                    function(t, i) {
                        return null != r[i.idx] ? s = r[i.idx] === o[i.idx] : e
                    })),
                    s
                }),
                s
            },
            _space: function() {
                var t = [],
                e = this;
                return f(c,
                function(i, s) {
                    e[s.cache] && t.push(i)
                }),
                t.pop()
            },
            transition: function(t, e) {
                var s = h(t),
                n = s._space(),
                a = c[n],
                o = 0 === this.alpha() ? h("transparent") : this,
                r = o[a.cache] || a.to(o._rgba),
                l = r.slice();
                return s = s[a.cache],
                f(a.props,
                function(t, n) {
                    var a = n.idx,
                    o = r[a],
                    h = s[a],
                    c = u[n.type] || {};
                    null !== h && (null === o ? l[a] = h: (c.mod && (h - o > c.mod / 2 ? o += c.mod: o - h > c.mod / 2 && (o -= c.mod)), l[a] = i((h - o) * e + o, n)))
                }),
                this[n](l)
            },
            blend: function(e) {
                if (1 === this._rgba[3]) return this;
                var i = this._rgba.slice(),
                s = i.pop(),
                n = h(e)._rgba;
                return h(t.map(i,
                function(t, e) {
                    return (1 - s) * n[e] + s * t
                }))
            },
            toRgbaString: function() {
                var e = "rgba(",
                i = t.map(this._rgba,
                function(t, e) {
                    return null == t ? e > 2 ? 1 : 0 : t
                });
                return 1 === i[3] && (i.pop(), e = "rgb("),
                e + i.join() + ")"
            },
            toHslaString: function() {
                var e = "hsla(",
                i = t.map(this.hsla(),
                function(t, e) {
                    return null == t && (t = e > 2 ? 1 : 0),
                    e && 3 > e && (t = Math.round(100 * t) + "%"),
                    t
                });
                return 1 === i[3] && (i.pop(), e = "hsl("),
                e + i.join() + ")"
            },
            toHexString: function(e) {
                var i = this._rgba.slice(),
                s = i.pop();
                return e && i.push(~~ (255 * s)),
                "#" + t.map(i,
                function(t) {
                    return t = (t || 0).toString(16),
                    1 === t.length ? "0" + t: t
                }).join("")
            },
            toString: function() {
                return 0 === this._rgba[3] ? "transparent": this.toRgbaString()
            }
        }),
        h.fn.parse.prototype = h.fn,
        c.hsla.to = function(t) {
            if (null == t[0] || null == t[1] || null == t[2]) return [null, null, null, t[3]];
            var e, i, s = t[0] / 255,
            n = t[1] / 255,
            a = t[2] / 255,
            o = t[3],
            r = Math.max(s, n, a),
            l = Math.min(s, n, a),
            h = r - l,
            c = r + l,
            u = .5 * c;
            return e = l === r ? 0 : s === r ? 60 * (n - a) / h + 360 : n === r ? 60 * (a - s) / h + 120 : 60 * (s - n) / h + 240,
            i = 0 === h ? 0 : .5 >= u ? h / c: h / (2 - c),
            [Math.round(e) % 360, i, u, null == o ? 1 : o]
        },
        c.hsla.from = function(t) {
            if (null == t[0] || null == t[1] || null == t[2]) return [null, null, null, t[3]];
            var e = t[0] / 360,
            i = t[1],
            s = t[2],
            a = t[3],
            o = .5 >= s ? s * (1 + i) : s + i - s * i,
            r = 2 * s - o;
            return [Math.round(255 * n(r, o, e + 1 / 3)), Math.round(255 * n(r, o, e)), Math.round(255 * n(r, o, e - 1 / 3)), a]
        },
        f(c,
        function(s, n) {
            var a = n.props,
            o = n.cache,
            l = n.to,
            c = n.from;
            h.fn[s] = function(s) {
                if (l && !this[o] && (this[o] = l(this._rgba)), s === e) return this[o].slice();
                var n, r = t.type(s),
                u = "array" === r || "object" === r ? s: arguments,
                d = this[o].slice();
                return f(a,
                function(t, e) {
                    var s = u["object" === r ? t: e.idx];
                    null == s && (s = d[e.idx]),
                    d[e.idx] = i(s, e)
                }),
                c ? (n = h(c(d)), n[o] = d, n) : h(d)
            },
            f(a,
            function(e, i) {
                h.fn[e] || (h.fn[e] = function(n) {
                    var a, o = t.type(n),
                    l = "alpha" === e ? this._hsla ? "hsla": "rgba": s,
                    h = this[l](),
                    c = h[i.idx];
                    return "undefined" === o ? c: ("function" === o && (n = n.call(this, c), o = t.type(n)), null == n && i.empty ? this: ("string" === o && (a = r.exec(n), a && (n = c + parseFloat(a[2]) * ("+" === a[1] ? 1 : -1))), h[i.idx] = n, this[l](h)))
                })
            })
        }),
        h.hook = function(e) {
            var i = e.split(" ");
            f(i,
            function(e, i) {
                t.cssHooks[i] = {
                    set: function(e, n) {
                        var a, o, r = "";
                        if ("transparent" !== n && ("string" !== t.type(n) || (a = s(n)))) {
                            if (n = h(a || n), !d.rgba && 1 !== n._rgba[3]) {
                                for (o = "backgroundColor" === i ? e.parentNode: e; ("" === r || "transparent" === r) && o && o.style;) try {
                                    r = t.css(o, "backgroundColor"),
                                    o = o.parentNode
                                } catch(l) {}
                                n = n.blend(r && "transparent" !== r ? r: "_default")
                            }
                            n = n.toRgbaString()
                        }
                        try {
                            e.style[i] = n
                        } catch(l) {}
                    }
                },
                t.fx.step[i] = function(e) {
                    e.colorInit || (e.start = h(e.elem, i), e.end = h(e.end), e.colorInit = !0),
                    t.cssHooks[i].set(e.elem, e.start.transition(e.end, e.pos))
                }
            })
        },
        h.hook(o),
        t.cssHooks.borderColor = {
            expand: function(t) {
                var e = {};
                return f(["Top", "Right", "Bottom", "Left"],
                function(i, s) {
                    e["border" + s + "Color"] = t
                }),
                e
            }
        },
        a = t.Color.names = {
            aqua: "#00ffff",
            black: "#000000",
            blue: "#0000ff",
            fuchsia: "#ff00ff",
            gray: "#808080",
            green: "#008000",
            lime: "#00ff00",
            maroon: "#800000",
            navy: "#000080",
            olive: "#808000",
            purple: "#800080",
            red: "#ff0000",
            silver: "#c0c0c0",
            teal: "#008080",
            white: "#ffffff",
            yellow: "#ffff00",
            transparent: [null, null, null, 0],
            _default: "#ffffff"
        }
    } (jQuery),
    function() {
        function i(e) {
            var i, s, n = e.ownerDocument.defaultView ? e.ownerDocument.defaultView.getComputedStyle(e, null) : e.currentStyle,
            a = {};
            if (n && n.length && n[0] && n[n[0]]) for (s = n.length; s--;) i = n[s],
            "string" == typeof n[i] && (a[t.camelCase(i)] = n[i]);
            else for (i in n)"string" == typeof n[i] && (a[i] = n[i]);
            return a
        }
        function s(e, i) {
            var s, n, o = {};
            for (s in i) n = i[s],
            e[s] !== n && (a[s] || (t.fx.step[s] || !isNaN(parseFloat(n))) && (o[s] = n));
            return o
        }
        var n = ["add", "remove", "toggle"],
        a = {
            border: 1,
            borderBottom: 1,
            borderColor: 1,
            borderLeft: 1,
            borderRight: 1,
            borderTop: 1,
            borderWidth: 1,
            margin: 1,
            padding: 1
        };
        t.each(["borderLeftStyle", "borderRightStyle", "borderBottomStyle", "borderTopStyle"],
        function(e, i) {
            t.fx.step[i] = function(t) { ("none" !== t.end && !t.setAttr || 1 === t.pos && !t.setAttr) && (jQuery.style(t.elem, i, t.end), t.setAttr = !0)
            }
        }),
        t.fn.addBack || (t.fn.addBack = function(t) {
            return this.add(null == t ? this.prevObject: this.prevObject.filter(t))
        }),
        t.effects.animateClass = function(e, a, o, r) {
            var l = t.speed(a, o, r);
            return this.queue(function() {
                var a, o = t(this),
                r = o.attr("class") || "",
                h = l.children ? o.find("*").addBack() : o;
                h = h.map(function() {
                    var e = t(this);
                    return {
                        el: e,
                        start: i(this)
                    }
                }),
                a = function() {
                    t.each(n,
                    function(t, i) {
                        e[i] && o[i + "Class"](e[i])
                    })
                },
                a(),
                h = h.map(function() {
                    return this.end = i(this.el[0]),
                    this.diff = s(this.start, this.end),
                    this
                }),
                o.attr("class", r),
                h = h.map(function() {
                    var e = this,
                    i = t.Deferred(),
                    s = t.extend({},
                    l, {
                        queue: !1,
                        complete: function() {
                            i.resolve(e)
                        }
                    });
                    return this.el.animate(this.diff, s),
                    i.promise()
                }),
                t.when.apply(t, h.get()).done(function() {
                    a(),
                    t.each(arguments,
                    function() {
                        var e = this.el;
                        t.each(this.diff,
                        function(t) {
                            e.css(t, "")
                        })
                    }),
                    l.complete.call(o[0])
                })
            })
        },
        t.fn.extend({
            addClass: function(e) {
                return function(i, s, n, a) {
                    return s ? t.effects.animateClass.call(this, {
                        add: i
                    },
                    s, n, a) : e.apply(this, arguments)
                }
            } (t.fn.addClass),
            removeClass: function(e) {
                return function(i, s, n, a) {
                    return arguments.length > 1 ? t.effects.animateClass.call(this, {
                        remove: i
                    },
                    s, n, a) : e.apply(this, arguments)
                }
            } (t.fn.removeClass),
            toggleClass: function(i) {
                return function(s, n, a, o, r) {
                    return "boolean" == typeof n || n === e ? a ? t.effects.animateClass.call(this, n ? {
                        add: s
                    }: {
                        remove: s
                    },
                    a, o, r) : i.apply(this, arguments) : t.effects.animateClass.call(this, {
                        toggle: s
                    },
                    n, a, o)
                }
            } (t.fn.toggleClass),
            switchClass: function(e, i, s, n, a) {
                return t.effects.animateClass.call(this, {
                    add: i,
                    remove: e
                },
                s, n, a)
            }
        })
    } (),
    function() {
        function s(e, i, s, n) {
            return t.isPlainObject(e) && (i = e, e = e.effect),
            e = {
                effect: e
            },
            null == i && (i = {}),
            t.isFunction(i) && (n = i, s = null, i = {}),
            ("number" == typeof i || t.fx.speeds[i]) && (n = s, s = i, i = {}),
            t.isFunction(s) && (n = s, s = null),
            i && t.extend(e, i),
            s = s || i.duration,
            e.duration = t.fx.off ? 0 : "number" == typeof s ? s: s in t.fx.speeds ? t.fx.speeds[s] : t.fx.speeds._default,
            e.complete = n || i.complete,
            e
        }
        function n(e) {
            return ! e || "number" == typeof e || t.fx.speeds[e] ? !0 : "string" != typeof e || t.effects.effect[e] ? t.isFunction(e) ? !0 : "object" != typeof e || e.effect ? !1 : !0 : !0
        }
        t.extend(t.effects, {
            version: "1.10.3",
            save: function(t, e) {
                for (var s = 0; e.length > s; s++) null !== e[s] && t.data(i + e[s], t[0].style[e[s]])
            },
            restore: function(t, s) {
                var n, a;
                for (a = 0; s.length > a; a++) null !== s[a] && (n = t.data(i + s[a]), n === e && (n = ""), t.css(s[a], n))
            },
            setMode: function(t, e) {
                return "toggle" === e && (e = t.is(":hidden") ? "show": "hide"),
                e
            },
            getBaseline: function(t, e) {
                var i, s;
                switch (t[0]) {
                case "top":
                    i = 0;
                    break;
                case "middle":
                    i = .5;
                    break;
                case "bottom":
                    i = 1;
                    break;
                default:
                    i = t[0] / e.height
                }
                switch (t[1]) {
                case "left":
                    s = 0;
                    break;
                case "center":
                    s = .5;
                    break;
                case "right":
                    s = 1;
                    break;
                default:
                    s = t[1] / e.width
                }
                return {
                    x: s,
                    y: i
                }
            },
            createWrapper: function(e) {
                if (e.parent().is(".ui-effects-wrapper")) return e.parent();
                var i = {
                    width: e.outerWidth(!0),
                    height: e.outerHeight(!0),
                    "float": e.css("float")
                },
                s = t("<div></div>").addClass("ui-effects-wrapper").css({
                    fontSize: "100%",
                    background: "transparent",
                    border: "none",
                    margin: 0,
                    padding: 0
                }),
                n = {
                    width: e.width(),
                    height: e.height()
                },
                a = document.activeElement;
                try {
                    a.id
                } catch(o) {
                    a = document.body
                }
                return e.wrap(s),
                (e[0] === a || t.contains(e[0], a)) && t(a).focus(),
                s = e.parent(),
                "static" === e.css("position") ? (s.css({
                    position: "relative"
                }), e.css({
                    position: "relative"
                })) : (t.extend(i, {
                    position: e.css("position"),
                    zIndex: e.css("z-index")
                }), t.each(["top", "left", "bottom", "right"],
                function(t, s) {
                    i[s] = e.css(s),
                    isNaN(parseInt(i[s], 10)) && (i[s] = "auto")
                }), e.css({
                    position: "relative",
                    top: 0,
                    left: 0,
                    right: "auto",
                    bottom: "auto"
                })),
                e.css(n),
                s.css(i).show()
            },
            removeWrapper: function(e) {
                var i = document.activeElement;
                return e.parent().is(".ui-effects-wrapper") && (e.parent().replaceWith(e), (e[0] === i || t.contains(e[0], i)) && t(i).focus()),
                e
            },
            setTransition: function(e, i, s, n) {
                return n = n || {},
                t.each(i,
                function(t, i) {
                    var a = e.cssUnit(i);
                    a[0] > 0 && (n[i] = a[0] * s + a[1])
                }),
                n
            }
        }),
        t.fn.extend({
            effect: function() {
                function e(e) {
                    function s() {
                        t.isFunction(a) && a.call(n[0]),
                        t.isFunction(e) && e()
                    }
                    var n = t(this),
                    a = i.complete,
                    r = i.mode; (n.is(":hidden") ? "hide" === r: "show" === r) ? (n[r](), s()) : o.call(n[0], i, s)
                }
                var i = s.apply(this, arguments),
                n = i.mode,
                a = i.queue,
                o = t.effects.effect[i.effect];
                return t.fx.off || !o ? n ? this[n](i.duration, i.complete) : this.each(function() {
                    i.complete && i.complete.call(this)
                }) : a === !1 ? this.each(e) : this.queue(a || "fx", e)
            },
            show: function(t) {
                return function(e) {
                    if (n(e)) return t.apply(this, arguments);
                    var i = s.apply(this, arguments);
                    return i.mode = "show",
                    this.effect.call(this, i)
                }
            } (t.fn.show),
            hide: function(t) {
                return function(e) {
                    if (n(e)) return t.apply(this, arguments);
                    var i = s.apply(this, arguments);
                    return i.mode = "hide",
                    this.effect.call(this, i)
                }
            } (t.fn.hide),
            toggle: function(t) {
                return function(e) {
                    if (n(e) || "boolean" == typeof e) return t.apply(this, arguments);
                    var i = s.apply(this, arguments);
                    return i.mode = "toggle",
                    this.effect.call(this, i)
                }
            } (t.fn.toggle),
            cssUnit: function(e) {
                var i = this.css(e),
                s = [];
                return t.each(["em", "px", "%", "pt"],
                function(t, e) {
                    i.indexOf(e) > 0 && (s = [parseFloat(i), e])
                }),
                s
            }
        })
    } (),
    function() {
        var e = {};
        t.each(["Quad", "Cubic", "Quart", "Quint", "Expo"],
        function(t, i) {
            e[i] = function(e) {
                return Math.pow(e, t + 2)
            }
        }),
        t.extend(e, {
            Sine: function(t) {
                return 1 - Math.cos(t * Math.PI / 2)
            },
            Circ: function(t) {
                return 1 - Math.sqrt(1 - t * t)
            },
            Elastic: function(t) {
                return 0 === t || 1 === t ? t: -Math.pow(2, 8 * (t - 1)) * Math.sin((80 * (t - 1) - 7.5) * Math.PI / 15)
            },
            Back: function(t) {
                return t * t * (3 * t - 2)
            },
            Bounce: function(t) {
                for (var e, i = 4; ((e = Math.pow(2, --i)) - 1) / 11 > t;);
                return 1 / Math.pow(4, 3 - i) - 7.5625 * Math.pow((3 * e - 2) / 22 - t, 2)
            }
        }),
        t.each(e,
        function(e, i) {
            t.easing["easeIn" + e] = i,
            t.easing["easeOut" + e] = function(t) {
                return 1 - i(1 - t)
            },
            t.easing["easeInOut" + e] = function(t) {
                return.5 > t ? i(2 * t) / 2 : 1 - i( - 2 * t + 2) / 2
            }
        })
    } ()
} (jQuery),
function(t) {
    var e = /up|down|vertical/,
    i = /up|left|vertical|horizontal/;
    t.effects.effect.blind = function(s, n) {
        var a, o, r, l = t(this),
        h = ["position", "top", "bottom", "left", "right", "height", "width"],
        c = t.effects.setMode(l, s.mode || "hide"),
        u = s.direction || "up",
        d = e.test(u),
        p = d ? "height": "width",
        f = d ? "top": "left",
        g = i.test(u),
        m = {},
        v = "show" === c;
        l.parent().is(".ui-effects-wrapper") ? t.effects.save(l.parent(), h) : t.effects.save(l, h),
        l.show(),
        a = t.effects.createWrapper(l).css({
            overflow: "hidden"
        }),
        o = a[p](),
        r = parseFloat(a.css(f)) || 0,
        m[p] = v ? o: 0,
        g || (l.css(d ? "bottom": "right", 0).css(d ? "top": "left", "auto").css({
            position: "absolute"
        }), m[f] = v ? r: o + r),
        v && (a.css(p, 0), g || a.css(f, r + o)),
        a.animate(m, {
            duration: s.duration,
            easing: s.easing,
            queue: !1,
            complete: function() {
                "hide" === c && l.hide(),
                t.effects.restore(l, h),
                t.effects.removeWrapper(l),
                n()
            }
        })
    }
} (jQuery),
function(t) {
    t.effects.effect.bounce = function(e, i) {
        var s, n, a, o = t(this),
        r = ["position", "top", "bottom", "left", "right", "height", "width"],
        l = t.effects.setMode(o, e.mode || "effect"),
        h = "hide" === l,
        c = "show" === l,
        u = e.direction || "up",
        d = e.distance,
        p = e.times || 5,
        f = 2 * p + (c || h ? 1 : 0),
        g = e.duration / f,
        m = e.easing,
        v = "up" === u || "down" === u ? "top": "left",
        _ = "up" === u || "left" === u,
        b = o.queue(),
        y = b.length;
        for ((c || h) && r.push("opacity"), t.effects.save(o, r), o.show(), t.effects.createWrapper(o), d || (d = o["top" === v ? "outerHeight": "outerWidth"]() / 3), c && (a = {
            opacity: 1
        },
        a[v] = 0, o.css("opacity", 0).css(v, _ ? 2 * -d: 2 * d).animate(a, g, m)), h && (d /= Math.pow(2, p - 1)), a = {},
        a[v] = 0, s = 0; p > s; s++) n = {},
        n[v] = (_ ? "-=": "+=") + d,
        o.animate(n, g, m).animate(a, g, m),
        d = h ? 2 * d: d / 2;
        h && (n = {
            opacity: 0
        },
        n[v] = (_ ? "-=": "+=") + d, o.animate(n, g, m)),
        o.queue(function() {
            h && o.hide(),
            t.effects.restore(o, r),
            t.effects.removeWrapper(o),
            i()
        }),
        y > 1 && b.splice.apply(b, [1, 0].concat(b.splice(y, f + 1))),
        o.dequeue()
    }
} (jQuery),
function(t) {
    t.effects.effect.clip = function(e, i) {
        var s, n, a, o = t(this),
        r = ["position", "top", "bottom", "left", "right", "height", "width"],
        l = t.effects.setMode(o, e.mode || "hide"),
        h = "show" === l,
        c = e.direction || "vertical",
        u = "vertical" === c,
        d = u ? "height": "width",
        p = u ? "top": "left",
        f = {};
        t.effects.save(o, r),
        o.show(),
        s = t.effects.createWrapper(o).css({
            overflow: "hidden"
        }),
        n = "IMG" === o[0].tagName ? s: o,
        a = n[d](),
        h && (n.css(d, 0), n.css(p, a / 2)),
        f[d] = h ? a: 0,
        f[p] = h ? 0 : a / 2,
        n.animate(f, {
            queue: !1,
            duration: e.duration,
            easing: e.easing,
            complete: function() {
                h || o.hide(),
                t.effects.restore(o, r),
                t.effects.removeWrapper(o),
                i()
            }
        })
    }
} (jQuery),
function(t) {
    t.effects.effect.drop = function(e, i) {
        var s, n = t(this),
        a = ["position", "top", "bottom", "left", "right", "opacity", "height", "width"],
        o = t.effects.setMode(n, e.mode || "hide"),
        r = "show" === o,
        l = e.direction || "left",
        h = "up" === l || "down" === l ? "top": "left",
        c = "up" === l || "left" === l ? "pos": "neg",
        u = {
            opacity: r ? 1 : 0
        };
        t.effects.save(n, a),
        n.show(),
        t.effects.createWrapper(n),
        s = e.distance || n["top" === h ? "outerHeight": "outerWidth"](!0) / 2,
        r && n.css("opacity", 0).css(h, "pos" === c ? -s: s),
        u[h] = (r ? "pos" === c ? "+=": "-=": "pos" === c ? "-=": "+=") + s,
        n.animate(u, {
            queue: !1,
            duration: e.duration,
            easing: e.easing,
            complete: function() {
                "hide" === o && n.hide(),
                t.effects.restore(n, a),
                t.effects.removeWrapper(n),
                i()
            }
        })
    }
} (jQuery),
function(t) {
    t.effects.effect.explode = function(e, i) {
        function s() {
            b.push(this),
            b.length === u * d && n()
        }
        function n() {
            p.css({
                visibility: "visible"
            }),
            t(b).remove(),
            g || p.hide(),
            i()
        }
        var a, o, r, l, h, c, u = e.pieces ? Math.round(Math.sqrt(e.pieces)) : 3,
        d = u,
        p = t(this),
        f = t.effects.setMode(p, e.mode || "hide"),
        g = "show" === f,
        m = p.show().css("visibility", "hidden").offset(),
        v = Math.ceil(p.outerWidth() / d),
        _ = Math.ceil(p.outerHeight() / u),
        b = [];
        for (a = 0; u > a; a++) for (l = m.top + a * _, c = a - (u - 1) / 2, o = 0; d > o; o++) r = m.left + o * v,
        h = o - (d - 1) / 2,
        p.clone().appendTo("body").wrap("<div></div>").css({
            position: "absolute",
            visibility: "visible",
            left: -o * v,
            top: -a * _
        }).parent().addClass("ui-effects-explode").css({
            position: "absolute",
            overflow: "hidden",
            width: v,
            height: _,
            left: r + (g ? h * v: 0),
            top: l + (g ? c * _: 0),
            opacity: g ? 0 : 1
        }).animate({
            left: r + (g ? 0 : h * v),
            top: l + (g ? 0 : c * _),
            opacity: g ? 1 : 0
        },
        e.duration || 500, e.easing, s)
    }
} (jQuery),
function(t) {
    t.effects.effect.fade = function(e, i) {
        var s = t(this),
        n = t.effects.setMode(s, e.mode || "toggle");
        s.animate({
            opacity: n
        },
        {
            queue: !1,
            duration: e.duration,
            easing: e.easing,
            complete: i
        })
    }
} (jQuery),
function(t) {
    t.effects.effect.fold = function(e, i) {
        var s, n, a = t(this),
        o = ["position", "top", "bottom", "left", "right", "height", "width"],
        r = t.effects.setMode(a, e.mode || "hide"),
        l = "show" === r,
        h = "hide" === r,
        c = e.size || 15,
        u = /([0-9]+)%/.exec(c),
        d = !!e.horizFirst,
        p = l !== d,
        f = p ? ["width", "height"] : ["height", "width"],
        g = e.duration / 2,
        m = {},
        v = {};
        t.effects.save(a, o),
        a.show(),
        s = t.effects.createWrapper(a).css({
            overflow: "hidden"
        }),
        n = p ? [s.width(), s.height()] : [s.height(), s.width()],
        u && (c = parseInt(u[1], 10) / 100 * n[h ? 0 : 1]),
        l && s.css(d ? {
            height: 0,
            width: c
        }: {
            height: c,
            width: 0
        }),
        m[f[0]] = l ? n[0] : c,
        v[f[1]] = l ? n[1] : 0,
        s.animate(m, g, e.easing).animate(v, g, e.easing,
        function() {
            h && a.hide(),
            t.effects.restore(a, o),
            t.effects.removeWrapper(a),
            i()
        })
    }
} (jQuery),
function(t) {
    t.effects.effect.highlight = function(e, i) {
        var s = t(this),
        n = ["backgroundImage", "backgroundColor", "opacity"],
        a = t.effects.setMode(s, e.mode || "show"),
        o = {
            backgroundColor: s.css("backgroundColor")
        };
        "hide" === a && (o.opacity = 0),
        t.effects.save(s, n),
        s.show().css({
            backgroundImage: "none",
            backgroundColor: e.color || "#ffff99"
        }).animate(o, {
            queue: !1,
            duration: e.duration,
            easing: e.easing,
            complete: function() {
                "hide" === a && s.hide(),
                t.effects.restore(s, n),
                i()
            }
        })
    }
} (jQuery),
function(t) {
    t.effects.effect.pulsate = function(e, i) {
        var s, n = t(this),
        a = t.effects.setMode(n, e.mode || "show"),
        o = "show" === a,
        r = "hide" === a,
        l = o || "hide" === a,
        h = 2 * (e.times || 5) + (l ? 1 : 0),
        c = e.duration / h,
        u = 0,
        d = n.queue(),
        p = d.length;
        for ((o || !n.is(":visible")) && (n.css("opacity", 0).show(), u = 1), s = 1; h > s; s++) n.animate({
            opacity: u
        },
        c, e.easing),
        u = 1 - u;
        n.animate({
            opacity: u
        },
        c, e.easing),
        n.queue(function() {
            r && n.hide(),
            i()
        }),
        p > 1 && d.splice.apply(d, [1, 0].concat(d.splice(p, h + 1))),
        n.dequeue()
    }
} (jQuery),
function(t) {
    t.effects.effect.puff = function(e, i) {
        var s = t(this),
        n = t.effects.setMode(s, e.mode || "hide"),
        a = "hide" === n,
        o = parseInt(e.percent, 10) || 150,
        r = o / 100,
        l = {
            height: s.height(),
            width: s.width(),
            outerHeight: s.outerHeight(),
            outerWidth: s.outerWidth()
        };
        t.extend(e, {
            effect: "scale",
            queue: !1,
            fade: !0,
            mode: n,
            complete: i,
            percent: a ? o: 100,
            from: a ? l: {
                height: l.height * r,
                width: l.width * r,
                outerHeight: l.outerHeight * r,
                outerWidth: l.outerWidth * r
            }
        }),
        s.effect(e)
    },
    t.effects.effect.scale = function(e, i) {
        var s = t(this),
        n = t.extend(!0, {},
        e),
        a = t.effects.setMode(s, e.mode || "effect"),
        o = parseInt(e.percent, 10) || (0 === parseInt(e.percent, 10) ? 0 : "hide" === a ? 0 : 100),
        r = e.direction || "both",
        l = e.origin,
        h = {
            height: s.height(),
            width: s.width(),
            outerHeight: s.outerHeight(),
            outerWidth: s.outerWidth()
        },
        c = {
            y: "horizontal" !== r ? o / 100 : 1,
            x: "vertical" !== r ? o / 100 : 1
        };
        n.effect = "size",
        n.queue = !1,
        n.complete = i,
        "effect" !== a && (n.origin = l || ["middle", "center"], n.restore = !0),
        n.from = e.from || ("show" === a ? {
            height: 0,
            width: 0,
            outerHeight: 0,
            outerWidth: 0
        }: h),
        n.to = {
            height: h.height * c.y,
            width: h.width * c.x,
            outerHeight: h.outerHeight * c.y,
            outerWidth: h.outerWidth * c.x
        },
        n.fade && ("show" === a && (n.from.opacity = 0, n.to.opacity = 1), "hide" === a && (n.from.opacity = 1, n.to.opacity = 0)),
        s.effect(n)
    },
    t.effects.effect.size = function(e, i) {
        var s, n, a, o = t(this),
        r = ["position", "top", "bottom", "left", "right", "width", "height", "overflow", "opacity"],
        l = ["position", "top", "bottom", "left", "right", "overflow", "opacity"],
        h = ["width", "height", "overflow"],
        c = ["fontSize"],
        u = ["borderTopWidth", "borderBottomWidth", "paddingTop", "paddingBottom"],
        d = ["borderLeftWidth", "borderRightWidth", "paddingLeft", "paddingRight"],
        p = t.effects.setMode(o, e.mode || "effect"),
        f = e.restore || "effect" !== p,
        g = e.scale || "both",
        m = e.origin || ["middle", "center"],
        v = o.css("position"),
        _ = f ? r: l,
        b = {
            height: 0,
            width: 0,
            outerHeight: 0,
            outerWidth: 0
        };
        "show" === p && o.show(),
        s = {
            height: o.height(),
            width: o.width(),
            outerHeight: o.outerHeight(),
            outerWidth: o.outerWidth()
        },
        "toggle" === e.mode && "show" === p ? (o.from = e.to || b, o.to = e.from || s) : (o.from = e.from || ("show" === p ? b: s), o.to = e.to || ("hide" === p ? b: s)),
        a = {
            from: {
                y: o.from.height / s.height,
                x: o.from.width / s.width
            },
            to: {
                y: o.to.height / s.height,
                x: o.to.width / s.width
            }
        },
        ("box" === g || "both" === g) && (a.from.y !== a.to.y && (_ = _.concat(u), o.from = t.effects.setTransition(o, u, a.from.y, o.from), o.to = t.effects.setTransition(o, u, a.to.y, o.to)), a.from.x !== a.to.x && (_ = _.concat(d), o.from = t.effects.setTransition(o, d, a.from.x, o.from), o.to = t.effects.setTransition(o, d, a.to.x, o.to))),
        ("content" === g || "both" === g) && a.from.y !== a.to.y && (_ = _.concat(c).concat(h), o.from = t.effects.setTransition(o, c, a.from.y, o.from), o.to = t.effects.setTransition(o, c, a.to.y, o.to)),
        t.effects.save(o, _),
        o.show(),
        t.effects.createWrapper(o),
        o.css("overflow", "hidden").css(o.from),
        m && (n = t.effects.getBaseline(m, s), o.from.top = (s.outerHeight - o.outerHeight()) * n.y, o.from.left = (s.outerWidth - o.outerWidth()) * n.x, o.to.top = (s.outerHeight - o.to.outerHeight) * n.y, o.to.left = (s.outerWidth - o.to.outerWidth) * n.x),
        o.css(o.from),
        ("content" === g || "both" === g) && (u = u.concat(["marginTop", "marginBottom"]).concat(c), d = d.concat(["marginLeft", "marginRight"]), h = r.concat(u).concat(d), o.find("*[width]").each(function() {
            var i = t(this),
            s = {
                height: i.height(),
                width: i.width(),
                outerHeight: i.outerHeight(),
                outerWidth: i.outerWidth()
            };
            f && t.effects.save(i, h),
            i.from = {
                height: s.height * a.from.y,
                width: s.width * a.from.x,
                outerHeight: s.outerHeight * a.from.y,
                outerWidth: s.outerWidth * a.from.x
            },
            i.to = {
                height: s.height * a.to.y,
                width: s.width * a.to.x,
                outerHeight: s.height * a.to.y,
                outerWidth: s.width * a.to.x
            },
            a.from.y !== a.to.y && (i.from = t.effects.setTransition(i, u, a.from.y, i.from), i.to = t.effects.setTransition(i, u, a.to.y, i.to)),
            a.from.x !== a.to.x && (i.from = t.effects.setTransition(i, d, a.from.x, i.from), i.to = t.effects.setTransition(i, d, a.to.x, i.to)),
            i.css(i.from),
            i.animate(i.to, e.duration, e.easing,
            function() {
                f && t.effects.restore(i, h)
            })
        })),
        o.animate(o.to, {
            queue: !1,
            duration: e.duration,
            easing: e.easing,
            complete: function() {
                0 === o.to.opacity && o.css("opacity", o.from.opacity),
                "hide" === p && o.hide(),
                t.effects.restore(o, _),
                f || ("static" === v ? o.css({
                    position: "relative",
                    top: o.to.top,
                    left: o.to.left
                }) : t.each(["top", "left"],
                function(t, e) {
                    o.css(e,
                    function(e, i) {
                        var s = parseInt(i, 10),
                        n = t ? o.to.left: o.to.top;
                        return "auto" === i ? n + "px": s + n + "px"
                    })
                })),
                t.effects.removeWrapper(o),
                i()
            }
        })
    }
} (jQuery),
function(t) {
    t.effects.effect.shake = function(e, i) {
        var s, n = t(this),
        a = ["position", "top", "bottom", "left", "right", "height", "width"],
        o = t.effects.setMode(n, e.mode || "effect"),
        r = e.direction || "left",
        l = e.distance || 20,
        h = e.times || 3,
        c = 2 * h + 1,
        u = Math.round(e.duration / c),
        d = "up" === r || "down" === r ? "top": "left",
        p = "up" === r || "left" === r,
        f = {},
        g = {},
        m = {},
        v = n.queue(),
        _ = v.length;
        for (t.effects.save(n, a), n.show(), t.effects.createWrapper(n), f[d] = (p ? "-=": "+=") + l, g[d] = (p ? "+=": "-=") + 2 * l, m[d] = (p ? "-=": "+=") + 2 * l, n.animate(f, u, e.easing), s = 1; h > s; s++) n.animate(g, u, e.easing).animate(m, u, e.easing);
        n.animate(g, u, e.easing).animate(f, u / 2, e.easing).queue(function() {
            "hide" === o && n.hide(),
            t.effects.restore(n, a),
            t.effects.removeWrapper(n),
            i()
        }),
        _ > 1 && v.splice.apply(v, [1, 0].concat(v.splice(_, c + 1))),
        n.dequeue()
    }
} (jQuery),
function(t) {
    t.effects.effect.slide = function(e, i) {
        var s, n = t(this),
        a = ["position", "top", "bottom", "left", "right", "width", "height"],
        o = t.effects.setMode(n, e.mode || "show"),
        r = "show" === o,
        l = e.direction || "left",
        h = "up" === l || "down" === l ? "top": "left",
        c = "up" === l || "left" === l,
        u = {};
        t.effects.save(n, a),
        n.show(),
        s = e.distance || n["top" === h ? "outerHeight": "outerWidth"](!0),
        t.effects.createWrapper(n).css({
            overflow: "hidden"
        }),
        r && n.css(h, c ? isNaN(s) ? "-" + s: -s: s),
        u[h] = (r ? c ? "+=": "-=": c ? "-=": "+=") + s,
        n.animate(u, {
            queue: !1,
            duration: e.duration,
            easing: e.easing,
            complete: function() {
                "hide" === o && n.hide(),
                t.effects.restore(n, a),
                t.effects.removeWrapper(n),
                i()
            }
        })
    }
} (jQuery),
function(t) {
    t.effects.effect.transfer = function(e, i) {
        var s = t(this),
        n = t(e.to),
        a = "fixed" === n.css("position"),
        o = t("body"),
        r = a ? o.scrollTop() : 0,
        l = a ? o.scrollLeft() : 0,
        h = n.offset(),
        c = {
            top: h.top - r,
            left: h.left - l,
            height: n.innerHeight(),
            width: n.innerWidth()
        },
        d = s.offset(),
        u = t("<div class='ui-effects-transfer'></div>").appendTo(document.body).addClass(e.className).css({
            top: d.top - r,
            left: d.left - l,
            height: s.innerHeight(),
            width: s.innerWidth(),
            position: a ? "fixed": "absolute"
        }).animate(c, e.duration, e.easing,
        function() {
            u.remove(),
            i()
        })
    }
} (jQuery),
jQuery(function($) {
    $.datepicker.regional["zh-CN"] = {
        clearText: "清除",
        clearStatus: "清除已选日期",
        closeText: "关闭",
        closeStatus: "不改变当前选择",
        prevText: "<上月",
        prevStatus: "显示上月",
        prevBigText: "<<",
        prevBigStatus: "显示上一年",
        nextText: "下月>",
        nextStatus: "显示下月",
        nextBigText: ">>",
        nextBigStatus: "显示下一年",
        currentText: "今天",
        currentStatus: "显示本月",
        monthNames: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
        monthNamesShort: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
        monthStatus: "选择月份",
        yearStatus: "选择年份",
        weekHeader: "周",
        weekStatus: "年内周次",
        dayNames: ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"],
        dayNamesShort: ["周日", "周一", "周二", "周三", "周四", "周五", "周六"],
        dayNamesMin: ["日", "一", "二", "三", "四", "五", "六"],
        dayStatus: "设置 DD 为一周起始",
        dateStatus: "选择 m月 d日, DD",
        dateFormat: "yy-mm-dd",
        firstDay: 1,
        initStatus: "请选择日期",
        isRTL: !1
    },
    $.datepicker.setDefaults($.datepicker.regional["zh-CN"])
}),
function() {
    "use strict";
    function _parseFlashVersion(flashVersion) {
        return flashVersion.replace(/,/g, ".").replace(/[^0-9\.]/g, "")
    }
    function _isFlashVersionSupported(flashVersion) {
        return parseFloat(_parseFlashVersion(flashVersion)) >= 10
    }
    var currentElement, flashState = {
        bridge: null,
        version: "0.0.0",
        disabled: null,
        outdated: null,
        ready: null
    },
    _clipData = {},
    clientIdCounter = 0,
    _clientMeta = {},
    elementIdCounter = 0,
    _elementMeta = {},
    _amdModuleId = null,
    _cjsModuleId = null,
    _swfPath = function() {
        var i, jsDir, tmpJsPath, jsPath, swfPath = "ZeroClipboard.swf";
        if (document.currentScript && (jsPath = document.currentScript.src));
        else {
            var scripts = document.getElementsByTagName("script");
            if ("readyState" in scripts[0]) for (i = scripts.length; i--&&("interactive" !== scripts[i].readyState || !(jsPath = scripts[i].src)););
            else if ("loading" === document.readyState) jsPath = scripts[scripts.length - 1].src;
            else {
                for (i = scripts.length; i--;) {
                    if (tmpJsPath = scripts[i].src, !tmpJsPath) {
                        jsDir = null;
                        break
                    }
                    if (tmpJsPath = tmpJsPath.split("#")[0].split("?")[0], tmpJsPath = tmpJsPath.slice(0, tmpJsPath.lastIndexOf("/") + 1), null == jsDir) jsDir = tmpJsPath;
                    else if (jsDir !== tmpJsPath) {
                        jsDir = null;
                        break
                    }
                }
                null !== jsDir && (jsPath = jsDir)
            }
        }
        return jsPath && (jsPath = jsPath.split("#")[0].split("?")[0], swfPath = jsPath.slice(0, jsPath.lastIndexOf("/") + 1) + swfPath),
        swfPath
    } (),
    _camelizeCssPropName = function() {
        var matcherRegex = /\-([a-z])/g,
        replacerFn = function(match, group) {
            return group.toUpperCase()
        };
        return function(prop) {
            return prop.replace(matcherRegex, replacerFn)
        }
    } (),
    _getStyle = function(el, prop) {
        var value, camelProp, tagName;
        return window.getComputedStyle ? value = window.getComputedStyle(el, null).getPropertyValue(prop) : (camelProp = _camelizeCssPropName(prop), value = el.currentStyle ? el.currentStyle[camelProp] : el.style[camelProp]),
        "cursor" !== prop || value && "auto" !== value || (tagName = el.tagName.toLowerCase(), "a" !== tagName) ? value: "pointer"
    },
    _elementMouseOver = function(event) {
        event || (event = window.event);
        var target;
        this !== window ? target = this: event.target ? target = event.target: event.srcElement && (target = event.srcElement),
        ZeroClipboard.activate(target)
    },
    _addEventHandler = function(element, method, func) {
        element && 1 === element.nodeType && (element.addEventListener ? element.addEventListener(method, func, !1) : element.attachEvent && element.attachEvent("on" + method, func))
    },
    _removeEventHandler = function(element, method, func) {
        element && 1 === element.nodeType && (element.removeEventListener ? element.removeEventListener(method, func, !1) : element.detachEvent && element.detachEvent("on" + method, func))
    },
    _addClass = function(element, value) {
        if (!element || 1 !== element.nodeType) return element;
        if (element.classList) return element.classList.contains(value) || element.classList.add(value),
        element;
        if (value && "string" == typeof value) {
            var classNames = (value || "").split(/\s+/);
            if (1 === element.nodeType) if (element.className) {
                for (var className = " " + element.className + " ",
                setClass = element.className,
                c = 0,
                cl = classNames.length; cl > c; c++) className.indexOf(" " + classNames[c] + " ") < 0 && (setClass += " " + classNames[c]);
                element.className = setClass.replace(/^\s+|\s+$/g, "")
            } else element.className = value
        }
        return element
    },
    _removeClass = function(element, value) {
        if (!element || 1 !== element.nodeType) return element;
        if (element.classList) return element.classList.contains(value) && element.classList.remove(value),
        element;
        if (value && "string" == typeof value || void 0 === value) {
            var classNames = (value || "").split(/\s+/);
            if (1 === element.nodeType && element.className) if (value) {
                for (var className = (" " + element.className + " ").replace(/[\n\t]/g, " "), c = 0, cl = classNames.length; cl > c; c++) className = className.replace(" " + classNames[c] + " ", " ");
                element.className = className.replace(/^\s+|\s+$/g, "")
            } else element.className = ""
        }
        return element
    },
    _getZoomFactor = function() {
        var rect, physicalWidth, logicalWidth, zoomFactor = 1;
        return "function" == typeof document.body.getBoundingClientRect && (rect = document.body.getBoundingClientRect(), physicalWidth = rect.right - rect.left, logicalWidth = document.body.offsetWidth, zoomFactor = Math.round(100 * (physicalWidth / logicalWidth)) / 100),
        zoomFactor
    },
    _getDOMObjectPosition = function(obj, defaultZIndex) {
        var info = {
            left: 0,
            top: 0,
            width: 0,
            height: 0,
            zIndex: _getSafeZIndex(defaultZIndex) - 1
        };
        if (obj.getBoundingClientRect) {
            var pageXOffset, pageYOffset, zoomFactor, rect = obj.getBoundingClientRect();
            "pageXOffset" in window && "pageYOffset" in window ? (pageXOffset = window.pageXOffset, pageYOffset = window.pageYOffset) : (zoomFactor = _getZoomFactor(), pageXOffset = Math.round(document.documentElement.scrollLeft / zoomFactor), pageYOffset = Math.round(document.documentElement.scrollTop / zoomFactor));
            var leftBorderWidth = document.documentElement.clientLeft || 0,
            topBorderWidth = document.documentElement.clientTop || 0;
            info.left = rect.left + pageXOffset - leftBorderWidth,
            info.top = rect.top + pageYOffset - topBorderWidth,
            info.width = "width" in rect ? rect.width: rect.right - rect.left,
            info.height = "height" in rect ? rect.height: rect.bottom - rect.top
        }
        return info
    },
    _cacheBust = function(path, options) {
        var cacheBust = null == options || options && options.cacheBust === !0 && options.useNoCache === !0;
        return cacheBust ? ( - 1 === path.indexOf("?") ? "?": "&") + "noCache=" + (new Date).getTime() : ""
    },
    _vars = function(options) {
        var i, len, domain, str = [],
        domains = [],
        trustedOriginsExpanded = [];
        if (options.trustedOrigins && ("string" == typeof options.trustedOrigins ? domains.push(options.trustedOrigins) : "object" == typeof options.trustedOrigins && "length" in options.trustedOrigins && (domains = domains.concat(options.trustedOrigins))), options.trustedDomains && ("string" == typeof options.trustedDomains ? domains.push(options.trustedDomains) : "object" == typeof options.trustedDomains && "length" in options.trustedDomains && (domains = domains.concat(options.trustedDomains))), domains.length) for (i = 0, len = domains.length; len > i; i++) if (domains.hasOwnProperty(i) && domains[i] && "string" == typeof domains[i]) {
            if (domain = _extractDomain(domains[i]), !domain) continue;
            if ("*" === domain) {
                trustedOriginsExpanded = [domain];
                break
            }
            trustedOriginsExpanded.push.apply(trustedOriginsExpanded, [domain, "//" + domain, window.location.protocol + "//" + domain])
        }
        return trustedOriginsExpanded.length && str.push("trustedOrigins=" + encodeURIComponent(trustedOriginsExpanded.join(","))),
        "string" == typeof options.jsModuleId && options.jsModuleId && str.push("jsModuleId=" + encodeURIComponent(options.jsModuleId)),
        str.join("&")
    },
    _inArray = function(elem, array, fromIndex) {
        if ("function" == typeof array.indexOf) return array.indexOf(elem, fromIndex);
        var i, len = array.length;
        for ("undefined" == typeof fromIndex ? fromIndex = 0 : 0 > fromIndex && (fromIndex = len + fromIndex), i = fromIndex; len > i; i++) if (array.hasOwnProperty(i) && array[i] === elem) return i;
        return - 1
    },
    _prepClip = function(elements) {
        if ("string" == typeof elements) throw new TypeError("ZeroClipboard doesn't accept query strings.");
        return elements.length ? elements: [elements]
    },
    _dispatchCallback = function(func, context, args, async) {
        async ? window.setTimeout(function() {
            func.apply(context, args)
        },
        0) : func.apply(context, args)
    },
    _getSafeZIndex = function(val) {
        var zIndex, tmp;
        return val && ("number" == typeof val && val > 0 ? zIndex = val: "string" == typeof val && (tmp = parseInt(val, 10)) && !isNaN(tmp) && tmp > 0 && (zIndex = tmp)),
        zIndex || ("number" == typeof _globalConfig.zIndex && _globalConfig.zIndex > 0 ? zIndex = _globalConfig.zIndex: "string" == typeof _globalConfig.zIndex && (tmp = parseInt(_globalConfig.zIndex, 10)) && !isNaN(tmp) && tmp > 0 && (zIndex = tmp)),
        zIndex || 0
    },
    _deprecationWarning = function(deprecatedApiName, debugEnabled) {
        if (deprecatedApiName && debugEnabled !== !1 && "undefined" != typeof console && console && (console.warn || console.log)) {
            var deprecationWarning = "`" + deprecatedApiName + "` is deprecated. See docs for more info:\n    https://github.com/zeroclipboard/zeroclipboard/blob/master/docs/instructions.md#deprecations";
            console.warn ? console.warn(deprecationWarning) : console.log(deprecationWarning)
        }
    },
    _extend = function() {
        var i, len, arg, prop, src, copy, target = arguments[0] || {};
        for (i = 1, len = arguments.length; len > i; i++) if (null != (arg = arguments[i])) for (prop in arg) if (arg.hasOwnProperty(prop)) {
            if (src = target[prop], copy = arg[prop], target === copy) continue;
            void 0 !== copy && (target[prop] = copy)
        }
        return target
    },
    _extractDomain = function(originOrUrl) {
        if (null == originOrUrl || "" === originOrUrl) return null;
        if (originOrUrl = originOrUrl.replace(/^\s+|\s+$/g, ""), "" === originOrUrl) return null;
        var protocolIndex = originOrUrl.indexOf("//");
        originOrUrl = -1 === protocolIndex ? originOrUrl: originOrUrl.slice(protocolIndex + 2);
        var pathIndex = originOrUrl.indexOf("/");
        return originOrUrl = -1 === pathIndex ? originOrUrl: -1 === protocolIndex || 0 === pathIndex ? null: originOrUrl.slice(0, pathIndex),
        originOrUrl && ".swf" === originOrUrl.slice( - 4).toLowerCase() ? null: originOrUrl || null
    },
    _determineScriptAccess = function() {
        var _extractAllDomains = function(origins, resultsArray) {
            var i, len, tmp;
            if (null != origins && "*" !== resultsArray[0] && ("string" == typeof origins && (origins = [origins]), "object" == typeof origins && "length" in origins)) for (i = 0, len = origins.length; len > i; i++) if (origins.hasOwnProperty(i) && (tmp = _extractDomain(origins[i]))) {
                if ("*" === tmp) {
                    resultsArray.length = 0,
                    resultsArray.push("*");
                    break
                } - 1 === _inArray(tmp, resultsArray) && resultsArray.push(tmp)
            }
        },
        _accessLevelLookup = {
            always: "always",
            samedomain: "sameDomain",
            never: "never"
        };
        return function(currentDomain, configOptions) {
            var asaLower, allowScriptAccess = configOptions.allowScriptAccess;
            if ("string" == typeof allowScriptAccess && (asaLower = allowScriptAccess.toLowerCase()) && /^always|samedomain|never$/.test(asaLower)) return _accessLevelLookup[asaLower];
            var swfDomain = _extractDomain(configOptions.moviePath);
            null === swfDomain && (swfDomain = currentDomain);
            var trustedDomains = [];
            _extractAllDomains(configOptions.trustedOrigins, trustedDomains),
            _extractAllDomains(configOptions.trustedDomains, trustedDomains);
            var len = trustedDomains.length;
            if (len > 0) {
                if (1 === len && "*" === trustedDomains[0]) return "always";
                if ( - 1 !== _inArray(currentDomain, trustedDomains)) return 1 === len && currentDomain === swfDomain ? "sameDomain": "always"
            }
            return "never"
        }
    } (),
    _objectKeys = function(obj) {
        if (null == obj) return [];
        if (Object.keys) return Object.keys(obj);
        var keys = [];
        for (var prop in obj) obj.hasOwnProperty(prop) && keys.push(prop);
        return keys
    },
    _deleteOwnProperties = function(obj) {
        if (obj) for (var prop in obj) obj.hasOwnProperty(prop) && delete obj[prop];
        return obj
    },
    _detectFlashSupport = function() {
        var hasFlash = !1;
        if ("boolean" == typeof flashState.disabled) hasFlash = flashState.disabled === !1;
        else {
            if ("function" == typeof ActiveXObject) try {
                new ActiveXObject("ShockwaveFlash.ShockwaveFlash") && (hasFlash = !0)
            } catch(error) {} ! hasFlash && navigator.mimeTypes["application/x-shockwave-flash"] && (hasFlash = !0)
        }
        return hasFlash
    },
    ZeroClipboard = function(elements, options) {
        return this instanceof ZeroClipboard ? (this.id = "" + clientIdCounter++, _clientMeta[this.id] = {
            instance: this,
            elements: [],
            handlers: {}
        },
        elements && this.clip(elements), "undefined" != typeof options && (_deprecationWarning("new ZeroClipboard(elements, options)", _globalConfig.debug), ZeroClipboard.config(options)), this.options = ZeroClipboard.config(), "boolean" != typeof flashState.disabled && (flashState.disabled = !_detectFlashSupport()), flashState.disabled === !1 && flashState.outdated !== !0 && null === flashState.bridge && (flashState.outdated = !1, flashState.ready = !1, _bridge()), void 0) : new ZeroClipboard(elements, options)
    };
    ZeroClipboard.prototype.setText = function(newText) {
        return newText && "" !== newText && (_clipData["text/plain"] = newText, flashState.ready === !0 && flashState.bridge && flashState.bridge.setText(newText)),
        this
    },
    ZeroClipboard.prototype.setSize = function(width, height) {
        return flashState.ready === !0 && flashState.bridge && flashState.bridge.setSize(width, height),
        this
    };
    var _setHandCursor = function(enabled) {
        flashState.ready === !0 && flashState.bridge && flashState.bridge.setHandCursor(enabled)
    };
    ZeroClipboard.prototype.destroy = function() {
        this.unclip(),
        this.off(),
        delete _clientMeta[this.id]
    };
    var _getAllClients = function() {
        var i, len, client, clients = [],
        clientIds = _objectKeys(_clientMeta);
        for (i = 0, len = clientIds.length; len > i; i++) client = _clientMeta[clientIds[i]].instance,
        client && client instanceof ZeroClipboard && clients.push(client);
        return clients
    };
    ZeroClipboard.version = "2.0.0-alpha.1";
    var _globalConfig = {
        swfPath: _swfPath,
        trustedDomains: window.location.host ? [window.location.host] : [],
        cacheBust: !0,
        forceHandCursor: !1,
        zIndex: 999999999,
        debug: !0,
        title: null,
        autoActivate: !0
    };
    ZeroClipboard.config = function(options) {
        "object" == typeof options && null !== options && _extend(_globalConfig, options); {
            if ("string" != typeof options || !options) {
                var copy = {};
                for (var prop in _globalConfig) _globalConfig.hasOwnProperty(prop) && (copy[prop] = "object" == typeof _globalConfig[prop] && null !== _globalConfig[prop] ? "length" in _globalConfig[prop] ? _globalConfig[prop].slice(0) : _extend({},
                _globalConfig[prop]) : _globalConfig[prop]);
                return copy
            }
            if (_globalConfig.hasOwnProperty(options)) return _globalConfig[options]
        }
    },
    ZeroClipboard.destroy = function() {
        ZeroClipboard.deactivate();
        for (var clientId in _clientMeta) if (_clientMeta.hasOwnProperty(clientId) && _clientMeta[clientId]) {
            var client = _clientMeta[clientId].instance;
            client && "function" == typeof client.destroy && client.destroy()
        }
        var htmlBridge = _getHtmlBridge(flashState.bridge);
        htmlBridge && htmlBridge.parentNode && (htmlBridge.parentNode.removeChild(htmlBridge), flashState.ready = null, flashState.bridge = null)
    },
    ZeroClipboard.activate = function(element) {
        currentElement && (_removeClass(currentElement, _globalConfig.hoverClass), _removeClass(currentElement, _globalConfig.activeClass)),
        currentElement = element,
        _addClass(element, _globalConfig.hoverClass),
        _reposition();
        var newTitle = _globalConfig.title || element.getAttribute("title");
        if (newTitle) {
            var htmlBridge = _getHtmlBridge(flashState.bridge);
            htmlBridge && htmlBridge.setAttribute("title", newTitle)
        }
        var useHandCursor = _globalConfig.forceHandCursor === !0 || "pointer" === _getStyle(element, "cursor");
        _setHandCursor(useHandCursor)
    },
    ZeroClipboard.deactivate = function() {
        var htmlBridge = _getHtmlBridge(flashState.bridge);
        htmlBridge && (htmlBridge.style.left = "0px", htmlBridge.style.top = "-9999px", htmlBridge.removeAttribute("title")),
        currentElement && (_removeClass(currentElement, _globalConfig.hoverClass), _removeClass(currentElement, _globalConfig.activeClass), currentElement = null)
    };
    var _bridge = function() {
        var flashBridge, len, container = document.getElementById("global-zeroclipboard-html-bridge");
        if (!container) {
            var opts = ZeroClipboard.config();
            opts.jsModuleId = "string" == typeof _amdModuleId && _amdModuleId || "string" == typeof _cjsModuleId && _cjsModuleId || null;
            var allowScriptAccess = _determineScriptAccess(window.location.host, _globalConfig),
            flashvars = _vars(opts),
            swfUrl = _globalConfig.moviePath + _cacheBust(_globalConfig.moviePath, _globalConfig),
            html = '      <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" id="global-zeroclipboard-flash-bridge" width="100%" height="100%">         <param name="movie" value="' + swfUrl + '"/>         <param name="allowScriptAccess" value="' + allowScriptAccess + '"/>         <param name="scale" value="exactfit"/>         <param name="loop" value="false"/>         <param name="menu" value="false"/>         <param name="quality" value="best" />         <param name="bgcolor" value="#ffffff"/>         <param name="wmode" value="transparent"/>         <param name="flashvars" value="' + flashvars + '"/>         <embed src="' + swfUrl + '"           loop="false" menu="false"           quality="best" bgcolor="#ffffff"           width="100%" height="100%"           name="global-zeroclipboard-flash-bridge"           allowScriptAccess="' + allowScriptAccess + '"           allowFullScreen="false"           type="application/x-shockwave-flash"           wmode="transparent"           pluginspage="http://www.macromedia.com/go/getflashplayer"           flashvars="' + flashvars + '"           scale="exactfit">         </embed>       </object>';
            container = document.createElement("div"),
            container.id = "global-zeroclipboard-html-bridge",
            container.setAttribute("class", "global-zeroclipboard-container"),
            container.style.position = "absolute",
            container.style.left = "0px",
            container.style.top = "-9999px",
            container.style.width = "15px",
            container.style.height = "15px",
            container.style.zIndex = "" + _getSafeZIndex(_globalConfig.zIndex),
            document.body.appendChild(container),
            container.innerHTML = html
        }
        flashBridge = document["global-zeroclipboard-flash-bridge"],
        flashBridge && (len = flashBridge.length) && (flashBridge = flashBridge[len - 1]),
        flashState.bridge = flashBridge || container.children[0].lastElementChild
    },
    _getHtmlBridge = function(flashBridge) {
        for (var isFlashElement = /^OBJECT|EMBED$/,
        htmlBridge = flashBridge && flashBridge.parentNode; htmlBridge && isFlashElement.test(htmlBridge.nodeName) && htmlBridge.parentNode;) htmlBridge = htmlBridge.parentNode;
        return htmlBridge || null
    },
    _reposition = function() {
        if (currentElement) {
            var pos = _getDOMObjectPosition(currentElement, _globalConfig.zIndex),
            htmlBridge = _getHtmlBridge(flashState.bridge);
            htmlBridge && (htmlBridge.style.top = pos.top + "px", htmlBridge.style.left = pos.left + "px", htmlBridge.style.width = pos.width + "px", htmlBridge.style.height = pos.height + "px", htmlBridge.style.zIndex = pos.zIndex + 1),
            flashState.ready === !0 && flashState.bridge && flashState.bridge.setSize(pos.width, pos.height)
        }
        return this
    };
    ZeroClipboard.prototype.on = function(eventName, func) {
        var i, len, events, added = {},
        handlers = _clientMeta[this.id] && _clientMeta[this.id].handlers;
        if ("string" == typeof eventName && eventName) events = eventName.toLowerCase().split(/\s+/);
        else if ("object" == typeof eventName && eventName && "undefined" == typeof func) for (i in eventName) eventName.hasOwnProperty(i) && "string" == typeof i && i && "function" == typeof eventName[i] && this.on(i, eventName[i]);
        if (events && events.length) {
            for (i = 0, len = events.length; len > i; i++) eventName = events[i].replace(/^on/, ""),
            added[eventName] = !0,
            handlers[eventName] || (handlers[eventName] = []),
            handlers[eventName].push(func);
            added.noflash && flashState.disabled && _receiveEvent.call(this, "noflash", {}),
            added.wrongflash && flashState.outdated && _receiveEvent.call(this, "wrongflash", {
                flashVersion: flashState.version
            }),
            added.load && flashState.ready && _receiveEvent.call(this, "load", {
                flashVersion: flashState.version
            })
        }
        return this
    },
    ZeroClipboard.prototype.off = function(eventName, func) {
        var i, len, foundIndex, events, perEventHandlers, handlers = _clientMeta[this.id] && _clientMeta[this.id].handlers;
        if (0 === arguments.length) events = _objectKeys(handlers);
        else if ("string" == typeof eventName && eventName) events = eventName.split(/\s+/);
        else if ("object" == typeof eventName && eventName && "undefined" == typeof func) for (i in eventName) eventName.hasOwnProperty(i) && "string" == typeof i && i && "function" == typeof eventName[i] && this.off(i, eventName[i]);
        if (events && events.length) for (i = 0, len = events.length; len > i; i++) if (eventName = events[i].toLowerCase().replace(/^on/, ""), perEventHandlers = handlers[eventName], perEventHandlers && perEventHandlers.length) if (func) for (foundIndex = _inArray(func, perEventHandlers); - 1 !== foundIndex;) perEventHandlers.splice(foundIndex, 1),
        foundIndex = _inArray(func, perEventHandlers, foundIndex);
        else handlers[eventName].length = 0;
        return this
    },
    ZeroClipboard.prototype.handlers = function(eventName) {
        var prop, copy = null,
        handlers = _clientMeta[this.id] && _clientMeta[this.id].handlers;
        if (handlers) {
            if ("string" == typeof eventName && eventName) return handlers[eventName] ? handlers[eventName].slice(0) : null;
            copy = {};
            for (prop in handlers) handlers.hasOwnProperty(prop) && handlers[prop] && (copy[prop] = handlers[prop].slice(0))
        }
        return copy
    };
    var _dispatchClientCallbacks = function(eventName, context, args, async) {
        var handlers = _clientMeta[this.id] && _clientMeta[this.id].handlers[eventName];
        if (handlers && handlers.length) {
            var i, len, func, originalContext = context || this;
            for (i = 0, len = handlers.length; len > i; i++) func = handlers[i],
            context = originalContext,
            "string" == typeof func && "function" == typeof window[func] && (func = window[func]),
            "object" == typeof func && func && "function" == typeof func.handleEvent && (context = func, func = func.handleEvent),
            "function" == typeof func && _dispatchCallback(func, context, args, async)
        }
        return this
    };
    ZeroClipboard.prototype.clip = function(elements) {
        elements = _prepClip(elements);
        for (var i = 0; i < elements.length; i++) if (elements.hasOwnProperty(i) && elements[i] && 1 === elements[i].nodeType) {
            elements[i].zcClippingId ? -1 === _inArray(this.id, _elementMeta[elements[i].zcClippingId]) && _elementMeta[elements[i].zcClippingId].push(this.id) : (elements[i].zcClippingId = "zcClippingId_" + elementIdCounter++, _elementMeta[elements[i].zcClippingId] = [this.id], _globalConfig.autoActivate === !0 && _addEventHandler(elements[i], "mouseover", _elementMouseOver));
            var clippedElements = _clientMeta[this.id].elements; - 1 === _inArray(elements[i], clippedElements) && clippedElements.push(elements[i])
        }
        return this
    },
    ZeroClipboard.prototype.unclip = function(elements) {
        var meta = _clientMeta[this.id];
        if (meta) {
            var arrayIndex, clippedElements = meta.elements;
            elements = "undefined" == typeof elements ? clippedElements.slice(0) : _prepClip(elements);
            for (var i = elements.length; i--;) if (elements.hasOwnProperty(i) && elements[i] && 1 === elements[i].nodeType) {
                for (arrayIndex = 0; - 1 !== (arrayIndex = _inArray(elements[i], clippedElements, arrayIndex));) clippedElements.splice(arrayIndex, 1);
                var clientIds = _elementMeta[elements[i].zcClippingId];
                if (clientIds) {
                    for (arrayIndex = 0; - 1 !== (arrayIndex = _inArray(this.id, clientIds, arrayIndex));) clientIds.splice(arrayIndex, 1);
                    0 === clientIds.length && (_globalConfig.autoActivate === !0 && _removeEventHandler(elements[i], "mouseover", _elementMouseOver), delete elements[i].zcClippingId)
                }
            }
        }
        return this
    },
    ZeroClipboard.prototype.elements = function() {
        var meta = _clientMeta[this.id];
        return meta && meta.elements ? meta.elements.slice(0) : []
    };
    var _getAllClientsClippedToElement = function(element) {
        var elementMetaId, clientIds, i, len, client, clients = [];
        if (element && 1 === element.nodeType && (elementMetaId = element.zcClippingId) && _elementMeta.hasOwnProperty(elementMetaId) && (clientIds = _elementMeta[elementMetaId], clientIds && clientIds.length)) for (i = 0, len = clientIds.length; len > i; i++) client = _clientMeta[clientIds[i]].instance,
        client && client instanceof ZeroClipboard && clients.push(client);
        return clients
    };
    _globalConfig.hoverClass = "zeroclipboard-is-hover",
    _globalConfig.activeClass = "zeroclipboard-is-active",
    _globalConfig.trustedOrigins = null,
    _globalConfig.allowScriptAccess = null,
    _globalConfig.useNoCache = !0,
    _globalConfig.moviePath = "ZeroClipboard.swf",
    ZeroClipboard.detectFlashSupport = function() {
        return _deprecationWarning("ZeroClipboard.detectFlashSupport", _globalConfig.debug),
        _detectFlashSupport()
    },
    ZeroClipboard.dispatch = function(eventName, args) {
        if ("string" == typeof eventName && eventName) {
            var cleanEventName = eventName.toLowerCase().replace(/^on/, "");
            if (cleanEventName) for (var clients = currentElement ? _getAllClientsClippedToElement(currentElement) : _getAllClients(), i = 0, len = clients.length; len > i; i++) _receiveEvent.call(clients[i], cleanEventName, args)
        }
    },
    ZeroClipboard.prototype.setHandCursor = function(enabled) {
        return _deprecationWarning("ZeroClipboard.prototype.setHandCursor", _globalConfig.debug),
        enabled = "boolean" == typeof enabled ? enabled: !!enabled,
        _setHandCursor(enabled),
        _globalConfig.forceHandCursor = enabled,
        this
    },
    ZeroClipboard.prototype.reposition = function() {
        return _deprecationWarning("ZeroClipboard.prototype.reposition", _globalConfig.debug),
        _reposition()
    },
    ZeroClipboard.prototype.receiveEvent = function(eventName, args) {
        if (_deprecationWarning("ZeroClipboard.prototype.receiveEvent", _globalConfig.debug), "string" == typeof eventName && eventName) {
            var cleanEventName = eventName.toLowerCase().replace(/^on/, "");
            cleanEventName && _receiveEvent.call(this, cleanEventName, args)
        }
    },
    ZeroClipboard.prototype.setCurrent = function(element) {
        return _deprecationWarning("ZeroClipboard.prototype.setCurrent", _globalConfig.debug),
        ZeroClipboard.activate(element),
        this
    },
    ZeroClipboard.prototype.resetBridge = function() {
        return _deprecationWarning("ZeroClipboard.prototype.resetBridge", _globalConfig.debug),
        ZeroClipboard.deactivate(),
        this
    },
    ZeroClipboard.prototype.setTitle = function(newTitle) {
        if (_deprecationWarning("ZeroClipboard.prototype.setTitle", _globalConfig.debug), newTitle = newTitle || _globalConfig.title || currentElement && currentElement.getAttribute("title")) {
            var htmlBridge = _getHtmlBridge(flashState.bridge);
            htmlBridge && htmlBridge.setAttribute("title", newTitle)
        }
        return this
    },
    ZeroClipboard.setDefaults = function(options) {
        _deprecationWarning("ZeroClipboard.setDefaults", _globalConfig.debug),
        ZeroClipboard.config(options)
    },
    ZeroClipboard.prototype.addEventListener = function(eventName, func) {
        return _deprecationWarning("ZeroClipboard.prototype.addEventListener", _globalConfig.debug),
        this.on(eventName, func)
    },
    ZeroClipboard.prototype.removeEventListener = function(eventName, func) {
        return _deprecationWarning("ZeroClipboard.prototype.removeEventListener", _globalConfig.debug),
        this.off(eventName, func)
    },
    ZeroClipboard.prototype.ready = function() {
        return _deprecationWarning("ZeroClipboard.prototype.ready", _globalConfig.debug),
        flashState.ready === !0
    };
    var _receiveEvent = function(eventName, args) {
        eventName = eventName.toLowerCase().replace(/^on/, "");
        var cleanVersion = args && args.flashVersion && _parseFlashVersion(args.flashVersion) || null,
        element = currentElement,
        performCallbackAsync = !0;
        switch (eventName) {
        case "load":
            if (cleanVersion) {
                if (!_isFlashVersionSupported(cleanVersion)) return _receiveEvent.call(this, "onWrongFlash", {
                    flashVersion: cleanVersion
                }),
                void 0;
                flashState.outdated = !1,
                flashState.ready = !0,
                flashState.version = cleanVersion
            }
            break;
        case "wrongflash":
            cleanVersion && !_isFlashVersionSupported(cleanVersion) && (flashState.outdated = !0, flashState.ready = !1, flashState.version = cleanVersion);
            break;
        case "mouseover":
            _addClass(element, _globalConfig.hoverClass);
            break;
        case "mouseout":
            _globalConfig.autoActivate === !0 && ZeroClipboard.deactivate();
            break;
        case "mousedown":
            _addClass(element, _globalConfig.activeClass);
            break;
        case "mouseup":
            _removeClass(element, _globalConfig.activeClass);
            break;
        case "datarequested":
            var targetId = element.getAttribute("data-clipboard-target"),
            targetEl = targetId ? document.getElementById(targetId) : null;
            if (targetEl) {
                var textContent = targetEl.value || targetEl.textContent || targetEl.innerText;
                textContent && this.setText(textContent)
            } else {
                var defaultText = element.getAttribute("data-clipboard-text");
                defaultText && this.setText(defaultText)
            }
            performCallbackAsync = !1;
            break;
        case "complete":
            _deleteOwnProperties(_clipData)
        }
        var context = element,
        eventArgs = [this, args];
        return _dispatchClientCallbacks.call(this, eventName, context, eventArgs, performCallbackAsync)
    };
    "function" == typeof define && define.amd ? define(["require", "exports", "module"],
    function(require, exports, module) {
        return _amdModuleId = module && module.id || null,
        ZeroClipboard
    }) : "object" == typeof module && module && "object" == typeof module.exports && module.exports ? (_cjsModuleId = module.id || null, module.exports = ZeroClipboard) : window.ZeroClipboard = ZeroClipboard
} (),
function($) {
    $.PaginationCalculator = function(maxentries, opts) {
        this.maxentries = maxentries,
        this.opts = opts
    },
    $.extend($.PaginationCalculator.prototype, {
        numPages: function() {
            return Math.ceil(this.maxentries / this.opts.items_per_page)
        },
        getInterval: function(current_page) {
            var ne_half = Math.floor(this.opts.num_display_entries / 2),
            np = this.numPages(),
            upper_limit = np - this.opts.num_display_entries,
            start = current_page > ne_half ? Math.max(Math.min(current_page - ne_half, upper_limit), 0) : 0,
            end = current_page > ne_half ? Math.min(current_page + ne_half + this.opts.num_display_entries % 2, np) : Math.min(this.opts.num_display_entries, np);
            return {
                start: start,
                end: end
            }
        }
    }),
    $.PaginationRenderers = {},
    $.PaginationRenderers.defaultRenderer = function(maxentries, opts) {
        this.maxentries = maxentries,
        this.opts = opts,
        this.pc = new $.PaginationCalculator(maxentries, opts)
    },
    $.extend($.PaginationRenderers.defaultRenderer.prototype, {
        createLink: function(page_id, current_page, appendopts) {
            var lnk, np = this.pc.numPages();
            return page_id = 0 > page_id ? 0 : np > page_id ? page_id: np - 1,
            appendopts = $.extend({
                text: page_id + 1,
                classes: ""
            },
            appendopts || {}),
            lnk = page_id == current_page ? $("<span class='current'>" + appendopts.text + "</span>") : $("<a>" + appendopts.text + "</a>").attr("href", this.opts.link_to.replace(/__id__/, page_id)),
            appendopts.classes && lnk.addClass(appendopts.classes),
            appendopts.rel && lnk.attr("rel", appendopts.rel),
            lnk.data("page_id", page_id),
            lnk
        },
        appendRange: function(container, current_page, start, end, opts) {
            var i;
            for (i = start; end > i; i++) this.createLink(i, current_page, opts).appendTo(container)
        },
        getLinks: function(current_page, eventHandler) {
            var begin, end, interval = this.pc.getInterval(current_page),
            np = this.pc.numPages(),
            fragment = $("<div class='pagination'></div>");
            return this.opts.prev_text && (current_page > 0 || this.opts.prev_show_always) && fragment.append(this.createLink(current_page - 1, current_page, {
                text: this.opts.prev_text,
                classes: "prev",
                rel: "prev"
            })),
            interval.start > 0 && this.opts.num_edge_entries > 0 && (end = Math.min(this.opts.num_edge_entries, interval.start), this.appendRange(fragment, current_page, 0, end, {
                classes: "sp"
            }), this.opts.num_edge_entries < interval.start && this.opts.ellipse_text && $("<span>" + this.opts.ellipse_text + "</span>").appendTo(fragment)),
            this.appendRange(fragment, current_page, interval.start, interval.end),
            interval.end < np && this.opts.num_edge_entries > 0 && (np - this.opts.num_edge_entries > interval.end && this.opts.ellipse_text && $("<span>" + this.opts.ellipse_text + "</span>").appendTo(fragment), begin = Math.max(np - this.opts.num_edge_entries, interval.end), this.appendRange(fragment, current_page, begin, np, {
                classes: "ep"
            })),
            this.opts.next_text && (np - 1 > current_page || this.opts.next_show_always) && fragment.append(this.createLink(current_page + 1, current_page, {
                text: this.opts.next_text,
                classes: "next",
                rel: "next"
            })),
            $("a", fragment).click(eventHandler),
            fragment
        }
    }),
    $.fn.pagination = function(maxentries, opts) {
        function paginationClickHandler(evt) {
            var new_current_page = $(evt.target).data("page_id"),
            continuePropagation = selectPage(new_current_page);
            return continuePropagation || evt.stopPropagation(),
            continuePropagation
        }
        function selectPage(new_current_page) {
            containers.data("current_page", new_current_page),
            links = renderer.getLinks(new_current_page, paginationClickHandler),
            containers.empty(),
            links.appendTo(containers);
            var continuePropagation = opts.callback(new_current_page, containers);
            return continuePropagation
        }
        opts = $.extend({
            items_per_page: 10,
            num_display_entries: 11,
            current_page: 0,
            num_edge_entries: 0,
            link_to: "#",
            prev_text: "Prev",
            next_text: "Next",
            ellipse_text: "...",
            prev_show_always: !0,
            next_show_always: !0,
            renderer: "defaultRenderer",
            show_if_single_page: !1,
            load_first_page: !0,
            callback: function() {
                return ! 1
            }
        },
        opts || {});
        var renderer, links, current_page, containers = this;
        if (current_page = parseInt(opts.current_page, 10), containers.data("current_page", current_page), maxentries = !maxentries || 0 > maxentries ? 1 : maxentries, opts.items_per_page = !opts.items_per_page || opts.items_per_page < 0 ? 1 : opts.items_per_page, !$.PaginationRenderers[opts.renderer]) throw new ReferenceError("Pagination renderer '" + opts.renderer + "' was not found in jQuery.PaginationRenderers object.");
        renderer = new $.PaginationRenderers[opts.renderer](maxentries, opts);
        var pc = new $.PaginationCalculator(maxentries, opts),
        np = pc.numPages();
        containers.off("setPage").on("setPage", {
            numPages: np
        },
        function(evt, page_id) {
            return page_id >= 0 && page_id < evt.data.numPages ? (selectPage(page_id), !1) : void 0
        }),
        containers.off("prevPage").on("prevPage",
        function() {
            var current_page = $(this).data("current_page");
            return current_page > 0 && selectPage(current_page - 1),
            !1
        }),
        containers.off("nextPage").on("nextPage", {
            numPages: np
        },
        function(evt) {
            var current_page = $(this).data("current_page");
            return current_page < evt.data.numPages - 1 && selectPage(current_page + 1),
            !1
        }),
        containers.off("currentPage").on("currentPage",
        function() {
            var current_page = $(this).data("current_page");
            return selectPage(current_page),
            !1
        }),
        links = renderer.getLinks(current_page, paginationClickHandler),
        containers.empty(),
        (np > 1 || opts.show_if_single_page) && links.appendTo(containers),
        opts.load_first_page && opts.callback(current_page, containers)
    }
} (jQuery),
function($) {
    function handleWindowResize() {
        $.each(tips,
        function() {
            this.refresh(!0)
        })
    }
    var tips = [],
    reBgImage = /^url\(["']?([^"'\)]*)["']?\);?$/i,
    rePNG = /\.png$/i,
    ie6 = !!window.createPopup && "undefined" == document.documentElement.currentStyle.minWidth;
    $(window).resize(handleWindowResize),
    $.Poshytip = function(elm, options) {
        this.$elm = $(elm),
        this.opts = $.extend({},
        $.fn.poshytip.defaults, options),
        this.$tip = $(['<div class="', this.opts.className, '">', '<div class="tip-inner tip-bg-image"></div>', '<div class="tip-arrow tip-arrow-top tip-arrow-right tip-arrow-bottom tip-arrow-left"></div>', "</div>"].join("")).appendTo(document.body),
        this.$arrow = this.$tip.find("div.tip-arrow"),
        this.$inner = this.$tip.find("div.tip-inner"),
        this.disabled = !1,
        this.content = null,
        this.init()
    },
    $.Poshytip.prototype = {
        init: function() {
            tips.push(this);
            var title = this.$elm.attr("title");
            if (this.$elm.data("title.poshytip", void 0 !== title ? title: null).data("poshytip", this), "none" != this.opts.showOn) switch (this.$elm.bind({
                "mouseenter.poshytip": $.proxy(this.mouseenter, this),
                "mouseleave.poshytip": $.proxy(this.mouseleave, this)
            }), this.opts.showOn) {
            case "hover":
                "cursor" == this.opts.alignTo && this.$elm.bind("mousemove.poshytip", $.proxy(this.mousemove, this)),
                this.opts.allowTipHover && this.$tip.hover($.proxy(this.clearTimeouts, this), $.proxy(this.mouseleave, this));
                break;
            case "focus":
                this.$elm.bind({
                    "focus.poshytip":
                    $.proxy(this.showDelayed, this),
                    "blur.poshytip": $.proxy(this.hideDelayed, this)
                })
            }
        },
        mouseenter: function() {
            return this.disabled ? !0 : (this.$elm.attr("title", ""), "focus" == this.opts.showOn ? !0 : (this.showDelayed(), void 0))
        },
        mouseleave: function(e) {
            if (this.disabled || this.asyncAnimating && (this.$tip[0] === e.relatedTarget || jQuery.contains(this.$tip[0], e.relatedTarget))) return ! 0;
            if (!this.$tip.data("active")) {
                var title = this.$elm.data("title.poshytip");
                null !== title && this.$elm.attr("title", title)
            }
            return "focus" == this.opts.showOn ? !0 : (this.hideDelayed(), void 0)
        },
        mousemove: function(e) {
            return this.disabled ? !0 : (this.eventX = e.pageX, this.eventY = e.pageY, this.opts.followCursor && this.$tip.data("active") && (this.calcPos(), this.$tip.css({
                left: this.pos.l,
                top: this.pos.t
            }), this.pos.arrow && (this.$arrow[0].className = "tip-arrow tip-arrow-" + this.pos.arrow)), void 0)
        },
        show: function() {
            this.disabled || this.$tip.data("active") || (this.reset(), this.update(), this.content && (this.display(), this.opts.timeOnScreen && this.hideDelayed(this.opts.timeOnScreen)))
        },
        showDelayed: function(timeout) {
            this.clearTimeouts(),
            this.showTimeout = setTimeout($.proxy(this.show, this), "number" == typeof timeout ? timeout: this.opts.showTimeout)
        },
        hide: function() { ! this.disabled && this.$tip.data("active") && this.display(!0)
        },
        hideDelayed: function(timeout) {
            this.clearTimeouts(),
            this.hideTimeout = setTimeout($.proxy(this.hide, this), "number" == typeof timeout ? timeout: this.opts.hideTimeout)
        },
        reset: function() {
            this.$tip.queue([]).detach().css("visibility", "hidden").data("active", !1),
            this.$inner.find("*").poshytip("hide"),
            this.opts.fade && this.$tip.css("opacity", this.opacity),
            this.$arrow[0].className = "tip-arrow tip-arrow-top tip-arrow-right tip-arrow-bottom tip-arrow-left",
            this.asyncAnimating = !1
        },
        update: function(content, dontOverwriteOption) {
            if (!this.disabled) {
                var async = void 0 !== content;
                if (async) {
                    if (dontOverwriteOption || (this.opts.content = content), !this.$tip.data("active")) return
                } else content = this.opts.content;
                var self = this,
                newContent = "function" == typeof content ? content.call(this.$elm[0],
                function(newContent) {
                    self.update(newContent)
                }) : "[title]" == content ? this.$elm.data("title.poshytip") : content;
                this.content !== newContent && (this.$inner.empty().append(newContent), this.content = newContent),
                this.refresh(async)
            }
        },
        refresh: function(async) {
            if (!this.disabled) {
                if (async) {
                    if (!this.$tip.data("active")) return;
                    var currPos = {
                        left: this.$tip.css("left"),
                        top: this.$tip.css("top")
                    }
                }
                this.$tip.css({
                    left: 0,
                    top: 0
                }).appendTo(document.body),
                void 0 === this.opacity && (this.opacity = this.$tip.css("opacity"));
                var bgImage = this.$tip.css("background-image").match(reBgImage),
                arrow = this.$arrow.css("background-image").match(reBgImage);
                if (bgImage) {
                    var bgImagePNG = rePNG.test(bgImage[1]);
                    ie6 && bgImagePNG ? (this.$tip.css("background-image", "none"), this.$inner.css({
                        margin: 0,
                        border: 0,
                        padding: 0
                    }), bgImage = bgImagePNG = !1) : this.$tip.prepend('<table class="tip-table" border="0" cellpadding="0" cellspacing="0"><tr><td class="tip-top tip-bg-image" colspan="2"><span></span></td><td class="tip-right tip-bg-image" rowspan="2"><span></span></td></tr><tr><td class="tip-left tip-bg-image" rowspan="2"><span></span></td><td></td></tr><tr><td class="tip-bottom tip-bg-image" colspan="2"><span></span></td></tr></table>').css({
                        border: 0,
                        padding: 0,
                        "background-image": "none",
                        "background-color": "transparent"
                    }).find(".tip-bg-image").css("background-image", 'url("' + bgImage[1] + '")').end().find("td").eq(3).append(this.$inner),
                    bgImagePNG && !$.support.opacity && (this.opts.fade = !1)
                }
                arrow && !$.support.opacity && (ie6 && rePNG.test(arrow[1]) && (arrow = !1, this.$arrow.css("background-image", "none")), this.opts.fade = !1);
                var $table = this.$tip.find("> table.tip-table");
                if (ie6) {
                    this.$tip[0].style.width = "",
                    $table.width("auto").find("td").eq(3).width("auto");
                    var tipW = this.$tip.width(),
                    minW = parseInt(this.$tip.css("min-width")),
                    maxW = parseInt(this.$tip.css("max-width")); ! isNaN(minW) && minW > tipW ? tipW = minW: !isNaN(maxW) && tipW > maxW && (tipW = maxW),
                    this.$tip.add($table).width(tipW).eq(0).find("td").eq(3).width("100%")
                } else $table[0] && $table.width("auto").find("td").eq(3).width("auto").end().end().width(document.defaultView && document.defaultView.getComputedStyle && parseFloat(document.defaultView.getComputedStyle(this.$tip[0], null).width) || this.$tip.width()).find("td").eq(3).width("100%");
                if (this.tipOuterW = this.$tip.outerWidth(), this.tipOuterH = this.$tip.outerHeight(), this.calcPos(), arrow && this.pos.arrow && (this.$arrow[0].className = "tip-arrow tip-arrow-" + this.pos.arrow, this.$arrow.css("visibility", "inherit")), async && this.opts.refreshAniDuration) {
                    this.asyncAnimating = !0;
                    var self = this;
                    this.$tip.css(currPos).animate({
                        left: this.pos.l,
                        top: this.pos.t
                    },
                    this.opts.refreshAniDuration,
                    function() {
                        self.asyncAnimating = !1
                    })
                } else this.$tip.css({
                    left: this.pos.l,
                    top: this.pos.t
                })
            }
        },
        display: function(hide) {
            var active = this.$tip.data("active");
            if (! (active && !hide || !active && hide)) {
                if (this.$tip.stop(), (this.opts.slide && this.pos.arrow || this.opts.fade) && (hide && this.opts.hideAniDuration || !hide && this.opts.showAniDuration)) {
                    var from = {},
                    to = {};
                    if (this.opts.slide && this.pos.arrow) {
                        var prop, arr;
                        "bottom" == this.pos.arrow || "top" == this.pos.arrow ? (prop = "top", arr = "bottom") : (prop = "left", arr = "right");
                        var val = parseInt(this.$tip.css(prop));
                        from[prop] = val + (hide ? 0 : this.pos.arrow == arr ? -this.opts.slideOffset: this.opts.slideOffset),
                        to[prop] = val + (hide ? this.pos.arrow == arr ? this.opts.slideOffset: -this.opts.slideOffset: 0) + "px"
                    }
                    this.opts.fade && (from.opacity = hide ? this.$tip.css("opacity") : 0, to.opacity = hide ? 0 : this.opacity),
                    this.$tip.css(from).animate(to, this.opts[hide ? "hideAniDuration": "showAniDuration"])
                }
                if (hide ? this.$tip.queue($.proxy(this.reset, this)) : this.$tip.css("visibility", "inherit"), active) {
                    var title = this.$elm.data("title.poshytip");
                    null !== title && this.$elm.attr("title", title)
                }
                this.$tip.data("active", !active)
            }
        },
        disable: function() {
            this.reset(),
            this.disabled = !0
        },
        enable: function() {
            this.disabled = !1
        },
        destroy: function() {
            this.reset(),
            this.$tip.remove(),
            delete this.$tip,
            this.content = null,
            this.$elm.unbind(".poshytip").removeData("title.poshytip").removeData("poshytip"),
            tips.splice($.inArray(this, tips), 1)
        },
        clearTimeouts: function() {
            this.showTimeout && (clearTimeout(this.showTimeout), this.showTimeout = 0),
            this.hideTimeout && (clearTimeout(this.hideTimeout), this.hideTimeout = 0)
        },
        calcPos: function() {
            var xL, xC, xR, yT, yC, yB, pos = {
                l: 0,
                t: 0,
                arrow: ""
            },
            $win = $(window),
            win = {
                l: $win.scrollLeft(),
                t: $win.scrollTop(),
                w: $win.width(),
                h: $win.height()
            };
            if ("cursor" == this.opts.alignTo) xL = xC = xR = this.eventX,
            yT = yC = yB = this.eventY;
            else {
                var elmOffset = this.$elm.offset(),
                elm = {
                    l: elmOffset.left,
                    t: elmOffset.top,
                    w: this.$elm.outerWidth(),
                    h: this.$elm.outerHeight()
                };
                xL = elm.l + ("inner-right" != this.opts.alignX ? 0 : elm.w),
                xC = xL + Math.floor(elm.w / 2),
                xR = xL + ("inner-left" != this.opts.alignX ? elm.w: 0),
                yT = elm.t + ("inner-bottom" != this.opts.alignY ? 0 : elm.h),
                yC = yT + Math.floor(elm.h / 2),
                yB = yT + ("inner-top" != this.opts.alignY ? elm.h: 0)
            }
            switch (this.opts.alignX) {
            case "right":
            case "inner-left":
                pos.l = xR + this.opts.offsetX,
                this.opts.keepInViewport && pos.l + this.tipOuterW > win.l + win.w && (pos.l = win.l + win.w - this.tipOuterW),
                ("right" == this.opts.alignX || "center" == this.opts.alignY) && (pos.arrow = "left");
                break;
            case "center":
                pos.l = xC - Math.floor(this.tipOuterW / 2),
                this.opts.keepInViewport && (pos.l + this.tipOuterW > win.l + win.w ? pos.l = win.l + win.w - this.tipOuterW: pos.l < win.l && (pos.l = win.l));
                break;
            default:
                pos.l = xL - this.tipOuterW - this.opts.offsetX,
                this.opts.keepInViewport && pos.l < win.l && (pos.l = win.l),
                ("left" == this.opts.alignX || "center" == this.opts.alignY) && (pos.arrow = "right")
            }
            switch (this.opts.alignY) {
            case "bottom":
            case "inner-top":
                pos.t = yB + this.opts.offsetY,
                pos.arrow && "cursor" != this.opts.alignTo || (pos.arrow = "top"),
                this.opts.keepInViewport && pos.t + this.tipOuterH > win.t + win.h && (pos.t = yT - this.tipOuterH - this.opts.offsetY, "top" == pos.arrow && (pos.arrow = "bottom"));
                break;
            case "center":
                pos.t = yC - Math.floor(this.tipOuterH / 2),
                this.opts.keepInViewport && (pos.t + this.tipOuterH > win.t + win.h ? pos.t = win.t + win.h - this.tipOuterH: pos.t < win.t && (pos.t = win.t));
                break;
            default:
                pos.t = yT - this.tipOuterH - this.opts.offsetY,
                pos.arrow && "cursor" != this.opts.alignTo || (pos.arrow = "bottom"),
                this.opts.keepInViewport && pos.t < win.t && (pos.t = yB + this.opts.offsetY, "bottom" == pos.arrow && (pos.arrow = "top"))
            }
            this.pos = pos
        }
    },
    $.fn.poshytip = function(options) {
        if ("string" == typeof options) {
            var args = arguments,
            method = options;
            return Array.prototype.shift.call(args),
            "destroy" == method && (this.die ? this.die("mouseenter.poshytip").die("focus.poshytip") : $(document).undelegate(this.selector, "mouseenter.poshytip").undelegate(this.selector, "focus.poshytip")),
            this.each(function() {
                var poshytip = $(this).data("poshytip");
                poshytip && poshytip[method] && poshytip[method].apply(poshytip, args)
            })
        }
        var opts = $.extend({},
        $.fn.poshytip.defaults, options);
        if ($("#poshytip-css-" + opts.className)[0] || $(['<style id="poshytip-css-', opts.className, '" type="text/css">', "div.", opts.className, "{visibility:hidden;position:absolute;top:0;left:0;}", "div.", opts.className, " table.tip-table, div.", opts.className, " table.tip-table td{margin:0;font-family:inherit;font-size:inherit;font-weight:inherit;font-style:inherit;font-variant:inherit;vertical-align:middle;}", "div.", opts.className, " td.tip-bg-image span{display:block;font:1px/1px sans-serif;height:", opts.bgImageFrameSize, "px;width:", opts.bgImageFrameSize, "px;overflow:hidden;}", "div.", opts.className, " td.tip-right{background-position:100% 0;}", "div.", opts.className, " td.tip-bottom{background-position:100% 100%;}", "div.", opts.className, " td.tip-left{background-position:0 100%;}", "div.", opts.className, " div.tip-inner{background-position:-", opts.bgImageFrameSize, "px -", opts.bgImageFrameSize, "px;}", "div.", opts.className, " div.tip-arrow{visibility:hidden;position:absolute;overflow:hidden;font:1px/1px sans-serif;}", "</style>"].join("")).appendTo("head"), opts.liveEvents && "none" != opts.showOn) {
            var handler, deadOpts = $.extend({},
            opts, {
                liveEvents: !1
            });
            switch (opts.showOn) {
            case "hover":
                handler = function() {
                    var $this = $(this);
                    $this.data("poshytip") || $this.poshytip(deadOpts).poshytip("mouseenter")
                },
                this.live ? this.live("mouseenter.poshytip", handler) : $(document).delegate(this.selector, "mouseenter.poshytip", handler);
                break;
            case "focus":
                handler = function() {
                    var $this = $(this);
                    $this.data("poshytip") || $this.poshytip(deadOpts).poshytip("showDelayed")
                },
                this.live ? this.live("focus.poshytip", handler) : $(document).delegate(this.selector, "focus.poshytip", handler)
            }
            return this
        }
        return this.each(function() {
            new $.Poshytip(this, opts)
        })
    },
    $.fn.poshytip.defaults = {
        content: "[title]",
        className: "tip-yellow",
        bgImageFrameSize: 10,
        showTimeout: 500,
        hideTimeout: 100,
        timeOnScreen: 0,
        showOn: "hover",
        liveEvents: !1,
        alignTo: "cursor",
        alignX: "right",
        alignY: "top",
        offsetX: -22,
        offsetY: 18,
        keepInViewport: !0,
        allowTipHover: !0,
        followCursor: !1,
        fade: !0,
        slide: !0,
        slideOffset: 8,
        showAniDuration: 300,
        hideAniDuration: 300,
        refreshAniDuration: 200
    }
} (jQuery),
function($) {
    var _options = {
        words_per_line: "150px",
        color: "#e6e6e6",
        tip_top: 0
    };
    $.fn.extend({
        tip: function(options) {
            return getOptions(options),
            insertCssForTip(),
            replaceTitle(this),
            _attachEvent(this),
            this
        }
    });
    var split_str = function(string, words_per_line) {
        if ("undefined" == typeof string || 0 == string.length) return "";
        words_per_line = parseInt(words_per_line);
        for (var output_string = string.substring(0, 1), i = 1; i < string.length; i++) 0 == i % words_per_line && (output_string += "<br/>"),
        output_string += string.substring(i, i + 1);
        return output_string
    },
    title_show_hoverFlag = !1,
    titleMouseOver = function(obj) {
        if ("undefined" == typeof $(obj).attr("_title") || "" == $(obj).attr("_title")) return ! 1;
        var title_show = document.getElementById("title_show");
        null == title_show && (title_show = document.createElement("div"), $(title_show).attr("id", "title_show"), $("body").append(title_show), $(title_show).css({
            position: "absolute",
            border: "solid 1px " + _options.color,
            background: "#FFFFFF",
            lineHeight: "18px",
            fontSize: "12px",

            padding: "10px",
            left: "-9999px",
            "z-index": "10000"
        })),
        innerHtml = "";
        var words_per_line = _options.words_per_line;
        /^\d+px$/.test(words_per_line) ? ($(title_show).css("width", words_per_line), innerHtml = $(obj).attr("_title")) : (words_per_line = parseInt(words_per_line), innerHtml = split_str($(obj).attr("_title"), words_per_line)),
        $(title_show).html(innerHtml);
        var title_sanjiao = document.getElementById("title_sanjiao");
        null == title_sanjiao && (title_sanjiao = document.createElement("div"), $(title_sanjiao).attr("id", "title_sanjiao"), $("#title_show").append(title_sanjiao), $(title_sanjiao).css({
            position: "absolute",
            height: "10px",
            width: "14px",
            "z-index": "10001"
        })),
        $("#title_show").css("display", "block");
        var title_show_width = $("#title_show").width(),
        title_show_height = $("#title_show").height(),
        top_down = 10,
        offset = $(obj).offset(),
        ele_height = $(obj).height(),
        ele_width = $(obj).width(),
        padding_height = 20;
        title_show.style.left = offset.left + (ele_width - title_show_width - 20) / (_options["trangle-position"] || 2) + "px",
        "up" == _options.direction || offset.top - $(window).scrollTop() + ele_height + top_down + title_show_height + 25 >= $(window).height() ? (title_show.style.top = offset.top - top_down - title_show_height - padding_height + _options.tip_top + "px", $(title_sanjiao).html('<span class="sanjiao sanjiao_fff3">◆</span><span class="sanjiao sanjiao_ddd4">◆</span>'), title_sanjiao.style.top = title_show_height + padding_height - 9 + "px") : (title_show.style.top = offset.top + ele_height + top_down - _options.tip_top + "px", $(title_sanjiao).html('<span class="sanjiao sanjiao_ddd1">◆</span><span class="sanjiao sanjiao_fff2">◆</span>'), title_sanjiao.style.top = "-10px"),
        $(title_sanjiao).find("span[class^='sanjiao sanjiao_ddd']").css("color", _options.color),
        title_sanjiao.style.left = (title_show_width + 20 - 14) / 2 + "px"
    },
    hover_flag = !1,
    titleMouseOut = function() {
        var title_show = document.getElementById("title_show");
        return null == title_show ? !1 : (hover_flag || (title_show.style.display = "none"), void 0)
    },
    _attachEvent = function(objs) {
        if ("object" != typeof objs) return ! 1;
        var current_over;
        for (i = 0; i < objs.length; i++) $(objs[i]).hover(function() {
            titleMouseOver(this),
            current_over = this,
            hover_flag = !0
        },
        function() {
            var that = this;
            current_over = this,
            hover_flag = !1,
            setTimeout(function() {
                title_show_hoverFlag || titleMouseOut(that)
            },
            60)
        });
        $("body").delegate("#title_show", "mouseenter",
        function() {
            title_show_hoverFlag = !0
        }),
        $("body").delegate("#title_show", "mouseleave",
        function() {
            title_show_hoverFlag = !1,
            titleMouseOut(current_over)
        })
    },
    replaceTitle = function(objs) {
        for (i = 0; i < objs.length; i++) $(objs[i]).attr("_title", $(objs[i]).attr("title")),
        $(objs[i]).removeAttr("title")
    },
    addStyleString = function(css) {
        var style = document.createElement("style");
        style.type = "text/css";
        try {
            style.appendChild(document.createTextNode(css))
        } catch(ex) {
            style.styleSheet.cssText = css
        }
        var head = document.getElementsByTagName("head")[0];
        head.appendChild(style)
    },
    insertCssForTip = function() {
        addStyleString(".sanjiao {font-size: 14px;font-family: 宋体, sans-serif;height: 8px;}.sanjiao_ddd1 { position: absolute;left: 0px;top: 0px;z-index: 1;}.sanjiao_fff2 {color: #fff;position: absolute;left: 0px;top: 2px;z-index: 2;}.sanjiao_fff3 {color: #fff;position: absolute;left: 0px;top: 0px;z-index: 2;}.sanjiao_ddd4 {position: absolute;left: 0px;top: 2px;z-index: 1;}")
    },
    getOptions = function(options) {
        $.extend(_options, options);
        var words_per_line = _options.words_per_line;
        "undefined" == typeof words_per_line && (words_per_line = "150px"),
        _options.words_per_line = words_per_line
    }
} (jQuery),
function($) {
    $.extend($.fn, {
        validate: function(options) {
            if (!this.length) return options && options.debug && window.console && console.warn("Nothing selected, can't validate, returning nothing."),
            void 0;
            var validator = $.data(this[0], "validator");
            return validator ? validator: (this.attr("novalidate", "novalidate"), validator = new $.validator(options, this[0]), $.data(this[0], "validator", validator), validator.settings.onsubmit && (this.validateDelegate(":submit", "click",
            function(event) {
                validator.settings.submitHandler && (validator.submitButton = event.target),
                $(event.target).hasClass("cancel") && (validator.cancelSubmit = !0),
                void 0 !== $(event.target).attr("formnovalidate") && (validator.cancelSubmit = !0)
            }), this.submit(function(event) {
                function handle() {
                    var hidden;
                    return validator.settings.submitHandler ? (validator.submitButton && (hidden = $("<input type='hidden'/>").attr("name", validator.submitButton.name).val($(validator.submitButton).val()).appendTo(validator.currentForm)), validator.settings.submitHandler.call(validator, validator.currentForm, event), validator.submitButton && hidden.remove(), !1) : !0
                }
                return validator.settings.debug && event.preventDefault(),
                validator.cancelSubmit ? (validator.cancelSubmit = !1, handle()) : validator.form() ? validator.pendingRequest ? (validator.formSubmitted = !0, !1) : handle() : (validator.focusInvalid(), !1)
            })), validator)
        },
        valid: function() {
            if ($(this[0]).is("form")) return this.validate().form();
            var valid = !0,
            validator = $(this[0].form).validate();
            return this.each(function() {
                valid = valid && validator.element(this)
            }),
            valid
        },
        removeAttrs: function(attributes) {
            var result = {},
            $element = this;
            return $.each(attributes.split(/\s/),
            function(index, value) {
                result[value] = $element.attr(value),
                $element.removeAttr(value)
            }),
            result
        },
        rules: function(command, argument) {
            var element = this[0];
            if (command) {
                var settings = $.data(element.form, "validator").settings,
                staticRules = settings.rules,
                existingRules = $.validator.staticRules(element);
                switch (command) {
                case "add":
                    $.extend(existingRules, $.validator.normalizeRule(argument)),
                    delete existingRules.messages,
                    staticRules[element.name] = existingRules,
                    argument.messages && (settings.messages[element.name] = $.extend(settings.messages[element.name], argument.messages));
                    break;
                case "remove":
                    if (!argument) return delete staticRules[element.name],
                    existingRules;
                    var filtered = {};
                    return $.each(argument.split(/\s/),
                    function(index, method) {
                        filtered[method] = existingRules[method],
                        delete existingRules[method]
                    }),
                    filtered
                }
            }
            var data = $.validator.normalizeRules($.extend({},
            $.validator.classRules(element), $.validator.attributeRules(element), $.validator.dataRules(element), $.validator.staticRules(element)), element);
            if (data.required) {
                var param = data.required;
                delete data.required,
                data = $.extend({
                    required: param
                },
                data)
            }
            return data
        }
    }),
    $.extend($.expr[":"], {
        blank: function(a) {
            return ! $.trim("" + $(a).val())
        },
        filled: function(a) {
            return !! $.trim("" + $(a).val())
        },
        unchecked: function(a) {
            return ! $(a).prop("checked")
        }
    }),
    $.validator = function(options, form) {
        this.settings = $.extend(!0, {},
        $.validator.defaults, options),
        this.currentForm = form,
        this.init()
    },
    $.validator.format = function(source, params) {
        return 1 === arguments.length ?
        function() {
            var args = $.makeArray(arguments);
            return args.unshift(source),
            $.validator.format.apply(this, args)
        }: (arguments.length > 2 && params.constructor !== Array && (params = $.makeArray(arguments).slice(1)), params.constructor !== Array && (params = [params]), $.each(params,
        function(i, n) {
            source = source.replace(new RegExp("\\{" + i + "\\}", "g"),
            function() {
                return n
            })
        }), source)
    },
    $.extend($.validator, {
        defaults: {
            messages: {},
            groups: {},
            rules: {},
            errorClass: "error",
            validClass: "valid",
            errorElement: "label",
            focusInvalid: !0,
            errorContainer: $([]),
            errorLabelContainer: $([]),
            onsubmit: !0,
            ignore: ":hidden",
            ignoreTitle: !1,
            onfocusin: function(element) {
                this.lastActive = element,
                this.settings.focusCleanup && !this.blockFocusCleanup && (this.settings.unhighlight && this.settings.unhighlight.call(this, element, this.settings.errorClass, this.settings.validClass), this.addWrapper(this.errorsFor(element)).hide())
            },
            onfocusout: function(element) {
                this.checkable(element) || !(element.name in this.submitted) && this.optional(element) || this.element(element)
            },
            onkeyup: function(element, event) { (9 !== event.which || "" !== this.elementValue(element)) && (element.name in this.submitted || element === this.lastElement) && this.element(element)
            },
            onclick: function(element) {
                element.name in this.submitted ? this.element(element) : element.parentNode.name in this.submitted && this.element(element.parentNode)
            },
            highlight: function(element, errorClass, validClass) {
                "radio" === element.type ? this.findByName(element.name).addClass(errorClass).removeClass(validClass) : $(element).addClass(errorClass).removeClass(validClass)
            },
            unhighlight: function(element, errorClass, validClass) {
                "radio" === element.type ? this.findByName(element.name).removeClass(errorClass).addClass(validClass) : $(element).removeClass(errorClass).addClass(validClass)
            }
        },
        setDefaults: function(settings) {
            $.extend($.validator.defaults, settings)
        },
        messages: {
            required: "This field is required.",
            remote: "Please fix this field.",
            email: "Please enter a valid email address.",
            url: "Please enter a valid URL.",
            date: "Please enter a valid date.",
            dateISO: "Please enter a valid date (ISO).",
            number: "Please enter a valid number.",
            digits: "Please enter only digits.",
            creditcard: "Please enter a valid credit card number.",
            equalTo: "Please enter the same value again.",
            maxlength: $.validator.format("Please enter no more than {0} characters."),
            minlength: $.validator.format("Please enter at least {0} characters."),
            rangelength: $.validator.format("Please enter a value between {0} and {1} characters long."),
            range: $.validator.format("Please enter a value between {0} and {1}."),
            max: $.validator.format("Please enter a value less than or equal to {0}."),
            min: $.validator.format("Please enter a value greater than or equal to {0}.")
        },
        autoCreateRanges: !1,
        prototype: {
            init: function() {
                function delegate(event) {
                    var validator = $.data(this[0].form, "validator"),
                    eventType = "on" + event.type.replace(/^validate/, "");
                    validator.settings[eventType] && validator.settings[eventType].call(validator, this[0], event)
                }
                this.labelContainer = $(this.settings.errorLabelContainer),
                this.errorContext = this.labelContainer.length && this.labelContainer || $(this.currentForm),
                this.containers = $(this.settings.errorContainer).add(this.settings.errorLabelContainer),
                this.submitted = {},
                this.valueCache = {},
                this.pendingRequest = 0,
                this.pending = {},
                this.invalid = {},
                this.reset();
                var groups = this.groups = {};
                $.each(this.settings.groups,
                function(key, value) {
                    "string" == typeof value && (value = value.split(/\s/)),
                    $.each(value,
                    function(index, name) {
                        groups[name] = key
                    })
                });
                var rules = this.settings.rules;
                $.each(rules,
                function(key, value) {
                    rules[key] = $.validator.normalizeRule(value)
                }),
                $(this.currentForm).validateDelegate(":text, [type='password'], [type='file'], select, textarea, [type='number'], [type='search'] ,[type='tel'], [type='url'], [type='email'], [type='datetime'], [type='date'], [type='month'], [type='week'], [type='time'], [type='datetime-local'], [type='range'], [type='color'] ", "focusin focusout keyup", delegate).validateDelegate("[type='radio'], [type='checkbox'], select, option", "click", delegate),
                this.settings.invalidHandler && $(this.currentForm).bind("invalid-form.validate", this.settings.invalidHandler)
            },
            form: function() {
                return this.checkForm(),
                $.extend(this.submitted, this.errorMap),
                this.invalid = $.extend({},
                this.errorMap),
                this.valid() || $(this.currentForm).triggerHandler("invalid-form", [this]),
                this.showErrors(),
                this.valid()
            },
            checkForm: function() {
                this.prepareForm();
                for (var i = 0,
                elements = this.currentElements = this.elements(); elements[i]; i++) this.check(elements[i]);
                return this.valid()
            },
            element: function(element) {
                element = this.validationTargetFor(this.clean(element)),
                this.lastElement = element,
                this.prepareElement(element),
                this.currentElements = $(element);
                var result = this.check(element) !== !1;
                return result ? delete this.invalid[element.name] : this.invalid[element.name] = !0,
                this.numberOfInvalids() || (this.toHide = this.toHide.add(this.containers)),
                this.showErrors(),
                result
            },
            showErrors: function(errors) {
                if (errors) {
                    $.extend(this.errorMap, errors),
                    this.errorList = [];
                    for (var name in errors) this.errorList.push({
                        message: errors[name],
                        element: this.findByName(name)[0]
                    });
                    this.successList = $.grep(this.successList,
                    function(element) {
                        return ! (element.name in errors)
                    })
                }
                this.settings.showErrors ? this.settings.showErrors.call(this, this.errorMap, this.errorList) : this.defaultShowErrors()
            },
            resetForm: function() {
                $.fn.resetForm && $(this.currentForm).resetForm(),
                this.submitted = {},
                this.lastElement = null,
                this.prepareForm(),
                this.hideErrors(),
                this.elements().removeClass(this.settings.errorClass).removeData("previousValue")
            },
            numberOfInvalids: function() {
                return this.objectLength(this.invalid)
            },
            objectLength: function(obj) {
                var count = 0;
                for (var i in obj) count++;
                return count
            },
            hideErrors: function() {
                this.addWrapper(this.toHide).hide()
            },
            valid: function() {
                return 0 === this.size()
            },
            size: function() {
                return this.errorList.length
            },
            focusInvalid: function() {
                if (this.settings.focusInvalid) try {
                    $(this.findLastActive() || this.errorList.length && this.errorList[0].element || []).filter(":visible").focus().trigger("focusin")
                } catch(e) {}
            },
            findLastActive: function() {
                var lastActive = this.lastActive;
                return lastActive && 1 === $.grep(this.errorList,
                function(n) {
                    return n.element.name === lastActive.name
                }).length && lastActive
            },
            elements: function() {
                var validator = this,
                rulesCache = {};
                return $(this.currentForm).find("input, select, textarea").not(":submit, :reset, :image, [disabled]").not(this.settings.ignore).filter(function() {
                    return ! this.name && validator.settings.debug && window.console && console.error("%o has no name assigned", this),
                    this.name in rulesCache || !validator.objectLength($(this).rules()) ? !1 : (rulesCache[this.name] = !0, !0)
                })
            },
            clean: function(selector) {
                return $(selector)[0]
            },
            errors: function() {
                var errorClass = this.settings.errorClass.replace(" ", ".");
                return $(this.settings.errorElement + "." + errorClass, this.errorContext)
            },
            reset: function() {
                this.successList = [],
                this.errorList = [],
                this.errorMap = {},
                this.toShow = $([]),
                this.toHide = $([]),
                this.currentElements = $([])
            },
            prepareForm: function() {
                this.reset(),
                this.toHide = this.errors().add(this.containers)
            },
            prepareElement: function(element) {
                this.reset(),
                this.toHide = this.errorsFor(element)
            },
            elementValue: function(element) {
                var type = $(element).attr("type"),
                val = $(element).val();
                return "radio" === type || "checkbox" === type ? $("input[name='" + $(element).attr("name") + "']:checked").val() : "string" == typeof val ? val.replace(/\r/g, "") : val
            },
            check: function(element) {
                element = this.validationTargetFor(this.clean(element));
                var result, rules = $(element).rules(),
                dependencyMismatch = !1,
                val = this.elementValue(element);
                for (var method in rules) {
                    var rule = {
                        method: method,
                        parameters: rules[method]
                    };
                    try {
                        if (result = $.validator.methods[method].call(this, val, element, rule.parameters), "dependency-mismatch" === result) {
                            dependencyMismatch = !0;
                            continue
                        }
                        if (dependencyMismatch = !1, "pending" === result) return this.toHide = this.toHide.not(this.errorsFor(element)),
                        void 0;
                        if (!result) return this.formatAndAdd(element, rule),
                        !1
                    } catch(e) {
                        throw this.settings.debug && window.console && console.log("Exception occurred when checking element " + element.id + ", check the '" + rule.method + "' method.", e),
                        e
                    }
                }
                return dependencyMismatch ? void 0 : (this.objectLength(rules) && this.successList.push(element), !0)
            },
            customDataMessage: function(element, method) {
                return $(element).data("msg-" + method.toLowerCase()) || element.attributes && $(element).attr("data-msg-" + method.toLowerCase())
            },
            customMessage: function(name, method) {
                var m = this.settings.messages[name];
                return m && (m.constructor === String ? m: m[method])
            },
            findDefined: function() {
                for (var i = 0; i < arguments.length; i++) if (void 0 !== arguments[i]) return arguments[i];
                return void 0
            },
            defaultMessage: function(element, method) {
                return this.findDefined(this.customMessage(element.name, method), this.customDataMessage(element, method), !this.settings.ignoreTitle && element.title || void 0, $.validator.messages[method], "<strong>Warning: No message defined for " + element.name + "</strong>")
            },
            formatAndAdd: function(element, rule) {
                var message = this.defaultMessage(element, rule.method),
                theregex = /\$?\{(\d+)\}/g;
                "function" == typeof message ? message = message.call(this, rule.parameters, element) : theregex.test(message) && (message = $.validator.format(message.replace(theregex, "{$1}"), rule.parameters)),
                this.errorList.push({
                    message: message,
                    element: element
                }),
                this.errorMap[element.name] = message,
                this.submitted[element.name] = message
            },
            addWrapper: function(toToggle) {
                return this.settings.wrapper && (toToggle = toToggle.add(toToggle.parent(this.settings.wrapper))),
                toToggle
            },
            defaultShowErrors: function() {
                var i, elements;
                for (i = 0; this.errorList[i]; i++) {
                    var error = this.errorList[i];
                    this.settings.highlight && this.settings.highlight.call(this, error.element, this.settings.errorClass, this.settings.validClass),
                    this.showLabel(error.element, error.message)
                }
                if (this.errorList.length && (this.toShow = this.toShow.add(this.containers)), this.settings.success) for (i = 0; this.successList[i]; i++) this.showLabel(this.successList[i]);
                if (this.settings.unhighlight) for (i = 0, elements = this.validElements(); elements[i]; i++) this.settings.unhighlight.call(this, elements[i], this.settings.errorClass, this.settings.validClass);
                this.toHide = this.toHide.not(this.toShow),
                this.hideErrors(),
                this.addWrapper(this.toShow).show()
            },
            validElements: function() {
                return this.currentElements.not(this.invalidElements())
            },
            invalidElements: function() {
                return $(this.errorList).map(function() {
                    return this.element
                })
            },
            showLabel: function(element, message) {
                var label = this.errorsFor(element);
                label.length ? (label.removeClass(this.settings.validClass).addClass(this.settings.errorClass), label.html(message)) : (label = $("<" + this.settings.errorElement + ">").attr("for", this.idOrName(element)).addClass(this.settings.errorClass).html(message || ""), this.settings.wrapper && (label = label.hide().show().wrap("<" + this.settings.wrapper + "/>").parent()), this.labelContainer.append(label).length || (this.settings.errorPlacement ? this.settings.errorPlacement(label, $(element)) : label.insertAfter(element))),
                !message && this.settings.success && (label.text(""), "string" == typeof this.settings.success ? label.addClass(this.settings.success) : this.settings.success(label, element)),
                this.toShow = this.toShow.add(label)
            },
            errorsFor: function(element) {
                var name = this.idOrName(element);
                return this.errors().filter(function() {
                    return $(this).attr("for") === name
                })
            },
            idOrName: function(element) {
                return this.groups[element.name] || (this.checkable(element) ? element.name: element.id || element.name)
            },
            validationTargetFor: function(element) {
                return this.checkable(element) && (element = this.findByName(element.name).not(this.settings.ignore)[0]),
                element
            },
            checkable: function(element) {
                return /radio|checkbox/i.test(element.type)
            },
            findByName: function(name) {
                return $(this.currentForm).find("[name='" + name + "']")
            },
            getLength: function(value, element) {
                switch (element.nodeName.toLowerCase()) {
                case "select":
                    return $("option:selected", element).length;
                case "input":
                    if (this.checkable(element)) return this.findByName(element.name).filter(":checked").length
                }
                return value.length
            },
            depend: function(param, element) {
                return this.dependTypes[typeof param] ? this.dependTypes[typeof param](param, element) : !0
            },
            dependTypes: {
                "boolean": function(param) {
                    return param
                },
                string: function(param, element) {
                    return !! $(param, element.form).length
                },
                "function": function(param, element) {
                    return param(element)
                }
            },
            optional: function(element) {
                var val = this.elementValue(element);
                return ! $.validator.methods.required.call(this, val, element) && "dependency-mismatch"
            },
            startRequest: function(element) {
                this.pending[element.name] || (this.pendingRequest++, this.pending[element.name] = !0)
            },
            stopRequest: function(element, valid) {
                this.pendingRequest--,
                this.pendingRequest < 0 && (this.pendingRequest = 0),
                delete this.pending[element.name],
                valid && 0 === this.pendingRequest && this.formSubmitted && this.form() ? ($(this.currentForm).submit(), this.formSubmitted = !1) : !valid && 0 === this.pendingRequest && this.formSubmitted && ($(this.currentForm).triggerHandler("invalid-form", [this]), this.formSubmitted = !1)
            },
            previousValue: function(element) {
                return $.data(element, "previousValue") || $.data(element, "previousValue", {
                    old: null,
                    valid: !0,
                    message: this.defaultMessage(element, "remote")
                })
            }
        },
        classRuleSettings: {
            required: {
                required: !0
            },
            email: {
                email: !0
            },
            url: {
                url: !0
            },
            date: {
                date: !0
            },
            dateISO: {
                dateISO: !0
            },
            number: {
                number: !0
            },
            digits: {
                digits: !0
            },
            creditcard: {
                creditcard: !0
            }
        },
        addClassRules: function(className, rules) {
            className.constructor === String ? this.classRuleSettings[className] = rules: $.extend(this.classRuleSettings, className)
        },
        classRules: function(element) {
            var rules = {},
            classes = $(element).attr("class");
            return classes && $.each(classes.split(" "),
            function() {
                this in $.validator.classRuleSettings && $.extend(rules, $.validator.classRuleSettings[this])
            }),
            rules
        },
        attributeRules: function(element) {
            var rules = {},
            $element = $(element),
            type = $element[0].getAttribute("type");
            for (var method in $.validator.methods) {
                var value;
                "required" === method ? (value = $element.get(0).getAttribute(method), "" === value && (value = !0), value = !!value) : value = $element.attr(method),
                /min|max/.test(method) && (null === type || /number|range|text/.test(type)) && (value = Number(value)),
                value ? rules[method] = value: type === method && "range" !== type && (rules[method] = !0)
            }
            return rules.maxlength && /-1|2147483647|524288/.test(rules.maxlength) && delete rules.maxlength,
            rules
        },
        dataRules: function(element) {
            var method, value, rules = {},
            $element = $(element);
            for (method in $.validator.methods) value = $element.data("rule-" + method.toLowerCase()),
            void 0 !== value && (rules[method] = value);
            return rules
        },
        staticRules: function(element) {
            var rules = {},
            validator = $.data(element.form, "validator");
            return validator.settings.rules && (rules = $.validator.normalizeRule(validator.settings.rules[element.name]) || {}),
            rules
        },
        normalizeRules: function(rules, element) {
            return $.each(rules,
            function(prop, val) {
                if (val === !1) return delete rules[prop],
                void 0;
                if (val.param || val.depends) {
                    var keepRule = !0;
                    switch (typeof val.depends) {
                    case "string":
                        keepRule = !!$(val.depends, element.form).length;
                        break;
                    case "function":
                        keepRule = val.depends.call(element, element)
                    }
                    keepRule ? rules[prop] = void 0 !== val.param ? val.param: !0 : delete rules[prop]
                }
            }),
            $.each(rules,
            function(rule, parameter) {
                rules[rule] = $.isFunction(parameter) ? parameter(element) : parameter
            }),
            $.each(["minlength", "maxlength"],
            function() {
                rules[this] && (rules[this] = Number(rules[this]))
            }),
            $.each(["rangelength", "range"],
            function() {
                var parts;
                rules[this] && ($.isArray(rules[this]) ? rules[this] = [Number(rules[this][0]), Number(rules[this][1])] : "string" == typeof rules[this] && (parts = rules[this].split(/[\s,]+/), rules[this] = [Number(parts[0]), Number(parts[1])]))
            }),
            $.validator.autoCreateRanges && (rules.min && rules.max && (rules.range = [rules.min, rules.max], delete rules.min, delete rules.max), rules.minlength && rules.maxlength && (rules.rangelength = [rules.minlength, rules.maxlength], delete rules.minlength, delete rules.maxlength)),
            rules
        },
        normalizeRule: function(data) {
            if ("string" == typeof data) {
                var transformed = {};
                $.each(data.split(/\s/),
                function() {
                    transformed[this] = !0
                }),
                data = transformed
            }
            return data
        },
        addMethod: function(name, method, message) {
            $.validator.methods[name] = method,
            $.validator.messages[name] = void 0 !== message ? message: $.validator.messages[name],
            method.length < 3 && $.validator.addClassRules(name, $.validator.normalizeRule(name))
        },
        methods: {
            required: function(value, element, param) {
                if (!this.depend(param, element)) return "dependency-mismatch";
                if ("select" === element.nodeName.toLowerCase()) {
                    var val = $(element).val();
                    return val && val.length > 0
                }
                return this.checkable(element) ? this.getLength(value, element) > 0 : $.trim(value).length > 0
            },
            email: function(value, element) {
                return this.optional(element) || /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))$/i.test(value)
            },
            url: function(value, element) {
                return this.optional(element) || /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(value)
            },
            date: function(value, element) {
                return this.optional(element) || !/Invalid|NaN/.test(new Date(value).toString())
            },
            dateISO: function(value, element) {
                return this.optional(element) || /^\d{4}[\/\-]\d{1,2}[\/\-]\d{1,2}$/.test(value)
            },
            number: function(value, element) {
                return this.optional(element) || /^-?(?:\d+|\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/.test(value)
            },
            digits: function(value, element) {
                return this.optional(element) || /^\d+$/.test(value)
            },
            creditcard: function(value, element) {
                if (this.optional(element)) return "dependency-mismatch";
                if (/[^0-9 \-]+/.test(value)) return ! 1;
                var nCheck = 0,
                nDigit = 0,
                bEven = !1;
                value = value.replace(/\D/g, "");
                for (var n = value.length - 1; n >= 0; n--) {
                    var cDigit = value.charAt(n);
                    nDigit = parseInt(cDigit, 10),
                    bEven && (nDigit *= 2) > 9 && (nDigit -= 9),
                    nCheck += nDigit,
                    bEven = !bEven
                }
                return 0 === nCheck % 10
            },
            minlength: function(value, element, param) {
                var length = $.isArray(value) ? value.length: this.getLength($.trim(value), element);
                return this.optional(element) || length >= param
            },
            maxlength: function(value, element, param) {
                var length = $.isArray(value) ? value.length: this.getLength($.trim(value), element);
                return this.optional(element) || param >= length
            },
            rangelength: function(value, element, param) {
                var length = $.isArray(value) ? value.length: this.getLength($.trim(value), element);
                return this.optional(element) || length >= param[0] && length <= param[1]
            },
            min: function(value, element, param) {
                return this.optional(element) || value >= param
            },
            max: function(value, element, param) {
                return this.optional(element) || param >= value
            },
            range: function(value, element, param) {
                return this.optional(element) || value >= param[0] && value <= param[1]
            },
            equalTo: function(value, element, param) {
                var target = $(param);
                return this.settings.onfocusout && target.unbind(".validate-equalTo").bind("blur.validate-equalTo",
                function() {
                    $(element).valid()
                }),
                value === target.val()
            },
            remote: function(value, element, param) {
                if (this.optional(element)) return "dependency-mismatch";
                var previous = this.previousValue(element);
                if (this.settings.messages[element.name] || (this.settings.messages[element.name] = {}), previous.originalMessage = this.settings.messages[element.name].remote, this.settings.messages[element.name].remote = previous.message, param = "string" == typeof param && {
                    url: param
                } || param, previous.old === value) return previous.valid;
                previous.old = value;
                var validator = this;
                this.startRequest(element);
                var data = {};
                return data[element.name] = value,
                $.ajax($.extend(!0, {
                    url: param,
                    mode: "abort",
                    port: "validate" + element.name,
                    dataType: "json",
                    data: data,
                    success: function(response) {
                        validator.settings.messages[element.name].remote = previous.originalMessage;
                        var valid = response === !0 || "true" === response;
                        if (valid) {
                            var submitted = validator.formSubmitted;
                            validator.prepareElement(element),
                            validator.formSubmitted = submitted,
                            validator.successList.push(element),
                            delete validator.invalid[element.name],
                            validator.showErrors()
                        } else {
                            var errors = {},
                            message = response || validator.defaultMessage(element, "remote");
                            errors[element.name] = previous.message = $.isFunction(message) ? message(value) : message,
                            validator.invalid[element.name] = !0,
                            validator.showErrors(errors)
                        }
                        previous.valid = valid,
                        validator.stopRequest(element, valid)
                    }
                },
                param)),
                "pending"
            }
        }
    }),
    $.format = $.validator.format
} (jQuery),
function($) {
    var pendingRequests = {};
    if ($.ajaxPrefilter) $.ajaxPrefilter(function(settings, _, xhr) {
        var port = settings.port;
        "abort" === settings.mode && (pendingRequests[port] && pendingRequests[port].abort(), pendingRequests[port] = xhr)
    });
    else {
        var ajax = $.ajax;
        $.ajax = function(settings) {
            var mode = ("mode" in settings ? settings: $.ajaxSettings).mode,
            port = ("port" in settings ? settings: $.ajaxSettings).port;
            return "abort" === mode ? (pendingRequests[port] && pendingRequests[port].abort(), pendingRequests[port] = ajax.apply(this, arguments), pendingRequests[port]) : ajax.apply(this, arguments)
        }
    }
} (jQuery),
function($) {
    $.extend($.fn, {
        validateDelegate: function(delegate, type, handler) {
            return this.bind(type,
            function(event) {
                var target = $(event.target);
                return target.is(delegate) ? handler.apply(target, arguments) : void 0
            })
        }
    })
} (jQuery),
function(factory) {
    "function" == typeof define && define.amd ? define(["jquery"], factory) : factory(jQuery)
} (function($, undefined) {
    var uuid = 0,
    slice = Array.prototype.slice,
    _cleanData = $.cleanData;
    $.cleanData = function(elems) {
        for (var elem, i = 0; null != (elem = elems[i]); i++) try {
            $(elem).triggerHandler("remove")
        } catch(e) {}
        _cleanData(elems)
    },
    $.widget = function(name, base, prototype) {
        var fullName, existingConstructor, constructor, basePrototype, proxiedPrototype = {},
        namespace = name.split(".")[0];
        name = name.split(".")[1],
        fullName = namespace + "-" + name,
        prototype || (prototype = base, base = $.Widget),
        $.expr[":"][fullName.toLowerCase()] = function(elem) {
            return !! $.data(elem, fullName)
        },
        $[namespace] = $[namespace] || {},
        existingConstructor = $[namespace][name],
        constructor = $[namespace][name] = function(options, element) {
            return this._createWidget ? (arguments.length && this._createWidget(options, element), void 0) : new constructor(options, element)
        },
        $.extend(constructor, existingConstructor, {
            version: prototype.version,
            _proto: $.extend({},
            prototype),
            _childConstructors: []
        }),
        basePrototype = new base,
        basePrototype.options = $.widget.extend({},
        basePrototype.options),
        $.each(prototype,
        function(prop, value) {
            return $.isFunction(value) ? (proxiedPrototype[prop] = function() {
                var _super = function() {
                    return base.prototype[prop].apply(this, arguments)
                },
                _superApply = function(args) {
                    return base.prototype[prop].apply(this, args)
                };
                return function() {
                    var returnValue, __super = this._super,
                    __superApply = this._superApply;
                    return this._super = _super,
                    this._superApply = _superApply,
                    returnValue = value.apply(this, arguments),
                    this._super = __super,
                    this._superApply = __superApply,
                    returnValue
                }
            } (), void 0) : (proxiedPrototype[prop] = value, void 0)
        }),
        constructor.prototype = $.widget.extend(basePrototype, {
            widgetEventPrefix: existingConstructor ? basePrototype.widgetEventPrefix || name: name
        },
        proxiedPrototype, {
            constructor: constructor,
            namespace: namespace,
            widgetName: name,
            widgetFullName: fullName
        }),
        existingConstructor ? ($.each(existingConstructor._childConstructors,
        function(i, child) {
            var childPrototype = child.prototype;
            $.widget(childPrototype.namespace + "." + childPrototype.widgetName, constructor, child._proto)
        }), delete existingConstructor._childConstructors) : base._childConstructors.push(constructor),
        $.widget.bridge(name, constructor)
    },
    $.widget.extend = function(target) {
        for (var key, value, input = slice.call(arguments, 1), inputIndex = 0, inputLength = input.length; inputLength > inputIndex; inputIndex++) for (key in input[inputIndex]) value = input[inputIndex][key],
        input[inputIndex].hasOwnProperty(key) && value !== undefined && (target[key] = $.isPlainObject(value) ? $.isPlainObject(target[key]) ? $.widget.extend({},
        target[key], value) : $.widget.extend({},
        value) : value);
        return target
    },
    $.widget.bridge = function(name, object) {
        var fullName = object.prototype.widgetFullName || name;
        $.fn[name] = function(options) {
            var isMethodCall = "string" == typeof options,
            args = slice.call(arguments, 1),
            returnValue = this;
            return options = !isMethodCall && args.length ? $.widget.extend.apply(null, [options].concat(args)) : options,
            isMethodCall ? this.each(function() {
                var methodValue, instance = $.data(this, fullName);
                return instance ? $.isFunction(instance[options]) && "_" !== options.charAt(0) ? (methodValue = instance[options].apply(instance, args), methodValue !== instance && methodValue !== undefined ? (returnValue = methodValue && methodValue.jquery ? returnValue.pushStack(methodValue.get()) : methodValue, !1) : void 0) : $.error("no such method '" + options + "' for " + name + " widget instance") : $.error("cannot call methods on " + name + " prior to initialization; attempted to call method '" + options + "'")
            }) : this.each(function() {
                var instance = $.data(this, fullName);
                instance ? instance.option(options || {})._init() : $.data(this, fullName, new object(options, this))
            }),
            returnValue
        }
    },
    $.Widget = function() {},
    $.Widget._childConstructors = [],
    $.Widget.prototype = {
        widgetName: "widget",
        widgetEventPrefix: "",
        defaultElement: "<div>",
        options: {
            disabled: !1,
            create: null
        },
        _createWidget: function(options, element) {
            element = $(element || this.defaultElement || this)[0],
            this.element = $(element),
            this.uuid = uuid++,
            this.eventNamespace = "." + this.widgetName + this.uuid,
            this.options = $.widget.extend({},
            this.options, this._getCreateOptions(), options),
            this.bindings = $(),
            this.hoverable = $(),
            this.focusable = $(),
            element !== this && ($.data(element, this.widgetFullName, this), this._on(!0, this.element, {
                remove: function(event) {
                    event.target === element && this.destroy()
                }
            }), this.document = $(element.style ? element.ownerDocument: element.document || element), this.window = $(this.document[0].defaultView || this.document[0].parentWindow)),
            this._create(),
            this._trigger("create", null, this._getCreateEventData()),
            this._init()
        },
        _getCreateOptions: $.noop,
        _getCreateEventData: $.noop,
        _create: $.noop,
        _init: $.noop,
        destroy: function() {
            this._destroy(),
            this.element.unbind(this.eventNamespace).removeData(this.widgetName).removeData(this.widgetFullName).removeData($.camelCase(this.widgetFullName)),
            this.widget().unbind(this.eventNamespace).removeAttr("aria-disabled").removeClass(this.widgetFullName + "-disabled ui-state-disabled"),
            this.bindings.unbind(this.eventNamespace),
            this.hoverable.removeClass("ui-state-hover"),
            this.focusable.removeClass("ui-state-focus")
        },
        _destroy: $.noop,
        widget: function() {
            return this.element
        },
        option: function(key, value) {
            var parts, curOption, i, options = key;
            if (0 === arguments.length) return $.widget.extend({},
            this.options);
            if ("string" == typeof key) if (options = {},
            parts = key.split("."), key = parts.shift(), parts.length) {
                for (curOption = options[key] = $.widget.extend({},
                this.options[key]), i = 0; i < parts.length - 1; i++) curOption[parts[i]] = curOption[parts[i]] || {},
                curOption = curOption[parts[i]];
                if (key = parts.pop(), 1 === arguments.length) return curOption[key] === undefined ? null: curOption[key];
                curOption[key] = value
            } else {
                if (1 === arguments.length) return this.options[key] === undefined ? null: this.options[key];
                options[key] = value
            }
            return this._setOptions(options),
            this
        },
        _setOptions: function(options) {
            var key;
            for (key in options) this._setOption(key, options[key]);
            return this
        },
        _setOption: function(key, value) {
            return this.options[key] = value,
            "disabled" === key && (this.widget().toggleClass(this.widgetFullName + "-disabled ui-state-disabled", !!value).attr("aria-disabled", value), this.hoverable.removeClass("ui-state-hover"), this.focusable.removeClass("ui-state-focus")),
            this
        },
        enable: function() {
            return this._setOption("disabled", !1)
        },
        disable: function() {
            return this._setOption("disabled", !0)
        },
        _on: function(suppressDisabledCheck, element, handlers) {
            var delegateElement, instance = this;
            "boolean" != typeof suppressDisabledCheck && (handlers = element, element = suppressDisabledCheck, suppressDisabledCheck = !1),
            handlers ? (element = delegateElement = $(element), this.bindings = this.bindings.add(element)) : (handlers = element, element = this.element, delegateElement = this.widget()),
            $.each(handlers,
            function(event, handler) {
                function handlerProxy() {
                    return suppressDisabledCheck || instance.options.disabled !== !0 && !$(this).hasClass("ui-state-disabled") ? ("string" == typeof handler ? instance[handler] : handler).apply(instance, arguments) : void 0
                }
                "string" != typeof handler && (handlerProxy.guid = handler.guid = handler.guid || handlerProxy.guid || $.guid++);
                var match = event.match(/^(\w+)\s*(.*)$/),
                eventName = match[1] + instance.eventNamespace,
                selector = match[2];
                selector ? delegateElement.delegate(selector, eventName, handlerProxy) : element.bind(eventName, handlerProxy)
            })
        },
        _off: function(element, eventName) {
            eventName = (eventName || "").split(" ").join(this.eventNamespace + " ") + this.eventNamespace,
            element.unbind(eventName).undelegate(eventName)
        },
        _delay: function(handler, delay) {
            function handlerProxy() {
                return ("string" == typeof handler ? instance[handler] : handler).apply(instance, arguments)
            }
            var instance = this;
            return setTimeout(handlerProxy, delay || 0)
        },
        _hoverable: function(element) {
            this.hoverable = this.hoverable.add(element),
            this._on(element, {
                mouseenter: function(event) {
                    $(event.currentTarget).addClass("ui-state-hover")
                },
                mouseleave: function(event) {
                    $(event.currentTarget).removeClass("ui-state-hover")
                }
            })
        },
        _focusable: function(element) {
            this.focusable = this.focusable.add(element),
            this._on(element, {
                focusin: function(event) {
                    $(event.currentTarget).addClass("ui-state-focus")
                },
                focusout: function(event) {
                    $(event.currentTarget).removeClass("ui-state-focus")
                }
            })
        },
        _trigger: function(type, event, data) {
            var prop, orig, callback = this.options[type];
            if (data = data || {},
            event = $.Event(event), event.type = (type === this.widgetEventPrefix ? type: this.widgetEventPrefix + type).toLowerCase(), event.target = this.element[0], orig = event.originalEvent) for (prop in orig) prop in event || (event[prop] = orig[prop]);
            return this.element.trigger(event, data),
            !($.isFunction(callback) && callback.apply(this.element[0], [event].concat(data)) === !1 || event.isDefaultPrevented())
        }
    },
    $.each({
        show: "fadeIn",
        hide: "fadeOut"
    },
    function(method, defaultEffect) {
        $.Widget.prototype["_" + method] = function(element, options, callback) {
            "string" == typeof options && (options = {
                effect: options
            });
            var hasOptions, effectName = options ? options === !0 || "number" == typeof options ? defaultEffect: options.effect || defaultEffect: method;
            options = options || {},
            "number" == typeof options && (options = {
                duration: options
            }),
            hasOptions = !$.isEmptyObject(options),
            options.complete = callback,
            options.delay && element.delay(options.delay),
            hasOptions && $.effects && $.effects.effect[effectName] ? element[method](options) : effectName !== method && element[effectName] ? element[effectName](options.duration, options.easing, callback) : element.queue(function(next) {
                $(this)[method](),
                callback && callback.call(element[0]),
                next()
            })
        }
    })
}),
function(factory) {
    "use strict";
    "function" == typeof define && define.amd ? define(["jquery"], factory) : factory(window.jQuery)
} (function($) {
    "use strict";
    var counter = 0;
    $.ajaxTransport("iframe",
    function(options) {
        if (options.async) {
            var form, iframe, addParamChar, initialIframeSrc = options.initialIframeSrc || "javascript:false;";
            return {
                send: function(_, completeCallback) {
                    form = $('<form style="display:none;"></form>'),
                    form.attr("accept-charset", options.formAcceptCharset),
                    addParamChar = /\?/.test(options.url) ? "&": "?",
                    "DELETE" === options.type ? (options.url = options.url + addParamChar + "_method=DELETE", options.type = "POST") : "PUT" === options.type ? (options.url = options.url + addParamChar + "_method=PUT", options.type = "POST") : "PATCH" === options.type && (options.url = options.url + addParamChar + "_method=PATCH", options.type = "POST"),
                    counter += 1,
                    iframe = $('<iframe src="' + initialIframeSrc + '" name="iframe-transport-' + counter + '"></iframe>').bind("load",
                    function() {
                        var fileInputClones, paramNames = $.isArray(options.paramName) ? options.paramName: [options.paramName];
                        iframe.unbind("load").bind("load",
                        function() {
                            var response;
                            try {
                                if (response = iframe.contents(), !response.length || !response[0].firstChild) throw new Error
                            } catch(e) {
                                response = void 0
                            }
                            completeCallback(200, "success", {
                                iframe: response
                            }),
                            $('<iframe src="' + initialIframeSrc + '"></iframe>').appendTo(form),
                            window.setTimeout(function() {
                                form.remove()
                            },
                            0)
                        }),
                        form.prop("target", iframe.prop("name")).prop("action", options.url).prop("method", options.type),
                        options.formData && $.each(options.formData,
                        function(index, field) {
                            $('<input type="hidden"/>').prop("name", field.name).val(field.value).appendTo(form)
                        }),
                        options.fileInput && options.fileInput.length && "POST" === options.type && (fileInputClones = options.fileInput.clone(), options.fileInput.after(function(index) {
                            return fileInputClones[index]
                        }), options.paramName && options.fileInput.each(function(index) {
                            $(this).prop("name", paramNames[index] || options.paramName)
                        }), form.append(options.fileInput).prop("enctype", "multipart/form-data").prop("encoding", "multipart/form-data"), options.fileInput.removeAttr("form")),
                        form.submit(),
                        fileInputClones && fileInputClones.length && options.fileInput.each(function(index, input) {
                            var clone = $(fileInputClones[index]);
                            $(input).prop("name", clone.prop("name")).attr("form", clone.attr("form")),
                            clone.replaceWith(input)
                        })
                    }),
                    form.append(iframe).appendTo(document.body)
                },
                abort: function() {
                    iframe && iframe.unbind("load").prop("src", initialIframeSrc),
                    form && form.remove()
                }
            }
        }
    }),
    $.ajaxSetup({
        converters: {
            "iframe text": function(iframe) {
                return iframe && $(iframe[0].body).text()
            },
            "iframe json": function(iframe) {
                return iframe && $.parseJSON($(iframe[0].body).text())
            },
            "iframe html": function(iframe) {
                return iframe && $(iframe[0].body).html()
            },
            "iframe xml": function(iframe) {
                var xmlDoc = iframe && iframe[0];
                return xmlDoc && $.isXMLDoc(xmlDoc) ? xmlDoc: $.parseXML(xmlDoc.XMLDocument && xmlDoc.XMLDocument.xml || $(xmlDoc.body).html())
            },
            "iframe script": function(iframe) {
                return iframe && $.globalEval($(iframe[0].body).text())
            }
        }
    })
}),
function(factory) {
    "use strict";
    "function" == typeof define && define.amd ? define(["jquery", "jquery.ui.widget"], factory) : factory(window.jQuery)
} (function($) {
    "use strict";
    $.support.fileInput = !(new RegExp("(Android (1\\.[0156]|2\\.[01]))|(Windows Phone (OS 7|8\\.0))|(XBLWP)|(ZuneWP)|(WPDesktop)|(w(eb)?OSBrowser)|(webOS)|(Kindle/(1\\.0|2\\.[05]|3\\.0))").test(window.navigator.userAgent) || $('<input type="file">').prop("disabled")),
    $.support.xhrFileUpload = !(!window.ProgressEvent || !window.FileReader),
    $.support.xhrFormDataFileUpload = !!window.FormData,
    $.support.blobSlice = window.Blob && (Blob.prototype.slice || Blob.prototype.webkitSlice || Blob.prototype.mozSlice),
    $.widget("blueimp.fileupload", {
        options: {
            dropZone: $(document),
            pasteZone: $(document),
            fileInput: void 0,
            replaceFileInput: !0,
            paramName: void 0,
            singleFileUploads: !0,
            limitMultiFileUploads: void 0,
            limitMultiFileUploadSize: void 0,
            limitMultiFileUploadSizeOverhead: 512,
            sequentialUploads: !1,
            limitConcurrentUploads: void 0,
            forceIframeTransport: !1,
            redirect: void 0,
            redirectParamName: void 0,
            postMessage: void 0,
            multipart: !0,
            maxChunkSize: void 0,
            uploadedBytes: void 0,
            recalculateProgress: !0,
            progressInterval: 100,
            bitrateInterval: 500,
            autoUpload: !0,
            messages: {
                uploadedBytes: "Uploaded bytes exceed file size"
            },
            i18n: function(message, context) {
                return message = this.messages[message] || message.toString(),
                context && $.each(context,
                function(key, value) {
                    message = message.replace("{" + key + "}", value)
                }),
                message
            },
            formData: function(form) {
                return form.serializeArray()
            },
            add: function(e, data) {
                return e.isDefaultPrevented() ? !1 : ((data.autoUpload || data.autoUpload !== !1 && $(this).fileupload("option", "autoUpload")) && data.process().done(function() {
                    data.submit()
                }), void 0)
            },
            processData: !1,
            contentType: !1,
            cache: !1
        },
        _specialOptions: ["fileInput", "dropZone", "pasteZone", "multipart", "forceIframeTransport"],
        _blobSlice: $.support.blobSlice &&
        function() {
            var slice = this.slice || this.webkitSlice || this.mozSlice;
            return slice.apply(this, arguments)
        },
        _BitrateTimer: function() {
            this.timestamp = Date.now ? Date.now() : (new Date).getTime(),
            this.loaded = 0,
            this.bitrate = 0,
            this.getBitrate = function(now, loaded, interval) {
                var timeDiff = now - this.timestamp;
                return (!this.bitrate || !interval || timeDiff > interval) && (this.bitrate = 8 * (loaded - this.loaded) * (1e3 / timeDiff), this.loaded = loaded, this.timestamp = now),
                this.bitrate
            }
        },
        _isXHRUpload: function(options) {
            return ! options.forceIframeTransport && (!options.multipart && $.support.xhrFileUpload || $.support.xhrFormDataFileUpload)
        },
        _getFormData: function(options) {
            var formData;
            return "function" === $.type(options.formData) ? options.formData(options.form) : $.isArray(options.formData) ? options.formData: "object" === $.type(options.formData) ? (formData = [], $.each(options.formData,
            function(name, value) {
                formData.push({
                    name: name,
                    value: value

                })
            }), formData) : []
        },
        _getTotal: function(files) {
            var total = 0;
            return $.each(files,
            function(index, file) {
                total += file.size || 1
            }),
            total
        },
        _initProgressObject: function(obj) {
            var progress = {
                loaded: 0,
                total: 0,
                bitrate: 0
            };
            obj._progress ? $.extend(obj._progress, progress) : obj._progress = progress
        },
        _initResponseObject: function(obj) {
            var prop;
            if (obj._response) for (prop in obj._response) obj._response.hasOwnProperty(prop) && delete obj._response[prop];
            else obj._response = {}
        },
        _onProgress: function(e, data) {
            if (e.lengthComputable) {
                var loaded, now = Date.now ? Date.now() : (new Date).getTime();
                if (data._time && data.progressInterval && now - data._time < data.progressInterval && e.loaded !== e.total) return;
                data._time = now,
                loaded = Math.floor(e.loaded / e.total * (data.chunkSize || data._progress.total)) + (data.uploadedBytes || 0),
                this._progress.loaded += loaded - data._progress.loaded,
                this._progress.bitrate = this._bitrateTimer.getBitrate(now, this._progress.loaded, data.bitrateInterval),
                data._progress.loaded = data.loaded = loaded,
                data._progress.bitrate = data.bitrate = data._bitrateTimer.getBitrate(now, loaded, data.bitrateInterval),
                this._trigger("progress", $.Event("progress", {
                    delegatedEvent: e
                }), data),
                this._trigger("progressall", $.Event("progressall", {
                    delegatedEvent: e
                }), this._progress)
            }
        },
        _initProgressListener: function(options) {
            var that = this,
            xhr = options.xhr ? options.xhr() : $.ajaxSettings.xhr();
            xhr.upload && ($(xhr.upload).bind("progress",
            function(e) {
                var oe = e.originalEvent;
                e.lengthComputable = oe.lengthComputable,
                e.loaded = oe.loaded,
                e.total = oe.total,
                that._onProgress(e, options)
            }), options.xhr = function() {
                return xhr
            })
        },
        _isInstanceOf: function(type, obj) {
            return Object.prototype.toString.call(obj) === "[object " + type + "]"
        },
        _initXHRData: function(options) {
            var formData, that = this,
            file = options.files[0],
            multipart = options.multipart || !$.support.xhrFileUpload,
            paramName = "array" === $.type(options.paramName) ? options.paramName[0] : options.paramName;
            options.headers = $.extend({},
            options.headers),
            options.contentRange && (options.headers["Content-Range"] = options.contentRange),
            multipart && !options.blob && this._isInstanceOf("File", file) || (options.headers["Content-Disposition"] = 'attachment; filename="' + encodeURI(file.name) + '"'),
            multipart ? $.support.xhrFormDataFileUpload && (options.postMessage ? (formData = this._getFormData(options), options.blob ? formData.push({
                name: paramName,
                value: options.blob
            }) : $.each(options.files,
            function(index, file) {
                formData.push({
                    name: "array" === $.type(options.paramName) && options.paramName[index] || paramName,
                    value: file
                })
            })) : (that._isInstanceOf("FormData", options.formData) ? formData = options.formData: (formData = new FormData, $.each(this._getFormData(options),
            function(index, field) {
                formData.append(field.name, field.value)
            })), options.blob ? formData.append(paramName, options.blob, file.name) : $.each(options.files,
            function(index, file) { (that._isInstanceOf("File", file) || that._isInstanceOf("Blob", file)) && formData.append("array" === $.type(options.paramName) && options.paramName[index] || paramName, file, file.uploadName || file.name)
            })), options.data = formData) : (options.contentType = file.type || "application/octet-stream", options.data = options.blob || file),
            options.blob = null
        },
        _initIframeSettings: function(options) {
            var targetHost = $("<a></a>").prop("href", options.url).prop("host");
            options.dataType = "iframe " + (options.dataType || ""),
            options.formData = this._getFormData(options),
            options.redirect && targetHost && targetHost !== location.host && options.formData.push({
                name: options.redirectParamName || "redirect",
                value: options.redirect
            })
        },
        _initDataSettings: function(options) {
            this._isXHRUpload(options) ? (this._chunkedUpload(options, !0) || (options.data || this._initXHRData(options), this._initProgressListener(options)), options.postMessage && (options.dataType = "postmessage " + (options.dataType || ""))) : this._initIframeSettings(options)
        },
        _getParamName: function(options) {
            var fileInput = $(options.fileInput),
            paramName = options.paramName;
            return paramName ? $.isArray(paramName) || (paramName = [paramName]) : (paramName = [], fileInput.each(function() {
                for (var input = $(this), name = input.prop("name") || "files[]", i = (input.prop("files") || [1]).length; i;) paramName.push(name),
                i -= 1
            }), paramName.length || (paramName = [fileInput.prop("name") || "files[]"])),
            paramName
        },
        _initFormSettings: function(options) {
            options.form && options.form.length || (options.form = $(options.fileInput.prop("form")), options.form.length || (options.form = $(this.options.fileInput.prop("form")))),
            options.paramName = this._getParamName(options),
            options.url || (options.url = options.form.prop("action") || location.href),
            options.type = (options.type || "string" === $.type(options.form.prop("method")) && options.form.prop("method") || "").toUpperCase(),
            "POST" !== options.type && "PUT" !== options.type && "PATCH" !== options.type && (options.type = "POST"),
            options.formAcceptCharset || (options.formAcceptCharset = options.form.attr("accept-charset"))
        },
        _getAJAXSettings: function(data) {
            var options = $.extend({},
            this.options, data);
            return this._initFormSettings(options),
            this._initDataSettings(options),
            options
        },
        _getDeferredState: function(deferred) {
            return deferred.state ? deferred.state() : deferred.isResolved() ? "resolved": deferred.isRejected() ? "rejected": "pending"
        },
        _enhancePromise: function(promise) {
            return promise.success = promise.done,
            promise.error = promise.fail,
            promise.complete = promise.always,
            promise
        },
        _getXHRPromise: function(resolveOrReject, context, args) {
            var dfd = $.Deferred(),
            promise = dfd.promise();
            return context = context || this.options.context || promise,
            resolveOrReject === !0 ? dfd.resolveWith(context, args) : resolveOrReject === !1 && dfd.rejectWith(context, args),
            promise.abort = dfd.promise,
            this._enhancePromise(promise)
        },
        _addConvenienceMethods: function(e, data) {
            var that = this,
            getPromise = function(args) {
                return $.Deferred().resolveWith(that, args).promise()
            };
            data.process = function(resolveFunc, rejectFunc) {
                return (resolveFunc || rejectFunc) && (data._processQueue = this._processQueue = (this._processQueue || getPromise([this])).pipe(function() {
                    return data.errorThrown ? $.Deferred().rejectWith(that, [data]).promise() : getPromise(arguments)
                }).pipe(resolveFunc, rejectFunc)),
                this._processQueue || getPromise([this])
            },
            data.submit = function() {
                return "pending" !== this.state() && (data.jqXHR = this.jqXHR = that._trigger("submit", $.Event("submit", {
                    delegatedEvent: e
                }), this) !== !1 && that._onSend(e, this)),
                this.jqXHR || that._getXHRPromise()
            },
            data.abort = function() {
                return this.jqXHR ? this.jqXHR.abort() : (this.errorThrown = "abort", that._trigger("fail", null, this), that._getXHRPromise(!1))
            },
            data.state = function() {
                return this.jqXHR ? that._getDeferredState(this.jqXHR) : this._processQueue ? that._getDeferredState(this._processQueue) : void 0
            },
            data.processing = function() {
                return ! this.jqXHR && this._processQueue && "pending" === that._getDeferredState(this._processQueue)
            },
            data.progress = function() {
                return this._progress
            },
            data.response = function() {
                return this._response
            }
        },
        _getUploadedBytes: function(jqXHR) {
            var range = jqXHR.getResponseHeader("Range"),
            parts = range && range.split("-"),
            upperBytesPos = parts && parts.length > 1 && parseInt(parts[1], 10);
            return upperBytesPos && upperBytesPos + 1
        },
        _chunkedUpload: function(options, testOnly) {
            options.uploadedBytes = options.uploadedBytes || 0;
            var jqXHR, upload, that = this,
            file = options.files[0],
            fs = file.size,
            ub = options.uploadedBytes,
            mcs = options.maxChunkSize || fs,
            slice = this._blobSlice,
            dfd = $.Deferred(),
            promise = dfd.promise();
            return this._isXHRUpload(options) && slice && (ub || fs > mcs) && !options.data ? testOnly ? !0 : ub >= fs ? (file.error = options.i18n("uploadedBytes"), this._getXHRPromise(!1, options.context, [null, "error", file.error])) : (upload = function() {
                var o = $.extend({},
                options),
                currentLoaded = o._progress.loaded;
                o.blob = slice.call(file, ub, ub + mcs, file.type),
                o.chunkSize = o.blob.size,
                o.contentRange = "bytes " + ub + "-" + (ub + o.chunkSize - 1) + "/" + fs,
                that._initXHRData(o),
                that._initProgressListener(o),
                jqXHR = (that._trigger("chunksend", null, o) !== !1 && $.ajax(o) || that._getXHRPromise(!1, o.context)).done(function(result, textStatus, jqXHR) {
                    ub = that._getUploadedBytes(jqXHR) || ub + o.chunkSize,
                    currentLoaded + o.chunkSize - o._progress.loaded && that._onProgress($.Event("progress", {
                        lengthComputable: !0,
                        loaded: ub - o.uploadedBytes,
                        total: ub - o.uploadedBytes
                    }), o),
                    options.uploadedBytes = o.uploadedBytes = ub,
                    o.result = result,
                    o.textStatus = textStatus,
                    o.jqXHR = jqXHR,
                    that._trigger("chunkdone", null, o),
                    that._trigger("chunkalways", null, o),
                    fs > ub ? upload() : dfd.resolveWith(o.context, [result, textStatus, jqXHR])
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    o.jqXHR = jqXHR,
                    o.textStatus = textStatus,
                    o.errorThrown = errorThrown,
                    that._trigger("chunkfail", null, o),
                    that._trigger("chunkalways", null, o),
                    dfd.rejectWith(o.context, [jqXHR, textStatus, errorThrown])
                })
            },
            this._enhancePromise(promise), promise.abort = function() {
                return jqXHR.abort()
            },
            upload(), promise) : !1
        },
        _beforeSend: function(e, data) {
            0 === this._active && (this._trigger("start"), this._bitrateTimer = new this._BitrateTimer, this._progress.loaded = this._progress.total = 0, this._progress.bitrate = 0),
            this._initResponseObject(data),
            this._initProgressObject(data),
            data._progress.loaded = data.loaded = data.uploadedBytes || 0,
            data._progress.total = data.total = this._getTotal(data.files) || 1,
            data._progress.bitrate = data.bitrate = 0,
            this._active += 1,
            this._progress.loaded += data.loaded,
            this._progress.total += data.total
        },
        _onDone: function(result, textStatus, jqXHR, options) {
            var total = options._progress.total,
            response = options._response;
            options._progress.loaded < total && this._onProgress($.Event("progress", {
                lengthComputable: !0,
                loaded: total,
                total: total
            }), options),
            response.result = options.result = result,
            response.textStatus = options.textStatus = textStatus,
            response.jqXHR = options.jqXHR = jqXHR,
            this._trigger("done", null, options)
        },
        _onFail: function(jqXHR, textStatus, errorThrown, options) {
            var response = options._response;
            options.recalculateProgress && (this._progress.loaded -= options._progress.loaded, this._progress.total -= options._progress.total),
            response.jqXHR = options.jqXHR = jqXHR,
            response.textStatus = options.textStatus = textStatus,
            response.errorThrown = options.errorThrown = errorThrown,
            this._trigger("fail", null, options)
        },
        _onAlways: function(jqXHRorResult, textStatus, jqXHRorError, options) {
            this._trigger("always", null, options)
        },
        _onSend: function(e, data) {
            data.submit || this._addConvenienceMethods(e, data);
            var jqXHR, aborted, slot, pipe, that = this,
            options = that._getAJAXSettings(data),
            send = function() {
                return that._sending += 1,
                options._bitrateTimer = new that._BitrateTimer,
                jqXHR = jqXHR || ((aborted || that._trigger("send", $.Event("send", {
                    delegatedEvent: e
                }), options) === !1) && that._getXHRPromise(!1, options.context, aborted) || that._chunkedUpload(options) || $.ajax(options)).done(function(result, textStatus, jqXHR) {
                    that._onDone(result, textStatus, jqXHR, options)
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    that._onFail(jqXHR, textStatus, errorThrown, options)
                }).always(function(jqXHRorResult, textStatus, jqXHRorError) {
                    if (that._onAlways(jqXHRorResult, textStatus, jqXHRorError, options), that._sending -= 1, that._active -= 1, options.limitConcurrentUploads && options.limitConcurrentUploads > that._sending) for (var nextSlot = that._slots.shift(); nextSlot;) {
                        if ("pending" === that._getDeferredState(nextSlot)) {
                            nextSlot.resolve();
                            break
                        }
                        nextSlot = that._slots.shift()
                    }
                    0 === that._active && that._trigger("stop")
                })
            };
            return this._beforeSend(e, options),
            this.options.sequentialUploads || this.options.limitConcurrentUploads && this.options.limitConcurrentUploads <= this._sending ? (this.options.limitConcurrentUploads > 1 ? (slot = $.Deferred(), this._slots.push(slot), pipe = slot.pipe(send)) : (this._sequence = this._sequence.pipe(send, send), pipe = this._sequence), pipe.abort = function() {
                return aborted = [void 0, "abort", "abort"],
                jqXHR ? jqXHR.abort() : (slot && slot.rejectWith(options.context, aborted), send())
            },
            this._enhancePromise(pipe)) : send()
        },
        _onAdd: function(e, data) {
            var paramNameSet, paramNameSlice, fileSet, i, that = this,
            result = !0,
            options = $.extend({},
            this.options, data),
            files = data.files,
            filesLength = files.length,
            limit = options.limitMultiFileUploads,
            limitSize = options.limitMultiFileUploadSize,
            overhead = options.limitMultiFileUploadSizeOverhead,
            batchSize = 0,
            paramName = this._getParamName(options),
            j = 0;
            if (!limitSize || filesLength && void 0 !== files[0].size || (limitSize = void 0), (options.singleFileUploads || limit || limitSize) && this._isXHRUpload(options)) if (options.singleFileUploads || limitSize || !limit) if (!options.singleFileUploads && limitSize) for (fileSet = [], paramNameSet = [], i = 0; filesLength > i; i += 1) batchSize += files[i].size + overhead,
            (i + 1 === filesLength || batchSize + files[i + 1].size + overhead > limitSize || limit && i + 1 - j >= limit) && (fileSet.push(files.slice(j, i + 1)), paramNameSlice = paramName.slice(j, i + 1), paramNameSlice.length || (paramNameSlice = paramName), paramNameSet.push(paramNameSlice), j = i + 1, batchSize = 0);
            else paramNameSet = paramName;
            else for (fileSet = [], paramNameSet = [], i = 0; filesLength > i; i += limit) fileSet.push(files.slice(i, i + limit)),
            paramNameSlice = paramName.slice(i, i + limit),
            paramNameSlice.length || (paramNameSlice = paramName),
            paramNameSet.push(paramNameSlice);
            else fileSet = [files],
            paramNameSet = [paramName];
            return data.originalFiles = files,
            $.each(fileSet || files,
            function(index, element) {
                var newData = $.extend({},
                data);
                return newData.files = fileSet ? element: [element],
                newData.paramName = paramNameSet[index],
                that._initResponseObject(newData),
                that._initProgressObject(newData),
                that._addConvenienceMethods(e, newData),
                result = that._trigger("add", $.Event("add", {
                    delegatedEvent: e
                }), newData)
            }),
            result
        },
        _replaceFileInput: function(input) {
            var inputClone = input.clone(!0);
            $("<form></form>").append(inputClone)[0].reset(),
            input.after(inputClone).detach(),
            $.cleanData(input.unbind("remove")),
            this.options.fileInput = this.options.fileInput.map(function(i, el) {
                return el === input[0] ? inputClone[0] : el
            }),
            input[0] === this.element[0] && (this.element = inputClone)
        },
        _handleFileTreeEntry: function(entry, path) {
            var dirReader, that = this,
            dfd = $.Deferred(),
            errorHandler = function(e) {
                e && !e.entry && (e.entry = entry),
                dfd.resolve([e])
            };
            return path = path || "",
            entry.isFile ? entry._file ? (entry._file.relativePath = path, dfd.resolve(entry._file)) : entry.file(function(file) {
                file.relativePath = path,
                dfd.resolve(file)
            },
            errorHandler) : entry.isDirectory ? (dirReader = entry.createReader(), dirReader.readEntries(function(entries) {
                that._handleFileTreeEntries(entries, path + entry.name + "/").done(function(files) {
                    dfd.resolve(files)
                }).fail(errorHandler)
            },
            errorHandler)) : dfd.resolve([]),
            dfd.promise()
        },
        _handleFileTreeEntries: function(entries, path) {
            var that = this;
            return $.when.apply($, $.map(entries,
            function(entry) {
                return that._handleFileTreeEntry(entry, path)
            })).pipe(function() {
                return Array.prototype.concat.apply([], arguments)
            })
        },
        _getDroppedFiles: function(dataTransfer) {
            dataTransfer = dataTransfer || {};
            var items = dataTransfer.items;
            return items && items.length && (items[0].webkitGetAsEntry || items[0].getAsEntry) ? this._handleFileTreeEntries($.map(items,
            function(item) {
                var entry;
                return item.webkitGetAsEntry ? (entry = item.webkitGetAsEntry(), entry && (entry._file = item.getAsFile()), entry) : item.getAsEntry()
            })) : $.Deferred().resolve($.makeArray(dataTransfer.files)).promise()
        },
        _getSingleFileInputFiles: function(fileInput) {
            fileInput = $(fileInput);
            var files, value, entries = fileInput.prop("webkitEntries") || fileInput.prop("entries");
            if (entries && entries.length) return this._handleFileTreeEntries(entries);
            if (files = $.makeArray(fileInput.prop("files")), files.length) void 0 === files[0].name && files[0].fileName && $.each(files,
            function(index, file) {
                file.name = file.fileName,
                file.size = file.fileSize
            });
            else {
                if (value = fileInput.prop("value"), !value) return $.Deferred().resolve([]).promise();
                files = [{
                    name: value.replace(/^.*\\/, "")
                }]
            }
            return $.Deferred().resolve(files).promise()
        },
        _getFileInputFiles: function(fileInput) {
            return fileInput instanceof $ && 1 !== fileInput.length ? $.when.apply($, $.map(fileInput, this._getSingleFileInputFiles)).pipe(function() {
                return Array.prototype.concat.apply([], arguments)
            }) : this._getSingleFileInputFiles(fileInput)
        },
        _onChange: function(e) {
            var that = this,
            data = {
                fileInput: $(e.target),
                form: $(e.target.form)
            };
            this._getFileInputFiles(data.fileInput).always(function(files) {
                data.files = files,
                that.options.replaceFileInput && that._replaceFileInput(data.fileInput),
                that._trigger("change", $.Event("change", {
                    delegatedEvent: e
                }), data) !== !1 && that._onAdd(e, data)
            })
        },
        _onPaste: function(e) {
            var items = e.originalEvent && e.originalEvent.clipboardData && e.originalEvent.clipboardData.items,
            data = {
                files: []
            };
            items && items.length && ($.each(items,
            function(index, item) {
                var file = item.getAsFile && item.getAsFile();
                file && data.files.push(file)
            }), this._trigger("paste", $.Event("paste", {
                delegatedEvent: e
            }), data) !== !1 && this._onAdd(e, data))
        },
        _onDrop: function(e) {
            e.dataTransfer = e.originalEvent && e.originalEvent.dataTransfer;
            var that = this,
            dataTransfer = e.dataTransfer,
            data = {};
            dataTransfer && dataTransfer.files && dataTransfer.files.length && (e.preventDefault(), this._getDroppedFiles(dataTransfer).always(function(files) {
                data.files = files,
                that._trigger("drop", $.Event("drop", {
                    delegatedEvent: e
                }), data) !== !1 && that._onAdd(e, data)
            }))
        },
        _onDragOver: function(e) {
            e.dataTransfer = e.originalEvent && e.originalEvent.dataTransfer;
            var dataTransfer = e.dataTransfer;
            dataTransfer && -1 !== $.inArray("Files", dataTransfer.types) && this._trigger("dragover", $.Event("dragover", {
                delegatedEvent: e
            })) !== !1 && (e.preventDefault(), dataTransfer.dropEffect = "copy")
        },
        _initEventHandlers: function() {
            this._isXHRUpload(this.options) && (this._on(this.options.dropZone, {
                dragover: this._onDragOver,
                drop: this._onDrop
            }), this._on(this.options.pasteZone, {
                paste: this._onPaste
            })),
            $.support.fileInput && this._on(this.options.fileInput, {
                change: this._onChange
            })
        },
        _destroyEventHandlers: function() {
            this._off(this.options.dropZone, "dragover drop"),
            this._off(this.options.pasteZone, "paste"),
            this._off(this.options.fileInput, "change")
        },
        _setOption: function(key, value) {
            var reinit = -1 !== $.inArray(key, this._specialOptions);
            reinit && this._destroyEventHandlers(),
            this._super(key, value),
            reinit && (this._initSpecialOptions(), this._initEventHandlers())
        },
        _initSpecialOptions: function() {
            var options = this.options;
            void 0 === options.fileInput ? options.fileInput = this.element.is('input[type="file"]') ? this.element: this.element.find('input[type="file"]') : options.fileInput instanceof $ || (options.fileInput = $(options.fileInput)),
            options.dropZone instanceof $ || (options.dropZone = $(options.dropZone)),
            options.pasteZone instanceof $ || (options.pasteZone = $(options.pasteZone))
        },
        _getRegExp: function(str) {
            var parts = str.split("/"),
            modifiers = parts.pop();
            return parts.shift(),
            new RegExp(parts.join("/"), modifiers)
        },
        _isRegExpOption: function(key, value) {
            return "url" !== key && "string" === $.type(value) && /^\/.*\/[igm]{0,3}$/.test(value)
        },
        _initDataAttributes: function() {
            var that = this,
            options = this.options;
            $.each($(this.element[0].cloneNode(!1)).data(),
            function(key, value) {
                that._isRegExpOption(key, value) && (value = that._getRegExp(value)),
                options[key] = value
            })
        },
        _create: function() {
            this._initDataAttributes(),
            this._initSpecialOptions(),
            this._slots = [],
            this._sequence = this._getXHRPromise(!0),
            this._sending = this._active = 0,
            this._initProgressObject(this),
            this._initEventHandlers()
        },
        active: function() {
            return this._active
        },
        progress: function() {
            return this._progress
        },
        add: function(data) {
            var that = this;
            data && !this.options.disabled && (data.fileInput && !data.files ? this._getFileInputFiles(data.fileInput).always(function(files) {
                data.files = files,
                that._onAdd(null, data)
            }) : (data.files = $.makeArray(data.files), this._onAdd(null, data)))
        },
        send: function(data) {
            if (data && !this.options.disabled) {
                if (data.fileInput && !data.files) {
                    var jqXHR, aborted, that = this,
                    dfd = $.Deferred(),
                    promise = dfd.promise();
                    return promise.abort = function() {
                        return aborted = !0,
                        jqXHR ? jqXHR.abort() : (dfd.reject(null, "abort", "abort"), promise)
                    },
                    this._getFileInputFiles(data.fileInput).always(function(files) {
                        if (!aborted) {
                            if (!files.length) return dfd.reject(),
                            void 0;
                            data.files = files,
                            jqXHR = that._onSend(null, data).then(function(result, textStatus, jqXHR) {
                                dfd.resolve(result, textStatus, jqXHR)
                            },
                            function(jqXHR, textStatus, errorThrown) {
                                dfd.reject(jqXHR, textStatus, errorThrown)
                            })
                        }
                    }),
                    this._enhancePromise(promise)
                }
                if (data.files = $.makeArray(data.files), data.files.length) return this._onSend(null, data)
            }
            return this._getXHRPromise(!1, data && data.context)
        }
    })
}),
function() {
    "use strict";
    function encodeHTMLSource() {
        var encodeHTMLRules = {
            "&": "&#38;",
            "<": "&#60;",
            ">": "&#62;",
            '"': "&#34;",
            "'": "&#39;",
            "/": "&#47;"
        },
        matchHTML = /&(?!#?\w+;)|<|>|"|'|\//g;
        return function() {
            return this ? this.replace(matchHTML,
            function(m) {
                return encodeHTMLRules[m] || m
            }) : this
        }
    }
    function resolveDefs(c, block, def) {
        return ("string" == typeof block ? block: block.toString()).replace(c.define || skip,
        function(m, code, assign, value) {
            return 0 === code.indexOf("def.") && (code = code.substring(4)),
            code in def || (":" === assign ? (c.defineParams && value.replace(c.defineParams,
            function(m, param, v) {
                def[code] = {
                    arg: param,
                    text: v
                }
            }), code in def || (def[code] = value)) : new Function("def", "def['" + code + "']=" + value)(def)),
            ""
        }).replace(c.use || skip,
        function(m, code) {
            c.useParams && (code = code.replace(c.useParams,
            function(m, s, d, param) {
                if (def[d] && def[d].arg && param) {
                    var rw = (d + ":" + param).replace(/'|\\/g, "_");
                    return def.__exp = def.__exp || {},
                    def.__exp[rw] = def[d].text.replace(new RegExp("(^|[^\\w$])" + def[d].arg + "([^\\w$])", "g"), "$1" + param + "$2"),
                    s + "def.__exp['" + rw + "']"
                }
            }));
            var v = new Function("def", "return " + code)(def);
            return v ? resolveDefs(c, v, def) : v
        })
    }
    function unescape(code) {
        return code.replace(/\\('|\\)/g, "$1").replace(/[\r\t\n]/g, " ")
    }
    var global, doT = {
        version: "1.0.1",
        templateSettings: {
            evaluate: /\{\{([\s\S]+?(\}?)+)\}\}/g,
            interpolate: /\{\{=([\s\S]+?)\}\}/g,
            encode: /\{\{!([\s\S]+?)\}\}/g,
            use: /\{\{#([\s\S]+?)\}\}/g,
            useParams: /(^|[^\w$])def(?:\.|\[[\'\"])([\w$\.]+)(?:[\'\"]\])?\s*\:\s*([\w$\.]+|\"[^\"]+\"|\'[^\']+\'|\{[^\}]+\})/g,
            define: /\{\{##\s*([\w\.$]+)\s*(\:|=)([\s\S]+?)#\}\}/g,
            defineParams: /^\s*([\w$]+):([\s\S]+)/,
            conditional: /\{\{\?(\?)?\s*([\s\S]*?)\s*\}\}/g,
            iterate: /\{\{~\s*(?:\}\}|([\s\S]+?)\s*\:\s*([\w$]+)\s*(?:\:\s*([\w$]+))?\s*\}\})/g,
            varname: "it",
            strip: !0,
            append: !0,
            selfcontained: !1
        },
        template: void 0,
        compile: void 0
    };
    "undefined" != typeof module && module.exports ? module.exports = doT: "function" == typeof define && define.amd ? define(function() {
        return doT
    }) : (global = function() {
        return this || (0, eval)("this")
    } (), global.doT = doT),
    String.prototype.encodeHTML = encodeHTMLSource();
    var startend = {
        append: {
            start: "'+(",
            end: ")+'",
            endencode: "||'').toString().encodeHTML()+'"
        },
        split: {
            start: "';out+=(",
            end: ");out+='",
            endencode: "||'').toString().encodeHTML();out+='"
        }
    },
    skip = /$^/;
    doT.template = function(tmpl, c, def) {
        c = c || doT.templateSettings;
        var needhtmlencode, indv, cse = c.append ? startend.append: startend.split,
        sid = 0,
        str = c.use || c.define ? resolveDefs(c, tmpl, def || {}) : tmpl;
        str = ("var out='" + (c.strip ? str.replace(/(^|\r|\n)\t* +| +\t*(\r|\n|$)/g, " ").replace(/\r|\n|\t|\/\*[\s\S]*?\*\//g, "") : str).replace(/'|\\/g, "\\$&").replace(c.interpolate || skip,
        function(m, code) {
            return cse.start + unescape(code) + cse.end
        }).replace(c.encode || skip,
        function(m, code) {
            return needhtmlencode = !0,
            cse.start + unescape(code) + cse.endencode
        }).replace(c.conditional || skip,
        function(m, elsecase, code) {
            return elsecase ? code ? "';}else if(" + unescape(code) + "){out+='": "';}else{out+='": code ? "';if(" + unescape(code) + "){out+='": "';}out+='"
        }).replace(c.iterate || skip,
        function(m, iterate, vname, iname) {
            return iterate ? (sid += 1, indv = iname || "i" + sid, iterate = unescape(iterate), "';var arr" + sid + "=" + iterate + ";if(arr" + sid + "){var " + vname + "," + indv + "=-1,l" + sid + "=arr" + sid + ".length-1;while(" + indv + "<l" + sid + "){" + vname + "=arr" + sid + "[" + indv + "+=1];out+='") : "';} } out+='"
        }).replace(c.evaluate || skip,
        function(m, code) {
            return "';" + unescape(code) + "out+='"
        }) + "';return out;").replace(/\n/g, "\\n").replace(/\t/g, "\\t").replace(/\r/g, "\\r").replace(/(\s|;|\}|^|\{)out\+='';/g, "$1").replace(/\+''/g, "").replace(/(\s|;|\}|^|\{)out\+=''\+/g, "$1out+="),
        needhtmlencode && c.selfcontained && (str = "String.prototype.encodeHTML=(" + encodeHTMLSource.toString() + "());" + str);
        try {
            return new Function(c.varname, str)
        } catch(e) {
            throw "undefined" != typeof console && console.log("Could not create a template function: " + str),
            e
        }
    },
    doT.compile = function(tmpl, def) {
        return doT.template(tmpl, null, def)
    }
} (),
function(factory) {
    "function" == typeof define && define.amd ? define(["jquery"], factory) : factory(jQuery)
} (function($) {
    function encode(s) {
        return config.raw ? s: encodeURIComponent(s)
    }
    function decode(s) {
        return config.raw ? s: decodeURIComponent(s)
    }
    function stringifyCookieValue(value) {
        return encode(config.json ? JSON.stringify(value) : String(value))
    }
    function parseCookieValue(s) {
        0 === s.indexOf('"') && (s = s.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, "\\"));
        try {
            return s = decodeURIComponent(s.replace(pluses, " ")),
            config.json ? JSON.parse(s) : s
        } catch(e) {}
    }
    function read(s, converter) {
        var value = config.raw ? s: parseCookieValue(s);
        return $.isFunction(converter) ? converter(value) : value
    }
    var pluses = /\+/g,
    config = $.cookie = function(key, value, options) {
        if (void 0 !== value && !$.isFunction(value)) {
            if (options = $.extend({},
            config.defaults, options), "number" == typeof options.expires) {
                var days = options.expires,
                t = options.expires = new Date;
                t.setTime( + t + 864e5 * days)
            }
            return document.cookie = [encode(key), "=", stringifyCookieValue(value), options.expires ? "; expires=" + options.expires.toUTCString() : "", options.path ? "; path=" + options.path: "", options.domain ? "; domain=" + options.domain: "", options.secure ? "; secure": ""].join("")
        }
        for (var result = key ? void 0 : {},
        cookies = document.cookie ? document.cookie.split("; ") : [], i = 0, l = cookies.length; l > i; i++) {
            var parts = cookies[i].split("="),
            name = decode(parts.shift()),
            cookie = parts.join("=");
            if (key && key === name) {
                result = read(cookie, value);
                break
            }
            key || void 0 === (cookie = read(cookie)) || (result[name] = cookie)
        }
        return result
    };
    config.defaults = {},
    $.removeCookie = function(key, options) {
        return void 0 === $.cookie(key) ? !1 : ($.cookie(key, "", $.extend({},
        options, {
            expires: -1
        })), !$.cookie(key))
    }
});
var itz = itz || {};
itz.util = itz.util || {},
function() {
    $(".user-account-nav-item").click(function() {
        var li = $(this).parent(),
        ul = li.find("ul"),
        height = ul.height();
        ul.data("data-height") || ul.data("data-height", height),
        ul.is(":visible") ? ul.animate({
            height: "0px"
        },
        230,
        function() {
            ul.hide()
        }) : (ul.show(), ul.css({
            height: "0px"
        }), ul.animate({
            height: ul.data("data-height") + "px"
        },
        230)
        //    , li.siblings().find("ul").each( function() {
        //    $(this).animate({
        //        height: "0px"
        //    }).hide();
        //    $(this).css({height:"0px"})
        //})
        )
    }),
    $(".user-info-status span").tip({
        words_per_line: 1e3
    })
} (),
Date.prototype.Format = function(fmt) {
    var o = {
        "M+": this.getMonth() + 1,
        "d+": this.getDate(),
        "h+": this.getHours(),
        "m+": this.getMinutes(),
        "s+": this.getSeconds(),
        "q+": Math.floor((this.getMonth() + 3) / 3),
        S: this.getMilliseconds()
    };
    /(y+)/.test(fmt) && (fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length)));
    for (var k in o) new RegExp("(" + k + ")").test(fmt) && (fmt = fmt.replace(RegExp.$1, 1 == RegExp.$1.length ? o[k] : ("00" + o[k]).substr(("" + o[k]).length)));
    return fmt
},
itz.util.promptA = function(promptId, tmplId, data, dialogOption) {
    var $prompt = $("#" + promptId),
    pagefn = doT.template(document.getElementById(tmplId).text, void 0),
    defaultOpt = {
        width: 460
    };
    dialogOption = dialogOption ? $.extend({},
    defaultOpt, dialogOption) : defaultOpt,
    $prompt.length || ($prompt = $('<div id="' + promptId + '" style="display:none"></div>'), $("body").append($prompt)),
    $prompt.html(pagefn(data)),
    $prompt.dialog({
        dialogClass: "clearPop pop-style-1",
        bgiframe: !0,
        modal: !0,
        resizable: !1,
        closeOnEscape: !1,
        show: {
            effect: "fadeIn",
            duration: 450
        },
        open: function() {},
        width: dialogOption.width,
        close: dialogOption.close
    })
},
itz.util.ajaxPager = function(options) {
    function pageSelectCallback(page_index) {
        return currentAjaxRequset && currentAjaxRequset.abort(),
        currentAjaxRequset = $.ajax({
            url: options.ajaxHostUrl + "?page=" + (page_index + 1) + (pager.tmpl ? "&type=" + pager.tmpl: "") + (pager.tmpl_param ? options.paramsUrl: ""),
            dataType: "html",
            timeout: 1e4,
            beforeSend: function() {
                $container.append($loading)
            },
            success: function(data) {
                $container.html(data)
            },
            error: function(xhr) {
                $loading.remove(),
                (xhr.statusText = "abort") || alert("爱亲，您的网络有问题呦，请刷新页面再试一下~")
            }
        }),
        !1
    }
    var currentAjaxRequset, pager = options.pager,
    pageInfo = pager.pageInfo,
    num_entries = pageInfo.nn ? pageInfo.nn: 1,
    $container = $("#" + options.container),
    $p = $("#" + options.pagination),
    $loading = $('<div style="height:100%;width:100%;position:absolute;left:0;top:0;text-align:center"><img style="top:50%;position:absolute;margin-top:-16px" src="' + options.loadImgPath + '"/></div>');
    return num_entries > 1 && $p.fadeIn(),
    $p.pagination(num_entries, {
        callback: pageSelectCallback,
        load_first_page: !1,
        items_per_page: 1,
        next_text: "&gt;",
        prev_text: "&lt;"
    }),
    {
        pageLoad: pageSelectCallback
    }
},
itz.util.getBorrowTip = function($tips, type, tipAjaxUrl) {
    tipAjaxUrl || (tipAjaxUrl = "/newuser/ajax/GetBorrowInfo"),
    type || (type = "borrow_detail"),
    $tips.poshytip({
        alignY: "bottom",
        showTimeout: 100,
        liveEvents: !0,
        content: function(updateCallback) {
            var $this = $(this),
            id = $this.attr("_id"),
            cid = $this.attr("_cid");
            return $.ajax({
                url: tipAjaxUrl + "?id=" + id + "&type=" + type + "&cid=" + cid,
                dataType: "html",
                success: function(data) {
                    updateCallback(data)
                }
            }),
            "拼命加载中..."
        }
    })
},
itz.util.getFee = function(money, fee) {
    return money && fee ? (money * fee).toFixed(2) : "缺少金额或费率"
},
//弹框，config为json格式，前面的message,role等不加引号{message:'弹出消息'}
itz.util.toast = function(config) {
    this.context = config.context == null ? $("body") : config.context;
    this.message = config.message;
    this.time = config.time == null ? 3000 : config.time;
    this.left = config.left;
    this.top = config.top;
    this.role = config.role == null ? "danger" : config.role; //
    var msgEntity;
    $("#toastMessage").remove();
    var msgDIV = new Array();
    msgDIV.push('<div id="toastMessage" class="toast-' + this.role + '">');
    msgDIV.push("<span>" + this.message + "</span>");
    msgDIV.push("</div>");
    msgEntity = $(msgDIV.join("")).appendTo(this.context);
    var halfTop = $(document).scrollTop() + ($(window).height() - msgEntity.height()) * 0.282;
    var left = this.left == null ? (this.context.width() - msgEntity.find("span").width() - 40) / 2 : this.left;
    var top = this.top == null ? halfTop : this.top;
    msgEntity.css({
        position: "absolute",
        top: top,
        "z-index": "990",
        left: left,
        color: "white",
        "font-size": "18px",
        padding: "10px",
        margin: "10px",
        borderRadius: "5px"
    });
    msgEntity.hide();
    msgEntity.fadeIn(this.time / 2);
    msgEntity.fadeOut(this.time / 2)
},
$.fn.itzCutDownBtn = function($phone, url, time) {
    function checkAlert($t, errorText) {
        $t.data("voice") ? ($t.val(errorText), setTimeout(function() {
            $t.val("语音验证码").removeAttr("disabled").removeClass("voicechdis")
        },
        2e3)) : ($t.attr("disabled", "true").removeClass("btn-style-1").addClass("btn-style-2").val(errorText), setTimeout(function() {
            $t.removeAttr("disabled").removeClass("btn-style-2").addClass("btn-style-1").val("继续获取短信验证码")
        },
        2e3))
    }
    var iTime, origTime, st, iUrl = url || "/newuser/ajax/PhoneCheck",
    $that = $(this),
    yuyin_wrapper = $that.closest("li").siblings(".yuyin-channel"),
    vnum = $(".js_vnum");
    iTime = origTime = time || 60;
    var yflag = yuyin_wrapper.length > 0 ? !0 : !1;
    if (this.click(function() {
        yflag && yuyin_wrapper.hide();
        var $t = $(this),
        num = void 0 === $phone ? 0 : $phone.val();
        return num || void 0 === $phone ? /^1\d{10}$/.test(num) || void 0 === $phone ? ($t.attr("disabled", "true").removeClass("btn-style-1").addClass("btn-style-2").val("发送中……"), $.ajax({
            type: "POST",
            dataType: "JSON",
            url: iUrl,
            data: "sms=" + num + "&num=" + (vnum.length > 0 ? vnum.val() : 0),
            error: function() {
                checkAlert($t, "网络错误~"),
                yflag && (yuyin_wrapper.show(), yuyin.removeAttr("disabled").removeClass("voicechdis"))
            },
            success: function(data) {
                0 == data.code ? ($t.val(iTime + "秒后可重新获取"), yflag && (yuyin.attr("disabled", !0).addClass("voicechdis"), yuyin_wrapper.show(), i0.show(), i1.hide(), i3.hide(), yct.show().html("在 " + iTime + "秒 后")), st = setInterval(function() {
                    return 1 === iTime ? (clearInterval(st), $t.removeAttr("disabled").removeClass("btn-style-2").addClass("btn-style-1"), $t.val("重新获取短信验证码"), yflag && yct.hide(), yflag && yuyin.removeAttr("disabled").removeClass("voicechdis"), iTime = origTime, void 0) : ($t.val(--iTime + "秒后可重新获取"), yflag && yct.html("在 " + iTime + "秒 后"), void 0)
                },
                1e3)) : 5 == data.code && void 0 !== $phone ? checkAlert($t, "该手机号已被认证过~") : checkAlert($t, data.info)
            }
        }), void 0) : (checkAlert($t, "手机格式不正确~"), void 0) : (checkAlert($t, "请填写手机号~"), void 0)
    }), yflag) {
        var yuyin = yuyin_wrapper.find("input"),
        i0 = yuyin_wrapper.find(".js_info0"),
        i1 = yuyin_wrapper.find(".js_info1"),
        i2 = yuyin_wrapper.find(".js_info2"),
        i3 = yuyin_wrapper.find(".js_info3"),
        yct = yuyin_wrapper.find(".yyct"),
        sms = yuyin_wrapper.siblings().find(".sms-channel");
        yuyin.click(function() {
            var $t = $(this),
            num = void 0 === $phone ? 0 : $phone.val();
            if ((num || void 0 === $phone) && (/^1\d{10}$/.test(num) || void 0 === $phone)) {
                $t.attr("disabled", "true").addClass("voicechdis"),
                sms.attr("disabled", !0).removeClass("btn-style-1").addClass("btn-style-2");
                var da = yuyin.data("type") ? {
                    Voice: "true"
                }: {
                    sms: num,
                    Voice: "true"
                };
                da.num = vnum.length > 0 ? vnum.val() : 0,
                $.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: iUrl,
                    data: da,
                    error: function() {
                        checkAlert($t, "网络错误~")
                    },
                    success: function(data) {
                        0 == data.code ? (i0.hide(), i1.show(), i2.show(), i3.show(), yct.show().html("在 " + iTime + "秒 后"), sms.val(iTime + "秒后获取短信验证码"), st = setInterval(function() {
                            return 1 === iTime ? (clearInterval(st), $t.removeAttr("disabled").removeClass("voicechdis"), sms.removeAttr("disabled").removeClass("btn-style-2").addClass("btn-style-1"), yct.hide(), iTime = origTime, sms.val("获取短信验证码"), void 0) : (yct.html("在 " + --iTime + "秒 后"), sms.val(iTime + "秒后获取短信验证码"), void 0)
                        },
                        1e3)) : 5 == data.code && void 0 !== $phone ? (checkAlert($t, "该手机号已被认证过~"), $t.removeAttr("disabled").removeClass("voicechdis"), sms.removeAttr("disabled").removeClass("btn-style-2").addClass("btn-style-1")) : (checkAlert($t, data.info), $t.removeAttr("disabled").removeClass("voicechdis"), sms.removeAttr("disabled").removeClass("btn-style-2").addClass("btn-style-1"))
                    }
                })
            }
        })
    }
    return {
        reset: function() {
            clearInterval(st),
            iTime = time || 60,
            $that.removeAttr("disabled").removeClass("btn-style-2").addClass("btn-style-1").val("获取验证码"),
            yflag && yuyin_wrapper.hide()
        }
    }
},
itz.util.parseArrayToJson = function(arr) {
    var tp = Object.prototype.toString;
    if ("Array" !== tp.call(arr).slice(8, -1)) return {};
    if (0 == arr.length) return {};
    var rst = {},
    db = {},
    ct = 0;
    return $.each(arr,
    function(k, v) {
        v.length >= 2 && (rst[v[0]] = v[1].toString(), db[v[1]] = v[0].toString(), ct++)
    }),
    {
        result: rst,
        converse: db,
        count: ct
    }
};