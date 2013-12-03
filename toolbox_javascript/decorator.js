 function decorate(original, before, after, context) {
    return function() {
      context = context || this;
      var res;
      if (typeof before === 'function') {
        before.apply(context, arguments);
      }
      res = original.apply(context, arguments);
      if (typeof after === 'function') {
        after.apply(context, arguments);
      }
      return res;
    };
  }