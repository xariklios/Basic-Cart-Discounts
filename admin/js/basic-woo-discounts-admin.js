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

            console.log(includedProdIds);

            $.ajax({
                type: 'POST',
                url: bcdScript.ajaxUrl,
                data: {
                    action: 'bcd_create_new_rule',
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


        /* Disable rule toggle */

        $('.table__item input').change(function () {

            let ruleId = $(this).parents('.table__row').data("id"); //get current rule id

            $.ajax({
                type: 'POST',
                url: bcdScript.ajaxUrl,
                data: {
                    action: 'bcd_update_rule_status',
                    id: ruleId,
                },
                success: function (res) {
                    alert('Rule ' + ruleId + ' has been set to ' + res.data);
                }
            })
        });
    });
})(jQuery);
