const $ = require('jquery');
require('select2');

module.exports = {
    init: function (parent) {
        $.fn.select2.defaults.set('language', 'sk');
        parent.find('select[data-trexima-european-cv-bind-select2]').each(function () {
            $(this).select2({
                templateResult: function(result) {
                    if (result.element && $(result.element).data('select2-description')) {
                        //return $('<div />').text(result.text).attr('title', result.title).tooltip();
                        return $('<div />').text(result.text).append($('<div class="select2-description" />').text($(result.element).data('select2-description')));
                    }

                    return result.text;
                },
                templateSelection: function(result) {
                    return result.text;
                },
                width: '100%',
                theme: "bootstrap"
            });
        });
    }
};