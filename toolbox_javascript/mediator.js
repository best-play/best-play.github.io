'use strict';
 
var mediator = (function () {
    var subscribtions = {};

    return {
        trigger: function (eventName, data) {
            if (subscribtions.hasOwnProperty(eventName)) {
                for (var i = 0; i < subscribtions[eventName].length; i += 1) {
                    subscribtions[eventName][i].call(window, data);
                }
            }
        },
        subscribe: function (eventName, handler) {
            if (subscribtions.hasOwnProperty(eventName)) {
                subscribtions[eventName].push(handler);
            } else {
                subscribtions[eventName] = [handler];
            }
        },
        unsubscribe: function (eventName, handlerReference) {
            if (subscribtions.hasOwnProperty(eventName)) { // проверяем есть ли событие, если нет - то ничего не делаем
                if (handlerReference === undefined) { // если нет второго аргумента, то
                    subscribtions[eventName].length = 0; // удаляем всех подписчиков
                } else {
                    for (i = 0; i < subscribtions[eventName].length; i += 1) { // пробегаемся по всему массиву
                        if (subscribtions[eventName][i] === handlerReference) { // если находим handlerReference, то
                            subscribtions[eventName].splice(i, 1);  //удаляем оброботчик
                            i -= 1; // уменьшаем индекс т.к. splice сдвигает индексы
                        }
                    }
                }
            }
        }
    };

}());
 
 
 
/*
Объект, который позволяет подписываться на события (событие - строк имени, действие - функция-callback), и инициировать события (выполнять всех подписчиков на событие, передавая им дополнительную информацию о событии)
*/
 
// Пример применения
function myFirstEvent(){
    console.log('I am born.')
}


mediator.trigger('complete'); // nothing happens

mediator.subscribe('complete', function (completionTime) {
    console.log('Complete subscriber. Fired at ' + completionTime);
});

mediator.subscribe('complete', myFirstEvent);
 
mediator.subscribe('complete', function () {
     console.log('Another complete subscriber');
});

mediator.subscribe('complete', myFirstEvent);
mediator.subscribe('complete', myFirstEvent);


mediator.unsubscribe('complete', myFirstEvent);
mediator.unsubscribe('noncomplete', myFirstEvent); // нет такого события


setTimeout(function () {
    mediator.trigger('complete', new Date()); // both subscribers fired
}, 3000);