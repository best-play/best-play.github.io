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
function hasClass(node, className) {
  function isArray (obj) {
    return Object.prototype.toString.call(obj) === '[object Array]';
  }

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
        return true;  // то возвращаем true
      } else {
        return false; // если чего то нет, то false
      }

  } else {
    for(i = 0; i < classes.length; i++) { // если передали сторку
        if (classes[i] === className) {
          return true;
        }
      }
      return false;
  }
}
function removeClass(node, className) {
  function isArray (obj) {
    return Object.prototype.toString.call(obj) === '[object Array]';
  }

  var classes, newClasses;

  if (node.className) {
    classes = node.className.split(' '); // заносим все классы в массив
  } else {
    classes = []; // если нет классов, то оставляем пустой массив
  }

  if (isArray(className)) { // если передали массив
    for(var i = 0; i < classes.length; i++) {
      for(var j = 0; j < className.length; j++) {
        if (classes[i] === className[j]) {
            classes.splice(i, 1); // вырезаем класс
            i--;
            j--;
          }
      }
    }
    newClasses = classes.join(' '); // собираем все в одну строку
      node.className = newClasses; // меняем класс на странице

  } else { // если передали строку
    for(i = 0; i < classes.length; i++) {
        if (classes[i] === className) {
          classes.splice(i, 1);
          i--;
        }
      }
      newClasses = classes.join(' ');
      node.className = newClasses;
  }
}
function bind(domNode, eventName, handler) {
  var handlerWrapper = function(event) {
    event = event || window.event;
    if (!event.target && event.srcElement) {
      event.target = event.srcElement;
    }
    return handler.call(domNode, event);
  };

  if (domNode.addEventListener) {
    domNode.addEventListener(eventName, handlerWrapper, false);
  } else if (domNode.attachEvent) {
    domNode.attachEvent('on' + eventName, handlerWrapper);
  }
  return handlerWrapper;
}


/*~~~~~~~~~~~~~~ Gallery prototype ~~~~~~~~~~~~~~~~~~~*/
function GalleryConstructor(selector){
  var self = this;
  var LEFT_ARROW = 39,
      RIGHT_ARROW = 37;
  this.wrapper = document.querySelector(selector);
  this.largeImg = this.wrapper.querySelector('.largeImg');
  this.previews = this.wrapper.querySelectorAll('.thumbs img');
  this.curentIndex = null;
  this.prevIndex = null;
  this.rotateTimer = null;

  this.show(0); // по-умолчанию показываем 1 картинку
  bind(window, 'load', this.startAutoScrolling());

  function keyDown(){
    if (event.keyCode === LEFT_ARROW){
      clearInterval(self.rotateTimer);
      self.next();
    }
    if (event.keyCode === RIGHT_ARROW) {
      clearInterval(self.rotateTimer);
      self.prev();
    }
  }
  bind(document.documentElement, 'keydown', keyDown);
}
GalleryConstructor.prototype.next = function(){
  if (this.curentIndex >= (this.previews.length - 1)){
    this.show(0);
  } else {
    this.show(this.curentIndex + 1);
  }
};
GalleryConstructor.prototype.prev = function(){
  if (this.curentIndex === 0){
    this.show(this.previews.length - 1);
  } else {
    this.show(this.curentIndex - 1);
  }
};
GalleryConstructor.prototype.show = function(imgIndex) {
  var previewNode = this.previews[imgIndex];
  var parentPreviewNode = previewNode.parentNode;

  this.largeImg.src = previewNode.getAttribute('data-largeImg');
  this.curentIndex = imgIndex;

  if (!hasClass(parentPreviewNode, 'current')){
    addClass(previewNode.parentNode, 'current');
    removeClass(this.previews[this.prevIndex].parentNode, 'current');
  }

  this.prevIndex = this.curentIndex;
};
GalleryConstructor.prototype.startAutoScrolling = function() {
  var self = this;
  this.rotateTimer = setInterval(function(){
    self.next();
  }, 5000);
};
GalleryConstructor.prototype.stopAutoScrolling = function() {
  clearInterval(this.rotateTimer);
};
var gal = new GalleryConstructor('.gallery');
/*~~~~~~~~~~~~~~ ~~~~~~~~~~~~~~~~ ~~~~~~~~~~~~~~~~~~~*/

/* конфигурация */
var IMG_WIDTH = 152; // ширина изображения
var IMG_NUM = 5; // количество изображений
var position = 0; // текущий сдвиг влево


var imgWrapper = document.querySelector('.thumbs');
var previews = imgWrapper.querySelectorAll('.thumbs img');
var ul = document.getElementsByClassName('thumbs')[0];
var prevBtn = document.getElementsByClassName('left-arrow')[0];
var nextBtn = document.getElementsByClassName('right-arrow')[0];

function clickImg() {
  var count;
  for(var i = 0; i < previews.length; i++){
    if (event.target === previews[i]){
      count = i;
    }
  }

  gal.show(count);
  gal.stopAutoScrolling();
}
bind(imgWrapper, 'click', clickImg); // событие клика на "превьюшку"



function prevButton() {
  position = Math.min(position + IMG_WIDTH * IMG_NUM, 0); // вычисление сдвига назад
  ul.style.marginLeft = position + 'px';
}
bind(prevBtn, 'click', prevButton); // событие клика на кнопку "назад"

function nextButton() {
  position = Math.max(position - IMG_WIDTH * IMG_NUM, -IMG_WIDTH * (previews.length - IMG_NUM));  // вычисление сдвига вперед
  ul.style.marginLeft = position + 'px';
}
bind(nextBtn, 'click', nextButton); // событие клика на кнопку "вперед"