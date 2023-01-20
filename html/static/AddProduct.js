AppProduct = {
    
    change_visibility: function(){
        let id = $('#productType').val();
        $('[data-category_id]').addClass('hidden');
        $(`[data-category_id=${id}]`).removeClass('hidden');
    },
    save_sku:function(){
        $('.error').remove();
        let req_data = {};
        let form_data = $('#product_form').serializeArray();
        form_data.forEach((e)=>{
            let value = e.value.trim();
            let name = e.name;
            if($(`#${e.name}`).attr('name')){
                name = $(`#${e.name}`).attr('name');
            }
            if(value) req_data[name] = value;
        })
        req_data.category_id = req_data.category_id*1;
        if (this.check_fields(req_data)) this.send_sku(req_data);
    },
    check_fields: function(req_data){
        let send = true;
        ['sku', 'name', 'price'].forEach((e)=>{
            if (!req_data[e]){
                $(`#${e}`).closest('.form-row').append('<div class="error">must be not null</div>');
                send = false;
            }
        });
        return send;
    },
    send_sku: function(data){
        $.ajax({
            type: "POST",
            url: "api/new_sku.php",
            data: data,
            dataType: 'application/json',
            success: function (res) {},
			error:function(res){
                let res_data = JSON.parse(res.responseText) 
                if (res_data.ok) {
                    $('input').val('');
                }else{
                    $('#product_form').append(`<div class="error">${res_data.error}</div>`);
                } 
			}
          });
    },
    init: function(){
        $('#productType').on('change', ()=>{
            this.change_visibility()
        })
        this.change_visibility();
        $('#cansel').on('click',()=>{
            window.location.href = '/';
        })
        $('#save').on('click',()=>{
            this.save_sku();
        })
    }
}

AppProduct.init()