const $ = require('jquery');
require('flatpickr');
const Slovak = require('flatpickr/dist/l10n/sk.js').default.sk;

module.exports = {
    init: function (parent) {
        parent.find("[data-trexima-european-cv-flatpickr-date]").each(function () {
            flatpickr(this, {
                locale: Slovak,
                allowInput: true,
                altFormat: 'j.n.Y',
                altInput: true,
                onChange: function (selDates, dateStr) {
                    this._selDateStr = dateStr;
                },
                onClose: function (selectedDates, dateStr, instance) {
                    // Update date picker on close with user input value
                    if (instance.config.allowInput && instance._input.value !== instance._selDateStr) {
                        instance.setDate(instance._input.value, true, instance.config.altFormat);
                    }
                }
            });
        });
    }
};