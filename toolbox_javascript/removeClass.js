/**
 * Removes all classes, that match className (which can be either string or array)
 * @param  {DOMNode} node      
 * @param  {String|Array} className
 */
'use strict';

function isArray (obj) {
    return Object.prototype.toString.call(obj) === '[object Array]';
}

function removeClass(node, className) {
	var classes;

	if (node.className) {
		classes = node.className.split(' '); // заносим все классы в массив
	} else {
		classes = []; // если нет классов, то оставляем пустой массив
	}

	if (isArray(className)) { // если передали массив
		for(var i = 0; i < classes.length; i++) {
			for(var j = 0; j < className.length; j++) {
				if (classes[i] == className[j]) {
   					classes.splice(i, 1);	// вырезаем класс
   					i--;
   					j--;
   				}
			}
		}
		newClasses = classes.join(' '); // собираем все в одну строку
  		node.className = newClasses; // меняем класс на странице

	} else { // если передали строку
		for(var i = 0; i < classes.length; i++) {
   			if (classes[i] == className) {
   				classes.splice(i, 1);
   				i--;
   			}
  		}
  		newClasses = classes.join(' ');
  		node.className = newClasses;
	}
}