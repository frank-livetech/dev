(function ($) {

    $(document).ready(function() {

        object_editor('editor');
        

        $(window).trigger('resize',null);

    });


    $(window).resize(function() {
        let windowHeight = $(window).outerHeight();
        let headerHeight = $('header').length > 0 ? $('header').outerHeight() : 0 ;
        let pageTopHeight = $('.page-static-header').length > 0 ? $('.page-static-header').outerHeight() : 0;
        let pageFooterHeight = $('.page-static-footer').length > 0 ? $('.page-static-footer').outerHeight() : 0;

        let colHeight = windowHeight-headerHeight-pageFooterHeight-pageTopHeight;

        $('.page-body').height(colHeight);

        $('.smart-col').css({'max-height':colHeight});
    });

    
})(jQuery);