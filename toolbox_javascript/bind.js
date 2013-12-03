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