/**
 * @fileOverview Form JavaScript
 * @name Api.js
 * @author Johannes Goslar
 */
define('TYPO3/CMS/SemanticImages/UI', [
    'jquery',
    'TYPO3/CMS/SemanticImages/react',
    'TYPO3/CMS/SemanticImages/Remote'
], function (
    $,
    React,
    Remote
) {
    return React.createClass({
        getInitialState: function(){
            return {
                quality: null
            };
        },
        onQuality(){
            var self = this;
            self.setState({quality: 'calculating'});
            Remote.quality(self.props.uid, function(err, data){
                self.setState({quality: data});
            });
        },
        render: function(){
            return React.createElement('div', {},
                React.createElement('p', {}, this.props.name +  this.props.uid),
                React.createElement('br', {}),
                // quality
                React.createElement('p', {}, 'Quality:',
                  this.state.quality != null ? this.state.quality : React.createElement('button', {onClick: this.onQuality}, 'calculate')
                 )
               );
        }
    });
});
