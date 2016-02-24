/**
 * @fileOverview Form JavaScript
 * @name Api.js
 * @author Johannes Goslar
 */
define('TYPO3/CMS/SemanticImages/Zeed', [
    'jquery',
    'TYPO3/CMS/SemanticImages/reacttoken',
    'TYPO3/CMS/SemanticImages/ConceptList',
], function (
    $,
    Reacttoken,
    ConceptList
) {
    var React = Reacttoken.React.default;
    return React.createClass({
        getInitialState: function(){
            return {
            };
        },
        onChange: function(){
            var self = this;
            window.setTimeout(function(){
                self.props.real.value = self.refs.token.state.values.join(', ');
            });
        },
        render: function(){
            return React.createElement(Reacttoken, {
                className: 'form-control',
                ref: 'token',
                onAdd: this.onChange,
                onRemove: this.onChange,
                placeholder: 'type for concepts',
                defaultValues: this.props.defaultValues,
                options: ConceptList
            });
        }
    });
});
