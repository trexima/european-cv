const $ = require('jquery');

module.exports = {
    init: function (parent) {
        /**
         * Driving licenses.
         * Auto check parent checkboxes based on Slovak legislative
         */
        parent.find('[data-trexima-european-cv-driving-license]').each(function () {
            var idTemplate = $(this).data('trexima-european-cv-driving-license');
            var mapping = {
                a: ['a1', 'am', 'a2'],
                a1: ['am'],
                b: ['am', 'b1'],
                b1: ['am'],
                c: ['am', 'b', 'b1', 'c1', 't'],
                c1: ['am', 'b', 'b1', 't'],
                d: ['am', 'b', 'b1', 'd1' ,'t'],
                d1: ['am', 'b', 'b1', 't'],
                b_e: ['am', 'b', 'b1'],
                d_e: ['am', 'b', 'b1', 'd', 'd1', 'b_e', 'd1_e', 't'],
                c_e: ['am', 'b', 'b1', 'c', 'c1', 'b_e', 'c1_e', 't'],
                c1_e: ['am', 'b', 'b1', 'c1', 'b_e', 't'],
                d1_e: ['am', 'b', 'b1', 'd1', 'b_e', 't'],
                a2: ['a1', 'am']
            };

            for (var id in mapping) {
                // Self executing anonymouse function is required for preserving variable in loop
                (function (idsToCheck) {
                    $('#'+idTemplate.replace(/__name__/g, id)).on('change', function (e) {
                        if ($(this).is(':checked')) {
                            for (var i in idsToCheck) {
                                $('#'+idTemplate.replace(/__name__/g, idsToCheck[i])).prop('checked', true).change();
                            }
                        }
                    });
                })(mapping[id].slice());
            }
        });
    }
};