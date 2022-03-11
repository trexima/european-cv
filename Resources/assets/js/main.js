const $ = require('jquery');
require('bootstrap');

const bsCustomFileInput = require('bs-custom-file-input');

const dynamicCollection = require('./dynamic-collection');
const drivingLicense = require('./driving-license');
const groupTrigger = require('./group-trigger');
const tooltip = require('./tooltip');
const liveUpdate = require('./live-update');
const jqueryFileUpload = require('./jquery-file-upload');
const areYouSure = require('./are-you-sure');
const select2 = require('./select2');
const parsleyjs = require('./parsleyjs');
const flatpickr = require('./flatpickr');
const autocomplete = require('./autocomplete');
const dynamicCollectionValidator = require('./dynamic-collection-validator');

$('[data-toggle="european-cv-tooltip"]').tooltip();
bsCustomFileInput.init();

dynamicCollection.init($(document));
drivingLicense.init($(document));
groupTrigger.init($(document));
tooltip.init($(document));
liveUpdate.init($(document));
jqueryFileUpload.init($(document));
areYouSure.init($(document));
select2.init($(document));
parsleyjs.init($(document));
flatpickr.init($(document));
autocomplete.init($(document));

dynamicCollectionValidator.init();

$('[data-trexima-european-cv-dynamic-collection-prototype]').on('trexima-european-cv-dynamic-collection-entry:added', function (e, newElement) {
    liveUpdate.init(newElement);
    autocomplete.init(newElement);
    select2.init(newElement);
    tooltip.init(newElement);
    bsCustomFileInput.init('#' + newElement.attr('id') + ' .custom-file input[type="file"]');

    newElement.find('.collapse').collapse('show');
    newElement.dirtyForms('rescan');
});
