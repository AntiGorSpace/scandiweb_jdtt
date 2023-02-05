"use strict";
let IndexPage = {
    delete_skus: function () {
        let ids = [];
        $('.delete-checkbox:checked').each(function () {
            let id = $(this).attr('date-sku_id');
            ids.push(id);
        });
        if (!ids.length)
            return;
        $.ajax({
            type: "POST",
            url: "api/delete_skus.php",
            data: { ids: ids },
            dataType: 'application/json',
            success: function (res) { },
            error: function (res) {
                let res_data = JSON.parse(res.responseText);
                if (res_data.ok) {
                    location.reload();
                }
                else {
                    $('#product_form').append(`<div class="error">${res_data.error}</div>`);
                }
            },
        });
    },
    init: function () {
        $('#add_sku').on('click', () => {
            window.location.href = '/add-product.php';
        });
        $('#delete-product-btn').on('click', () => {
            this.delete_skus();
        });
    }
};
IndexPage.init();
