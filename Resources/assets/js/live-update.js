const $ = require('jquery');

module.exports = {
    init: function (parent) {
        parent.find('[data-trexima-european-cv-live-update]').each(function () {
            var element = parent.find($(this).data('trexima-european-cv-live-update'));
            var container = $(this);
            var liveUpdateHandler = function () {
                if ($(this).data('flatpickr-date')) {
                    var elementFlatpickr = $(this).get(0)._flatpickr;
                    var selectedDate = elementFlatpickr.selectedDates[0];
                    if (selectedDate) {
                        container.html(flatpickr.formatDate(selectedDate, elementFlatpickr.config.altFormat));
                    } else {
                        container.html('');
                    }
                } else if ($(this).is('select')) {
                    if ($(this).val()) {
                        container.html($(this).find('option:selected').text());
                    } else {
                        container.html('');
                    }

                } else {
                    container.text($(this).val());
                }
            };

            element.filter('select').on('change', liveUpdateHandler);
            element.filter('input, textarea').on('input change', liveUpdateHandler);
        });

        /**
         * Element content is visible if referenced field isn't filled
         */
        parent.find('[data-trexima-european-cv-live-update-default]').each(function () {
            var element = parent.find($(this).data('trexima-european-cv-live-update-default'));
            var container = $(this);
            var liveUpdateDefaultHandler = function () {
                if ($(this).val()) {
                    container.hide().addClass('hidden');
                } else {
                    container.hide().removeClass('hidden').show();
                }
            };

            element.filter('select').on('change', liveUpdateDefaultHandler);
            element.filter('input, textarea').on('input change', liveUpdateDefaultHandler);
        });

        /**
         * Element content is visible when referenced field is filled
         */
        parent.find('[data-trexima-european-cv-live-update-filled]').each(function () {
            var element = parent.find($(this).data('trexima-european-cv-live-update-filled'));
            var container = $(this);
            var liveUpdateFilledHandler = function () {
                if (!$(this).val()) {
                    container.hide().addClass('hidden');
                } else {
                    container.hide().removeClass('hidden').show();
                }
            };

            element.filter('select').on('change', liveUpdateFilledHandler);
            element.filter('input, textarea').on('input change', liveUpdateFilledHandler);
        });
    }
};