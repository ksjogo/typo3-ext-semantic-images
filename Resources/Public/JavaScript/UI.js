/**
 * @fileOverview Form JavaScript
 * @name Api.js
 * @author Johannes Goslar
 */
define('TYPO3/CMS/SemanticImages/UI', [
    'jquery',
    'TYPO3/CMS/SemanticImages/react',
    'TYPO3/CMS/SemanticImages/Remote',
    'TYPO3/CMS/SemanticImages/Quality',
    'TYPO3/CMS/SemanticImages/Concepts'
], function (
    $,
    React,
    Remote,
    Quality,
    Concepts
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
                React.createElement(Quality, {uid: this.props.uid}),
                React.createElement(Concepts, {uid: this.props.uid})
               );
        }
    });
});
