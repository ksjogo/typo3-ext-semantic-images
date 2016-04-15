/**
 * @fileOverview Form JavaScript
 * @name Api.js
 * @author Johannes Goslar
 */
define([
    'jquery',
    './react',
], function (
    $,
    React
) {
    return React.createClass({
        getInitialState: function(){
            return {
                value: this.props.real.value
            };
        },
        onChange: function(event){
            this.setState({value: event.target.value});
            this.props.real.value = event.target.value;
        },
        render: function(){
            return React.createElement('textarea', {
                className: 'form-control',
                onChange: this.onChange,
                value: this.state.value
            });
        }
    });
});
