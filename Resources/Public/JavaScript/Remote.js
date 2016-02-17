/**
 * @fileOverview Remote functions for Semantic Images
 * @name Remote.js
 * @author Johannes Goslar
 */
define('TYPO3/CMS/SemanticImages/Remote', [
    'jquery'
], function (
    $
) {
    return {
        route: function(id)
        {
            if (TYPO3)
                return TYPO3.settings.ajaxUrls[id];
            else if (window && window.parent && window.parent.TYPO3)
                return window.parent.TYPO3.settings.ajxUrls[id];
            else
                return '';
        },
        quality: function(uid, cb)
        {
            $.post(this.route('semanticimages_quality'), {uid: uid})
                .done(function(data) {
                    cb(null, data);
                }).
                fail(function(jqXHR, textStatus, errorThrown) {
                    cb(textStatus, null);
                });
        }
    };
});
