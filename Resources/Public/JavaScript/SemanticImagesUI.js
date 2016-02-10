/**
 * @fileOverviewist View
 * @name List.js
 * @author Johannes Goslar
 */
define('TYPO3/CMS/SemanticImages/SemanticImagesUI', [
    'TYPO3/CMS/SemanticImages/react',
], function (
    React
) {
    return React.createClass({
        getInitialState: function() {
            return {
                expanded: null,
                busy: false
            };
        },
        render: function() {
            return React.createElement("div", {className: "semanticimages"},
                React.createElement("p", {}, "hello")
               );
        }
    });
});
