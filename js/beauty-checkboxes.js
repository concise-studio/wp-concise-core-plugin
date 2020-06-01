(function($) { $(function() { 
    $(document).on('click', '[role="beauty-checkbox"]', function(ev) { 
        ev.preventDefault();
        
        var input = $(this).siblings('input[type="hidden"]');
        
        if ($(this).hasClass('checked')) {
            $(this)
                .removeClass('checked')
                .trigger('valueChanged');
            input
                .val(0)
                .trigger('change');
        } else {
            $(this)
                .addClass('checked')
                .trigger('valueChanged');
            input
                .val(1)
                .trigger('change');
        }
    });   
}) })(jQuery)
