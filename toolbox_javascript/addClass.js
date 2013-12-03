/**
 * Adds classes to node element. Does not add class, if it's already presents.
 * @param {DOMNode} node     
 * @param {String} className 
 */
'use strict';

function addClass(node, className) {
	var classes;

	if (node.className) {
		classes = node.className.split(' '); // заносим все классы в массив
	} else {
		classes = []; // если нет классов, то оставляем пустой массив
	}

	for(var i = 0; i < classes.length; i++) {
   		if (classes[i] === className) {
   			return; // прерываем работу т.к. класс уже есть
   		}
  	}
 
 	if (classes.length === 0){ // если классов нет
 		node.className += className; // то добавляем наш класс
 	} else {
 		node.className += ' ' + className; // а если есть, то пробел + наш класс
 	}

  	return;
}