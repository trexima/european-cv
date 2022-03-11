const $ = require('jquery');
const sortable = require('./sortable');

module.exports = {
    init: function (parent) {
        sortable.init(parent);

        parent.find('[data-trexima-european-cv-dynamic-collection-add]').click(function (e) {
            e.preventDefault();
            var list = $($(this).data('trexima-european-cv-dynamic-collection-add'));
            // Try to find the counter of the list
            var counter = list.data('trexima-european-cv-dynamic-collection-counter');
            // If the counter does not exist, use the length of the list
            if (!counter) {
                counter = list.children().length;
            }

            // grab the prototype template
            var newWidget = list.data('trexima-european-cv-dynamic-collection-prototype');
            // replace the "__name__" used in the id and name of the prototype
            // with a number that's unique to your emails
            // end name attribute looks like name="contact[emails][2]"
            newWidget = $(newWidget.replace(/__name__/g, counter));

            // Increase the counter
            counter++;
            // And store it, the length cannot be used if deleting widgets is allowed
            list.data('trexima-european-cv-dynamic-collection-counter', counter);

            newWidget.appendTo(list);
            list.trigger('trexima-european-cv-dynamic-collection-entry:added', [newWidget]);
        });

        /**
         * Dynamic collection remove widget
         */
        $('form').on('click', '[data-trexima-european-cv-dynamic-collection-remove]', function (e) {
            e.preventDefault();
            var list = $($(this).data('trexima-european-cv-dynamic-collection-add'));
            var widget = $($(this).data('trexima-european-cv-dynamic-collection-remove'));
            var parent = widget.parents('[data-trexima-european-cv-dynamic-collection-prototype]').first();
            var counter = parent.data('trexima-european-cv-dynamic-collection-counter');
            // If the counter does not exist, use the length of the list
            if (!counter) {
                counter = list.children().length;
            }

            counter--;
            parent.data('trexima-european-cv-dynamic-collection-counter', counter);

            widget.remove();
            parent.trigger('trexima-european-cv-dynamic-collection-entry:removed');
        });

        /**
         * Sorting for dynamic collections.
         * Must use sortable plugin.
         *
         * Parameters:
         *  data-trexima-european-cv-dynamic-collection-sort - selector for collection container
         *  data-trexima-european-cv-dynamic-collection-sort-by - define pripority of element sorted by
         *  data-trexima-european-cv-dynamic-collection-sort-desc - if defined, descending order will be applied, ascending otherwise
         */
        parent.find('[data-trexima-european-cv-dynamic-collection-sort]').click(function (e) {
            e.preventDefault();
            var collection = $($(this).data('trexima-european-cv-dynamic-collection-sort'));
            var sortable = collection.data('trexima-european-cv-sortable');
            var asc = !$(this).data('trexima-european-cv-dynamic-collection-sort-desc');

            var sortableIds = [];
            collection.find('.drag-item').each(function () {
                var sortByElements = $(this).find('[data-trexima-european-cv-dynamic-collection-sort-by]');

                var values = [];
                sortByElements.each(function () {
                    var value = $(this).val();
                    if ($(this).data('flatpickr-date')) {
                        value = [$(this).get(0)._flatpickr.selectedDates[0]];
                    }

                    values.push({
                        value: value,
                        priority: $(this).data('trexima-european-cv-dynamic-collection-sort-by')
                    });
                });

                sortableIds.push({
                    id: $(this).attr('id'),
                    values: values
                });
            });

            // Sort values by priority
            for (var i in sortableIds) {
                sortableIds[i].values.sort(function (a, b) {
                    return a.priority - b.priority;
                });
            }

            // Main sorting function
            sortableIds.sort(function(a, b) {
                // TODO: Allow sorting of Flatpickr inputs
                /*if (a.value instanceof Date && b.value instanceof Date) {
                    return asc ? (b.value - a.value) : (a.value - b.value);
                }*/

                for (var i in a.values) {
                    // Assuming a and b has same structure with same number of elements
                    if (a.values[i].value !== b.values[i].value) {
                        return asc ? (b.values[i].value - a.values[i].value) : (a.values[i].value - b.values[i].value);
                    }
                }

                // Values are same
                return 0;
            });

            // Get order ids for sortable plugin and do element sorting
            var orderedIds = [];
            for (var i in sortableIds) {
                orderedIds.push(sortableIds[i].id);
            }
            sortable.sort(orderedIds); // Finally sort elements
        });
    }
};