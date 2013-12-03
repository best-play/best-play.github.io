function getXmlHttp() { // cross-browser XHR
		try {
			return new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try {
				return new ActiveXObject("Microsoft.XMLHTTP");
			} catch (ee) {}
		}
		if (typeof XMLHttpRequest != 'undefined') {
			return new XMLHttpRequest();
		}
	}
