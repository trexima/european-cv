const $ = require('jquery');
require('blueimp-file-upload');

module.exports = {
    init: function (parent) {
        if (typeof $.fn.fileupload !== 'undefined') {
            /* jQuery File Upload for one image */
            parent.find('[data-trexima-european-cv-jquery-file-upload=image]').each(function () {
                var input = $(this).find('input[type=file]');
                var dropZone = $(this);
                var progressBar = $(this).find('.jquery-file-upload-progress-bar ');
                var thumbnailContainer = $(this).find('.jquery-file-upload-thumbnail');
                var uploadUrl = $(this).data('trexima-european-cv-jquery-file-upload-url');
                var resultInput = $($(this).data('trexima-european-cv-jquery-file-upload-result'));
                var baseUrl = $(this).data('trexima-european-cv-jquery-file-upload-base-url');

                var fileUpload = input.fileupload({
                    url: uploadUrl,
                    dataType: 'json',
                    dropZone: dropZone,
                    paramName: 'file',
                    maxNumberOfFiles: 1,
                    start: function(data) {
                        thumbnailContainer.html('Čakajte, prosím...');
                        progressBar.css('width', 0);
                    },
                    done: function (e, data) {
                        $.each(data.result.files, function (index, file) {
                            if (file.error) {
                                dropZone.removeClass('jquery-file-upload-uploaded');
                                thumbnailContainer.html(file.error);
                            } else {
                                dropZone.addClass('jquery-file-upload-uploaded');

                                thumbnailContainer.html($('<img />').attr('src', baseUrl+file.filename));
                                resultInput.val(file.filename);
                            }
                        });
                    },
                    fail: function (e, data) {
                        if (data.jqXHR.status === 413) {
                            thumbnailContainer.html('Súbor je príliš veľký.');
                        } else {
                            thumbnailContainer.html('Neočakávaná chyba. Odpoveď: '+data.jqXHR.status+'.');
                        }
                    },
                    drop: function (e, data) {
                        if (data.files.length > 1) {
                            e.preventDefault();
                            alert('Naraz môžete nahrať maximálne 1 súbor.');
                        }
                    },
                    progressall: function (e, data) {
                        var progress = parseInt(data.loaded / data.total * 100, 10);
                        progressBar.css('width', progress + '%');
                    }
                });
            });

            parent.find('[data-trexima-european-cv-jquery-file-upload-remove]').click(function (e) {
                e.preventDefault();
                var jqueryFileUploadElement = $($(this).data('trexima-european-cv-jquery-file-upload-remove'));

                var thumbnailContainer = jqueryFileUploadElement.find('.jquery-file-upload-thumbnail');
                var thumbnailContainerDefault = thumbnailContainer.data('trexima-european-cv-jquery-file-upload-default');
                var resultInput = $(jqueryFileUploadElement.data('trexima-european-cv-jquery-file-upload-result'));

                // Hide remove element only if is inside upload container
                jqueryFileUploadElement.removeClass('jquery-file-upload-uploaded');

                thumbnailContainer.html(thumbnailContainerDefault);
                resultInput.val('');
            });
        }
    }
};

