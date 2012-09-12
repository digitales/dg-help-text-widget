jQuery(document).ready(function($) {
    $('div.help-box> .info').hide();
        $('div.help-box> h5').click(function() {
            var $instance = $(this);
            $instance.toggleClass('animation');
            $(this).next('div').slideToggle(400, function() {
                $instance.toggleClass('active');
        } );
    });
} );
