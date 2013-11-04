/*
	1. Для работы с jquery использовал $j();
	2. Работает для всех подгруженных табиков;
	3. Добавляет кастомные стили на страницу;
	4. Правильно отработает только при первом вызове функции.
*/
$j(function() {
	"use strict";

	// make custom styles
	$j('<style type="text/css">'
		+ '._betInfo { \n'
		+ '	color: rgb(25, 124, 175);\n'
		+ '	font-size: 12px;\n'
		+ '	font-weight: normal;\n'
		+ '	height: 25px;\n'
		+ '	line-height: 25px;\n'
		+ '	text-align: left;\n'
		+ '	padding-left: 8px;\n'
		+ '}\n'
		+ '._betType { \n'
		+ '	color: rgb(25, 124, 175);\n'
		+ '	font-weight: bold;\n'
		+ '	height: 25px;\n'
		+ '	line-height: 25px;\n'
		+ '	text-align: center;\n'
		+ '}\n'
		+'</style>\n').appendTo("head");

	// get all tabs for a work
	var tabs = $j('div.tabCnt');

	tabs.each(function() {
		var $this = $j(this);

		// get all tables for a work
		var table = $this.find('div:eq(0) > div table:not(:first)');

		// hide (1 х 2) in a table row
		$this.find('div:eq(0) > div > table th[id]:not(:first) a').hide();

		table.each(function() {
			var $this = $j(this);
			var tableRows = $this.children().eq(2);
			var rows = tableRows.children();

			// correcting column width
			$j('.footballcard colgroup').each(function() {
				$this = $j(this);
				$this.children().eq(3).css("width", "25px");
				$this.children().eq(5).css("width", "135px");
			});

			for (i = 0; i < rows.length; i++) {
				// make buttons at a column
				rows.eq(i).children().eq(4).find('div').appendTo(rows.eq(i).children().eq(3));
				rows.eq(i).children().eq(5).find('div').appendTo(rows.eq(i).children().eq(3));

				// correcting vertical align in a cells
				rows.find('.time, .tv, .stats, .bets').css("vertical-align", "top");

				// get bet coefficient
				var betH = rows.eq(i).find('span.prc').eq(0).text();
				var betD = rows.eq(i).find('span.prc').eq(1).text();
				var betA = rows.eq(i).find('span.prc').eq(2).text();

				// add text with a hint
				rows.eq(i).find('.fbhlt').eq(1).append('<div class="_betInfo">gioca €20 vinci €' + parseInt((parseFloat(betH) * 20), 10) + '</div>');
				rows.eq(i).find('.fbhlt').eq(1).append('<div class="_betInfo">gioca €20 vinci €' + parseInt((parseFloat(betD) * 20), 10) + '</div>');
				rows.eq(i).find('.fbhlt').eq(1).append('<div class="_betInfo">gioca €20 vinci €' + parseInt((parseFloat(betA) * 20), 10) + '</div>');

				// remove unusable column
				rows.eq(i).find('.fbhlt').eq(2).remove();
			}
		});
	});

	// new table for a (1 х 2)
	var htmlTable = document.createElement('table');
	htmlTable.style.border = "none";
	htmlTable.style.width = "25px";
	htmlTable.style.float = "right";
	for (var i = 0; i < 3; i++) {
		if (i === 0) {
			var tr = document.createElement('tr');
			var th = document.createElement('th');
			$j(th).addClass("_betType");
			th.innerHTML = "1";
			tr.appendChild(th);
			htmlTable.appendChild(tr);
		} else if (i === 1) {
			var tr = document.createElement('tr');
			var th = document.createElement('th');
			$j(th).addClass("_betType");
			th.innerHTML = "x";
			tr.appendChild(th);
			htmlTable.appendChild(tr);
		} else if (i === 2) {
			var tr = document.createElement('tr');
			var th = document.createElement('th');
			$j(th).addClass("_betType");
			th.innerHTML = "2";
			tr.appendChild(th);
			htmlTable.appendChild(tr);
		}
	}

	// append table
	var tvBlock = $j('.tv.border');
	var td = document.createElement('td');
	$j(td).addClass("time border").append(htmlTable);
	tvBlock.after(td);

});