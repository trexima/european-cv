const $ = require('jquery');
const Sortable = require('sortablejs');

module.exports = {
    init: function (parent) {
        let sortableContainers = parent.find('[data-trexima-european-cv-sortable]');
        sortableContainers.each(function () {
            let sortable = Sortable.create($(this).get(0), {
                draggable: '.drag-item',
                handle: '.drag-handle',
                filter: '.drag-ignore',
                dataIdAttr: 'id', // Used in toArray and sort methods
                preventOnFilter: false,
                animation: 150,
                ghostClass: "sortable-ghost"
            });

            $(this).data('trexima-european-cv-sortable', sortable);
        })
    }
};