/**
 * @fileOverview Form JavaScript
 * @name Api.js
 * @author Johannes Goslar
 */
define('TYPO3/CMS/SemanticImages/Quality', [
    'jquery',
    'TYPO3/CMS/SemanticImages/react',
    'TYPO3/CMS/SemanticImages/Remote',
], function (
    $,
    React,
    Remote
) {
    return React.createClass({
        render: function(){
            console.log(this.props);
            return React.createElement('div', {},
                React.createElement('p', {}, 'hello')
               );
        }
    });
});
