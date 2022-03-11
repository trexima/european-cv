const $ = require('jquery');
const Parsley = require('parsleyjs');

module.exports = {
    init: function () {
        Parsley.addValidator('treximaEuropeanCvDynamicCollectionMin', {
            requirementType: 'integer',
            validateNumber: function(value, requirement) {
                return value >= requirement;
            },
            messages: {
                en: 'Add at least %s entries',
                sk: 'Pridajte aspoň %s položiek'
            }
        });

        /**
         * Scroll to collection with error.
         * Code taken over parsley.js UI.Form.focus
         */
        Parsley.on('form:error', function() {
            var focusedField = null;
            if (true === this.validationResult || 'none' === this.options.focus) return;

            for (var i = 0; i < this.fields.length; i++) {
                var field = this.fields[i];

                if (true !== field.validationResult && field.validationResult.length > 0 && 'undefined' === typeof field.options.noFocus) {
                    focusedField = field.$element;
                    if ('first' === this.options.focus) break;
                }
            }

            if (null === focusedField) return;
            if (focusedField.data('trexima-european-cv-dynamic-collection-prototype')) {
                $(window).scrollTop(focusedField.offset().top);
            }
        });

        $('[data-trexima-european-cv-dynamic-collection-prototype]').on('trexima-european-cv-dynamic-collection-entry:added trexima-european-cv-dynamic-collection-entry:removed', function (e) {
            // Validate collection if something is added or removed
            $(this).parsley().validate(); // Trigger validation if something changes in collection
        });
    }
};