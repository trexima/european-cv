const $ = require('jquery');
require('jquery.dirtyforms');

module.exports = {
    init: function (parent) {
        parent.find('[data-trexima-european-cv-are-you-sure]').each(function () {
            $(this).dirtyForms({
                ignoreSelector: '[data-toggle=confirm]' // Ignore if another popup is showed before page leave
            });
        });
    }
};