(function($) { $(function() { 
    $(document).on('click', '[role="beauty-radiobutton"]', function(ev) { 
        ev.preventDefault();
        
        var name = $(this).data('name');
        var value = $(this).data('value');
        
        $('[role="beauty-radiobutton"][data-name="'  + name + '"]').each(function() { 
            if ($(this).data('value') == value) {
                $(this).addClass('checked');
            } else {
                $(this).removeClass('checked');
            }
        });
        
        $('[name="' + name + '"]')
            .val(value)
            .trigger('change');
    });    
}) })(jQuery)
