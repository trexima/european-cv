const $ = require('jquery');

module.exports = {
    init: function (parent) {
        parent.find('.european-cv-tootlip').each(function () {
            $(this).on('mouseenter click', function (e) {
                e.preventDefault();
                $(this).find('.european-cv-tooltip-content').stop().fadeIn(200);
                $(this).addClass('active');
            }).on('mouseleave', function () {
                $(this).find('.european-cv-tooltip-content').stop().fadeOut(200);
                $(this).removeClass('active');
            });
        });
    }
};