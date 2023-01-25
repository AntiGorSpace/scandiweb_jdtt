AppProduct = {
    
    change_visibility: function(){
        let id = $('#productType').val();
        $('[data-category_id]').addClass('hidden');
        $(`[data-category_id=${id}]`).removeClass('hidden');
    },
    save_sku:function(){
        $('.error').remove();
        $('.success').remove();
        let req_data = {cat_properties:{}};
        let form_data = $('#product_form').serializeArray();
        form_data.forEach((e)=>{
            let value = e.value.trim();
            let name = e.name;
            if(name && name.match(/^cat_/)){
                if(value) req_data.cat_properties[name.replace('cat_', '')] = value;
            }else{
                if(value) req_data[name] = value;
            }
        })
        req_data.category_id = req_data.category_id*1;
        if (this.check_fields(req_data)) this.send_sku(req_data);
    },
    show_error:function(e,m,c){
        $(`#${e}`).closest('.form-row').append(`<div class="${c}">${m}</div>`);
    },
    check_fields: function(req_data){
        let send = true;
        ['sku', 'name', 'price'].forEach((e)=>{
            if (!req_data[e]){
                this.show_error(e,'must be not null','error');
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
            success: (res)=> {},
			error:(res)=>{
                let res_data = JSON.parse(res.responseText) 
                if (res_data.ok) {
                    $('input').val('');
                    this.show_error('sku','Sku created', 'success');
                }else{
                    if (res_data.sku_exist){
                        this.show_error('sku','SKU alredy exist', 'error');
                    }
                    if (res_data.not_null){
                        for(id in res_data.not_null){
                            console.log(id)
                            this.show_error(id,'must be not null', 'error');
                        }
                    }
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