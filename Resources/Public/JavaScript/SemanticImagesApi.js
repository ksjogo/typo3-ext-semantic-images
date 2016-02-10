/**
 * @fileOverview Main
 * @name Main.js
 * @author Johannes Goslar
 */
define('TYPO3/CMS/SemanticImages/SemanticImagesApi', [
    'TYPO3/CMS/SemanticImages/poly',
    'TYPO3/CMS/SemanticImages/react',
    'jquery',
    'TYPO3/CMS/SemanticImages/SemanticImagesUI'
], function (
    Poly,
    React,
    $,
    SemanticImagesUI
) {
    return {
        show: function(mountpoint) {
            React.render(React.createElement(SemanticImagesUI, {}), mountpoint);
        }
    };
});
