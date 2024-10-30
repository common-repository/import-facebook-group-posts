jQuery(document).ready(function ($) {
    $('#fbg-posts-table input[type="checkbox"]').click(function () {
        if( $(this).is(':checked') ) {
            $('#fbg-pool').append( $(this).parent().find('div') );
        }
    });
});