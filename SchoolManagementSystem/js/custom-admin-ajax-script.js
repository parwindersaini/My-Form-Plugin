jQuery(document).ready(function($) {
    $('#custom-admin-form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
       console.log(form_data);
        $.ajax({
            type: 'POST',
            url: ajax_object.ajax_url,
            data: {
                action: 'custom_admin_form_submit',
                data:form_data // Get input data value
            },
            success: function(response) {
                if (response.success) {
                    $('#custom-admin-response').html('<div class="updated"><p>' + response.message + '</p></div>');
                } else {
                    $('#custom-admin-response').html('<div class="error"><p>' + response.message + '</p></div>');
                }
            }
        });
    });
});
