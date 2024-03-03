jQuery(document).ready(function($) {
    $('#myForm').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: myFormAjax.ajaxurl,
            data: {
                action: 'submit_my_form',
                data: formData
            },
            success: function(response) {
                $('#formResponse').html(response);
                $('#myForm')[0].reset();
            }
        });
    });
});
