/**
 * @fileOverview Form JavaScript
 * @name Api.js
 * @author Johannes Goslar
 */
define('TYPO3/CMS/SemanticImages/UI', [
    'jquery',
    'TYPO3/CMS/SemanticImages/react'
], function (
    $,
    React
) {
    return React.createClass({
        render: function(){
            return React.createElement('p', {},  this.props.name +  this.props.uid);
        }
    });
});
