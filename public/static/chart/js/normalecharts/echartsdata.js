function request() {
	if (_dates.length <= 0) {
		$.ajax({
			url: __quoteapi,
			dataType: "json",
			data: "method=kmap&symbol=" + __symbol + "&period=" + __period + "&lasttick=0",
			success: function (data) {
				if (data.Code >= 0) {
					fixdata(data.Obj);
					render();
				}
			}
		});
	}
	else {
		$.ajax({
			url: __quoteapi,
			dataType: "json",
			data: "method=kmap&symbol=" + __symbol + "&period=" + __period + "&lasttick=" + _lstkticks,
			success: function (data) {
				if (data.Code >= 0) {
					fixdata(data.Obj);
					render();
				}
			}
		});
	}
}

function getDateStr(tick) {
	var ordate = new Date(tick * 1000);
	if (__period.indexOf("T") == 0 || __period.indexOf("1M") == 0) { return ordate.Format("MM-dd-hh:mm"); }
	else if (__period.indexOf("5M") == 0) { return (ordate.Format("MM-dd-hh:") + PrefixInteger((Math.floor(ordate.getMinutes() * 1.0 / 5.0) * 5), 2)); }
	else if (__period.indexOf("10M") == 0) { return (ordate.Format("MM-dd-hh:") + PrefixInteger((Math.floor(ordate.getMinutes() * 1.0 / 10.0) * 10), 2)); }
	else if (__period.indexOf("15M") == 0) { return (ordate.Format("MM-dd-hh:") + PrefixInteger((Math.floor(ordate.getMinutes() * 1.0 / 15.0) * 15), 2)); }
	else if (__period.indexOf("30M") == 0) { return (ordate.Format("MM-dd-hh:") + PrefixInteger((Math.floor(ordate.getMinutes() * 1.0 / 30.0) * 30), 2)); }
	else if (__period.indexOf("1H") == 0 || __period.indexOf("2H") == 0 || __period.indexOf("4H") == 0) { return ordate.Format("MM-dd-hh:00"); }
	else return (new Date(tick * 1000)).Format("yyyy-MM-dd");
}

function PrefixInteger(num, n) {
	return (Array(n).join(0) + num).slice(-n);
}
function fixdata(datas) {

	if (datas.length <= 0) {
		return;
	}

	if (datas.length == 1) {
		var dstr = getDateStr(datas[0].Tick);

		if (_dates.length > 0 && _dates[_dates.length - 1] == dstr) {
			if (datas[0].O <= 0) { datas[0].O = _data[_dates.length - 1][0]; }
			if (datas[0].L <= 0) { datas[0].L = _data[_dates.length - 1][2]; }
			if (datas[0].H <= 0) { datas[0].H = _data[_dates.length - 1][3]; }
			if (datas[0].V <= 0) { datas[0].V = _data[_dates.length - 1][4]; }
		}
		else { return; }
	}

	if (_dates.length > 0) {
		//最小
		var dstr = getDateStr(datas[(datas.length - 1)].Tick);

		while (_dates[_dates.length - 1] >= dstr) {
			_dates.splice(_dates.length - 1);
			_data.splice(_data.length - 1);
			_datal.splice(_datal.length - 1);
			_volumes.splice(_volumes.length - 1);
		}
	}

	for (var i = (datas.length - 1); i >= 0; i--) {
		var d = datas[i];
		if (d.C <= 0)
			continue;

		var dstr = getDateStr(d.Tick);

		//倒数第二个做最后lst_k-tick
		if (i == 1) { _lstkticks = d.Tick; }

		/*不要用k线更新实时数据*/
		if (i <= 1 && setP != undefined) {
			setP(d.Tick, d.C, d.C, d.C, 0, 0, false);
		}
		_dates.push(dstr);
		_data.push([d.O, d.C, d.L, d.H, d.V]);
		_datal.push(d.C);
		_volumes.push(d.V);
	}
}

function render() {
	var dataMA5 = calculateMA(5, _data);
	var dataMA20 = calculateMA(20, _data);
	var dataMA30 = calculateMA(30, _data);

	//记录之前的状态
	if (_myChart && _myChart.getOption()) {
		_dzst = _myChart.getOption().dataZoom[0].start;
		_dzed = _myChart.getOption().dataZoom[0].end;
	}
	else {
		if (_dates.length < _stnum)
			_dzst = 0;
		else {
			_dzst = (_dates.length - _stnum) * 100 / _dates.length;
		}
		_dzed = 100;
	}

	option = createOption(__symbol, _mtype, _sets, __period, _dzst, _dzed, _dates, _data, _datal, _volumes, dataMA5, dataMA20, dataMA30);

	if (option && typeof option === "object") {
		_myChart.setOption(option, true);
	}

}

