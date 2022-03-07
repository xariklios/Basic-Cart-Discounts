(function ($) {
    'use strict';
    $(document).ready(function () {
        console.log('true')
        $('#bcd_new_form').on('submit', function (e) {
            e.preventDefault();

            let title = $('#title').val();
            console.log(title)

            $.ajax({
                type: 'POST',
                url: bcdScript.ajaxUrl,
                data: {
                    action: 'create_new_rule',
                    title: title,
                },
                success: function () {
                    console.log('entered')
                }
            })
        })
    });
})(jQuery);
