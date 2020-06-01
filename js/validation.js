(function($) { $(function() {      
    function validate(form) {
        var validationAction = form.data('validation');        
        var formData = form.serializeArray();
        var errorsBlock = $(form.data('errors'));
        var errorsList = errorsBlock.find('[role="errors-wrapper"]');
        
        formData.push({
            name: 'action',
            value: validationAction
        });
        
        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            method: 'POST',
            dataType: 'json',            
            data: formData
        }).done(function(response) {     
            if (response.result !== 1) {
                // Clean list
                errorsList.find('[role="error"]').remove();
                
                // Set info about missed fields
                if (response.missed.length > 0) {
                    response.missed.forEach(function(field) { 
                        var input = form.find('[name*="' + field + '"]');
                        var alertText = input.data('missed-text');
                        
                        input.parent().addClass('form-group-alert');
                        
                        if (typeof alertText !== 'undefined') {                        
                            errorsList.append('<p role="error"><span>' + alertText + '</span</p>');
                        }
                    });
                }
                
                // Set info about failed fields
                for (var field in response.failed) {
                    var input = form.find('[name*="' + field + '"]');                        
                    var alertText = response.failed[field];                                                
                    
                    input.parent().addClass('form-group-alert');
                    errorsList.append('<p role="error"><span>' + alertText + '</span</p>')
                }
                
                errorsBlock.show();
                
                var notPassedEvent = jQuery.Event('validation_not_passed');
                notPassedEvent.missed = response.missed;
                notPassedEvent.failed = response.failed;
                form.trigger(notPassedEvent);
            } else {
                errorsBlock.hide();
                form.trigger('validation_passed');
            }   
        });
    }
    
    
    
    
    $(document).on('submit', 'form[data-validation]', function(ev) { 
        ev.preventDefault();
        
        validate($(this));
    });
    
    $(document).on('change', 'form[data-validation] .form-group-alert :input', function(ev) { 
        $(this).closest('.form-group-alert').removeClass('form-group-alert');
        
        validate($(this).closest('form[data-validation]'));
    });
}) })(jQuery)
