/**
 * Returns first element, of node parents, that matches callback returned true for.
 * @param  {DOMNode}   node
 * @param  {Function} callback
 * @return {DOMNode}
 */
'use strict';

function closest(node, callback) {
	var nextParentNode = node;

	while (!callback(nextParentNode)){
		nextParentNode = nextParentNode.parentNode;
		if (nextParentNode === null) {
			return false;
		}
	}
	
	return nextParentNode;


}

closest(document.body, function(node) { 
    return node.nodeName === 'BODY'; 
});