/**
 * Prototype patch to fix getOffsetParent() issue in IE
 */

function isBody(element) {
    return element.nodeName.toUpperCase() === 'BODY';
}

function isHtml(element) {
    return element.nodeName.toUpperCase() === 'HTML';
}

function isDocument(element) {
    return element.nodeType === Node.DOCUMENT_NODE;
}

function isDetached(element) {
    return element !== document.body && !Element.descendantOf(element, document.body);
}

var getOffsetParent = function (element) {
    element = $(element);
    if (isDocument(element) || isDetached(element) || isBody(element) || isHtml(element))
        return $(document.body);
    var isInline = (Element.getStyle(element, 'display') === 'inline');
    if (!isInline && element.offsetParent && Element.visible(element)) return $(element.offsetParent);
    while ((element = element.parentNode) && element !== document.body) {
        if (Element.getStyle(element, 'position') !== 'static') {
            return isHtml(element) ? $(document.body) : $(element);
        }
    }
    return $(document.body);
};

Element.addMethods({
    getOffsetParent: getOffsetParent
});