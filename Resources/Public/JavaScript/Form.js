/**
 * @fileOverview Form JavaScript
 * @name Api.js
 * @author Johannes Goslar
 */
define('TYPO3/CMS/SemanticImages/Form', [
    'jquery',
    'TYPO3/CMS/SemanticImages/react',
    'TYPO3/CMS/SemanticImages/UI'
], function (
    $,
    React,
    UI
) {
    if (HTMLCollection.prototype[Symbol.iterator] ==  null)
        HTMLCollection.prototype[Symbol.iterator] = Array.prototype[Symbol.iterator];

    $(document).ready(function(){
        for (var element of document.getElementsByClassName('semanticimage'))
            React.render(React.createElement(UI, {uid: element.dataset.uid, name: element.dataset.name}), element);
    });
    return null;
});
