/**
 * @fileOverview Form JavaScript
 * @name Api.js
 * @author Johannes Goslar
 */
define('TYPO3/CMS/SemanticImages/UI', [
    'jquery',
    'TYPO3/CMS/SemanticImages/react',
    'TYPO3/CMS/SemanticImages/Remote',
    'TYPO3/CMS/SemanticImages/Quality'
], function (
    $,
    React,
    Remote,
    Quality
) {
    return React.createClass({
        getInitialState: function(){
            return {
            };
        },
        render: function(){
            return React.createElement('div', {},
                // React.createElement('p', {}, this.props.name +  this.props.uid),
                // React.createElement('br', {}),
                // quality
                React.createElement(Quality, {uid: this.props.uid})
               );
        }
    });
});
