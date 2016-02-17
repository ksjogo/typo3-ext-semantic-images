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
        getInitialState: function(){
            return {
                data: null,
                running: false
            };
        },
        onQuality(){
            var self = this;
            self.setState({running: true});
            Remote.quality(self.props.uid, function(err, data){
                self.setState({data: data, running: false});
            });
        },
        render: function(){
            return React.createElement('div', {className: 'form-section'}, React.createElement('label', {}, 'Quality Analysis:'),
                (!this.state.data && !this.state.running) ? React.createElement('button', {onClick: this.onQuality}, 'calculate') : '',
                (!this.state.data && this.state.running) ? 'calculating' : '',
                (!this.state.data) ? '' : $.map(this.state.data, function(value, key){
                    return React.createElement('p', {}, key + ' ' + value);
                })
               );
        }
    });
});
