/**
 * returns true, in case node has all classes from className
 * @param  {DOMNode}  node
 * @param  {String|Array} className
 * @return {Boolean}
 */
'use strict';

function isArray (obj) {
    return Object.prototype.toString.call(obj) === '[object Array]';
}

function hasClass(node, className) {
	var classes, count = 0;

	if (node.className) {
		classes = node.className.split(' '); // заносим все классы в массив
	} else {
		classes = []; // если нет классов, то оставляем пустой массив
	}

	if (isArray(className)) { // если передали массив
		for(var i = 0; i < classes.length; i++) {
			for (var j = 0; j < className.length; j++) {
	   			if (classes[i] === className[j]) { // то проверяем все элементы массива
	   				count++;
	   			}
	   		}
  		}

  		if (count === className.length){ // если все элементы есть
  			return true;	// то возвращаем true
  		} else {
  			return false; // если чего то нет, то false
  		}

	} else {
		for(var i = 0; i < classes.length; i++) { // если передали сторку
	   		if (classes[i] === className) {
	   			return true;
	   		}
  		}
  		return false;
	}
}