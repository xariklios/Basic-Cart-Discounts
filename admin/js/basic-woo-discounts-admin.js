(function ($) {
    'use strict';
    $(document).ready(function () {
        console.log('true')
        $('#bcd_new_form').on('submit', function (e) {
            e.preventDefault();

            let title = $('#title').val();
            let includedProdIds = $("#_bcd_products_included_ids").val();
            let discountType = $('#bcd_discount_type').find(":selected").val();
            let discountValue = $('#bcd_discount_value').val();


            $.ajax({
                type: 'POST',
                url: bcdScript.ajaxUrl,
                data: {
                    action: 'create_new_rule',
                    title: title,
                    includedProdIds: includedProdIds,
                    discountType: discountType,
                    discountValue: discountValue
                },
                success: function () {
                    console.log('entered')
                }
            })
        })
    });
})(jQuery);
