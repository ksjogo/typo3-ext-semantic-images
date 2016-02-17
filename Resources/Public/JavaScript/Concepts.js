/**
 * @fileOverview Form JavaScript
 * @name Api.js
 * @author Johannes Goslar
 */
define('TYPO3/CMS/SemanticImages/Concepts', [
    'jquery',
    'TYPO3/CMS/SemanticImages/react',
    'TYPO3/CMS/SemanticImages/Remote',
], function (
    $,
    React,
    Remote
) {
    return React.createClass({
        getInitialState: function(){
            return {
                data: null,
                running: false
            };
        },
        feedData: function(data){
            var sorted = [];
            for (var concept in data)
                sorted.push([concept, data[concept]]);
            sorted.sort(function(a, b) {return b[1] - a[1];});
            this.setState({data: sorted});
        },
        onQuality(){
            var self = this;
            self.setState({running: true});
            Remote.concepts(self.props.uid, function(err, data){
                self.setState({running: false});
                self.feedData(data);
            });
        },
        render: function(){
            return React.createElement('div', {className: 'form-section'}, React.createElement('label', {}, 'Concepts:'),
                (!this.state.data && !this.state.running) ? React.createElement('button', {onClick: this.onQuality}, 'calculate') : '',
                (!this.state.data && this.state.running) ? 'calculating' : '',
                (!this.state.data) ? '' : $.map(this.state.data, function(element){
                    return React.createElement('p', {}, element[0] + ' ' + element[1]);
                })
               );
        }
    });
});
