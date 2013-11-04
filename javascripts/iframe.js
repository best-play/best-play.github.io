/*
Скрипт для редизайна сайта http://habrahabr.ru с ипользование iframe.
	1. Сохраняется последний активный таб в куках
	2. Есть попытка убрать "моргания" контента
	3. Проблема с размером iframe в IE
*/
$(function() {
	'use strict';

	var COOKIE_LAST_USED_TAB = 'lastUsedTab'; // имя куки
	var COOKIE_EXPIRES = new Date( new Date().getTime() + 60 * 60 * 24 * 1000 ); // кука на сутки
	var COOKIE_PATH = "/"; // устанавливаем на все страницы сайта

	var ADDITIONAL_HEIGHT = 50; // добавляем высоту, что бы убрать прокрутку

	var $layout = $('#layout');
	var tabsA = $('.main_menu a');
	var $content = $('#layout > div:not(:first)');

	function setCookie(name, value, options) {
		options = options || {};

		var expires = options.expires;

		if (typeof expires == "number" && expires) {
			var d = new Date();
			d.setTime(d.getTime() + expires * 1000);
			expires = options.expires = d;
		}
		if (expires && expires.toUTCString) {
			options.expires = expires.toUTCString();
		}

		value = encodeURIComponent(value);

		var updatedCookie = name + "=" + value;

		for (var propName in options) {
			updatedCookie += "; " + propName;
			var propValue = options[propName];
			if (propValue !== true) {
				updatedCookie += "=" + propValue;
			}
		}

		document.cookie = updatedCookie;
	}

	function getCookie(name) {
		var matches = document.cookie.match(new RegExp(
			"(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
		));
		return matches ? decodeURIComponent(matches[1]) : undefined;
	}

	function changeIframeContent() {
		var $this = $(this);
		var $iframe = $this.contents();
		var scrollHeight;

		$this.css('visibility', 'visible');
		// скрываем пока страница не загрузится (убираем "мигание")
		$(this.contentWindow).on('unload', function() {
			$this.hide();
			$this.load(function() {
				$this.show();
			});
		});

		// скрываем не нужное в iframe
		$iframe.find('#header').hide();
		$iframe.find('#footer').hide();
		$iframe.find('.footer_logos').hide();

		// регулируем ширину контента
		$iframe.find('#layout').css({
			'width': '1200px',
			'padding': '0'
		})
		$iframe.find('.content_left').css({
			'width': '880px'
		})

		scrollHeight = ($iframe.find('#layout').height() + ADDITIONAL_HEIGHT) + 'px';
		$this.css('height', scrollHeight);
	}

	function createIframes() {
		var iframe = $(document.createElement('iframe'));
		iframe.css({
			'display': 'none',
			'width': '1200px',
			'scrolling': 'no',
			'visibility': 'hidden'
		});
		iframe.attr('src', this.href);

		$('#layout').append(iframe);
		iframe.on('load', changeIframeContent);
	}

	function toogleTabs(event) {
		event.preventDefault();

		// опции для создания куки
		var cookieOpt = {
			"expires": COOKIE_EXPIRES,
			"path": COOKIE_PATH
		};

		var $this = $(this);
		var targetIfr = $layout.find('iframe[src="' + this.href + '"]');
		var visibleIfr = $layout.find('iframe:visible');
		var checkClass = $this.hasClass('active');
		var activeClass = $('.main_menu a[class="active"]');

		// записываем в куки информацию о последнем табике
		setCookie(COOKIE_LAST_USED_TAB, this.href, cookieOpt);

		// если жмем на не активный табик, то открываем его
		// а если табик уже активный, то перезагружаем iframe
		if (!checkClass) {
			// работаем с табиками
			$this.addClass('active');
			activeClass.removeClass();

			// работаем с iframe
			visibleIfr.hide();
			targetIfr.show();
		} else {
			targetIfr.attr("src", this.href);
		}
	}

	// функция для загрузки информации из куки про последний таб
	function openLastTab() {
		var cookie = getCookie(COOKIE_LAST_USED_TAB);
		var targetIfr = $layout.find('iframe[src="' + cookie + '"]');
		var targetTab = $layout.find('a[href="' + cookie + '"]');
		var activeClass = $('.main_menu a[class="active"]');

		// если есть кука, то открываем таб из кук
		// в противном случае показываем 1 таб
		if (cookie) {
			activeClass.removeClass();
			targetTab.addClass('active');
			targetIfr.show();
		} else {
			$layout.find('iframe').eq(1).show();
		}
	}

	// скрываем контент
	$content.hide();

	// делаем табики
	tabsA.on('click', toogleTabs);

	// добавляем iframe
	tabsA.each(createIframes);

	// запускаем обработку такибок
	openLastTab();
});