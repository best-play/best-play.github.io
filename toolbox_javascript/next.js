var next = (function () {
	var TEXT_NODE_TYPE = 3;
	var COMMENT_NODE_TYPE = 8;
 
	return function (node) {
		var nextSiblingNode = node.nextSibling;
 
		while( nextSiblingNode !== null ) {
			if ((nextSiblingNode.nodeType === TEXT_NODE_TYPE) || (nextSiblingNode.nodeType === COMMENT_NODE_TYPE)) {
				nextSiblingNode = nextSiblingNode.nextSibling;
			} else {
				return nextSiblingNode;
			}
		}

		return node;
	}
}());