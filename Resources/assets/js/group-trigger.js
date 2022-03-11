const $ = require('jquery');

module.exports = {
    init: function (parent) {
        /**
         * Trigger some action on element events
         * data-trigger-show-on-value and data-trigger-hide-on-value are disjunctive(can't be defined on same element)
         *
         * data-trexima-european-cv-group-trigger - name of group of elements that rely on element(one group must have exact one group trigger element)
         * data-group - name of group where element belongs
         * data-group-show-on-value - show element on exact value of trigger element and hide for other values
         * data-group-hide-on-value - hide element on exact value of trigger element and show for other values
         */
        parent.find('[data-trexima-european-cv-group-trigger]').each(function () {
            var groupName = $(this).data('trexima-european-cv-group-trigger');
            var groupElements = $('[data-group='+groupName+']');
            var groupActions = {};

            groupElements.each(function () {
                var element = $(this);
                var showValue = element.data('group-show-on-value');
                var hideValue = element.data('group-hide-on-value');
                var action;
                var value;
                if (typeof showValue !== 'undefined') {
                    action = 'show';
                    value = showValue;
                } else if (typeof hideValue !== 'undefined') {
                    action = 'hide';
                    value = hideValue;
                }

                if (typeof action === 'undefined') {
                    return;
                }

                if (typeof groupActions[value] === 'undefined') {
                    groupActions[value] = {
                        show: [],
                        hide: []
                    };
                }
                groupActions[value][action].push(element.get(0));
            });

            $(this).on('change', function () {
                var value = $(this).val();
                // Checkbox equals 1 if is checked otherwise 0
                if ($(this).is('[type=checkbox], [type=radio]')) {
                    value = $(this).is(':checked') ? value : 0;
                }

                var showElements = [];
                var hideElements = [];
                for (var groupValue in groupActions) {
                    if (groupValue == value) {
                        showElements = showElements.concat(groupActions[groupValue].show);
                        hideElements =  hideElements.concat(groupActions[groupValue].hide);
                    } else {
                        hideElements = hideElements.concat(groupActions[groupValue].show);
                        showElements = showElements.concat(groupActions[groupValue].hide);
                    }
                }

                $(hideElements).addClass('hidden').hide();
                $(showElements).removeClass('hidden').show();
            });
        });
    }
};