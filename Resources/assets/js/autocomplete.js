const $ = require('jquery');
require('jquery-ui/ui/widgets/autocomplete');

module.exports = {
    init: function (parent) {
        /**
         * If this constant is passed to autocomplete, manual searching is triggered
         * and if result contains only one result, result is forcibly set as value of input.
         * e.g.: input.autocomplete('search', force);
         *
         */
        var force = {'force': true};

        /**
         * Attach autocomplete on inputs with attributes:
         *  data-trexima-european-cv-autocomplete - if provided autocomplete is attached with parameters defined by data-trexima-european-cv-autocomplete-* attributes
         *  data-trexima-european-cv-autocomplete-onchange - if provided autocomplete is attached and results of autocomplete is dependent on element velue defined by selector in this attribute
         *
         * Options:
         *  trexima-european-cv-autocomplete-min-length
         *  trexima-european-cv-autocomplete-url - URL where AJAX request from autocomplete is handled
         *  trexima-european-cv-autocomplete-data - data in JSON format
         */
        parent.find('[data-trexima-european-cv-autocomplete], [data-trexima-european-cv-autocomplete-onchange]').each(function () {
            var selectBox = $($(this).data('trexima-european-cv-autocomplete-onchange'));
            var autocompleteMinLength = $(this).data('trexima-european-cv-autocomplete-min-length');
            var autocompleteUrl = $(this).data('trexima-european-cv-autocomplete-url');
            var autocompleteData = $(this).data('trexima-european-cv-autocomplete-data');
            var input = $(this);

            if (typeof autocompleteUrl !== 'undefined' && typeof autocompleteData !== 'undefined') {
                throw 'Only one of data-trexima-european-cv-autocomplete-url, data-trexima-european-cv-autocomplete-data can be defined!';
            }

            var dataSource = autocompleteData;
            if (typeof autocompleteUrl !== 'undefined') {
                dataSource = function (request, response) {
                    $.ajax({
                        url: autocompleteUrl,
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            'selected-value': function () {
                                if (typeof selectBox !== 'undefined') {
                                    return selectBox.val();
                                }

                                return false;
                            },
                            term: request.term
                        },
                        success: function (data) {
                            if (request.term == force && data.length == 1) {
                                // Manual triggered search
                                input.val(data[0].value);
                                response([]);
                            } else {
                                response(data);
                            }
                        }
                    });
                };
            }

            input.autocomplete({
                source: dataSource,
                minLength: autocompleteMinLength,
                select: function (event, ui) {
                    if (typeof input.data('rawMaskFn') !== 'undefined') {
                        // BUG FIX: jQuery Masked Input Plugin replace our value after 10 miliseconds!
                        setTimeout(function () {
                            input.val(ui.item.value);
                        }, 10);
                        input.blur();
                    }
                }
            }).focus(function () {
                // Display autocomplete on focus
                $(this).autocomplete('search', $(this).val());
            });

            if (typeof selectBox !== 'undefined') {
                /* Attach autocomplete to text input based on selected value in selectbox or input */
                selectBox.change(function () {
                    // Show possible values on selectbox change
                    input.autocomplete('search', force);
                });
            }
        });
    }
};