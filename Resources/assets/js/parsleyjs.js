const $ = require('jquery');
const Parsley = require('parsleyjs');
require('parsleyjs/dist/i18n/sk');

module.exports = {
    init: function (parent) {
        if (typeof $.fn.parsley !== 'undefined') {
            // parsleyjs/dist/i18n/sk.js
            Parsley.addMessages('sk', {
                type: {
                    email:        "Prosím, vyplňte správnu e-mailovú adresu.",
                    url:          "Prosím, vyplňte platnú URL adresu."
                }
            });

            /*window.Parsley.on('field:validate', function() {
                // Remove server validation errors
                $('.validation-errors-server').hide();
            });*/

            /**
             * Override Parsley.js
             * Version 2.8.1
             *
             * Allow buttons with defined group trigger, validation of one or more
             * defined groups in button attribute "data-parsley-trigger-group".
             */
            $.extend(window.ParsleyExtend, {
                onSubmitValidate: function onSubmitValidate(event) {
                    var _this5 = this;

                    // This is a Parsley generated submit event, do not validate, do not prevent, simply exit and keep normal behavior
                    if (true === event.parsley) return;

                    // If we didn't come here through a submit button, use the first one in the form
                    var submitSource = this._submitSource || this.$element.find(Utils._SubmitSelector)[0];
                    this._submitSource = null;
                    this.$element.find('.parsley-synthetic-submit-button').prop('disabled', true);
                    if (submitSource && null !== submitSource.getAttribute('formnovalidate')) return;

                    window.Parsley._remoteCache = {};

                    // Our default group, must be defined in window.Parsley.config.group
                    var groups = ['default'];
                    var groupTrigger = $(submitSource).data('parsley-trigger-group');
                    if (typeof groupTrigger !== 'undefined') {
                        if (!Array.isArray(groupTrigger)) {
                            groupTrigger = [groupTrigger];
                        }
                        groups = groupTrigger;
                    }

                    var promises = [];
                    for (i in groups) {
                        // We can have more groups so we must concat all promises!
                        promises.push(this.whenValidate({ group: groups[i], event: event }));
                    }

                    if ('resolved' === window.Parsley.Utils.all(promises).state() && false !== this._trigger('submit')) {
                        // All good, let event go through. We make this distinction because browsers
                        // differ in their handling of `submit` being called from inside a submit event [#1047]
                    } else {
                        // Rejected or pending: cancel this submit
                        event.stopImmediatePropagation();
                        event.preventDefault();
                        if ('pending' === window.Parsley.Utils.all(promises).state()) window.Parsley.Utils.all(promises).done(function () {
                            _this5._submit(submitSource);
                        });
                    }
                }
            });

            parent.find('[data-trexima-european-cv-parsley-validate]').parsley({
                autoBind: false,
                inputs: 'input, textarea, select, [data-trexima-european-cv-dynamic-collection-prototype]',
                value: function (parsley) {
                    // Dynamic collection validation is based on number of entries in collection
                    if (parsley.$element.data('trexima-european-cv-dynamic-collection-prototype')) {
                        return ''+parsley.$element.children().length; // Parsley always expect string from inputs
                    }

                    return parsley.$element.val();
                },
                errorClass: 'is-invalid',
                successClass: 'is-valid',
                classHandler: function(ParsleyField) {
                    return ParsleyField.$element;
                },
                errorsContainer: function(ParsleyField) {
                    return ParsleyField.$element.parents('.form-group').first();
                },
                errorsWrapper: '<span class="invalid-feedback">',
                errorTemplate: '<div></div>',
                trigger: 'input',
                group: 'default'
            });
        }
    }
};