function jqValidate_sendform(form, successFunc, resetFunc){
    $.ajax({
        url: $(form).attr('action'),
        type: 'POST',
        data: $(form).serialize(),
        dataType: 'json',
        beforeSend: function(){
            jqValidate_sending();
        },
        success: function(json) {
            
            try{ blockUI_unwait(); }catch (e){}
            if(typeof json.redirect != 'undefined') {
                window.location.href = json.redirect;
            }

            jqValidate_messages(json);
            if(json.status == 1){
                if(json.is_editing != 1){                                    
                    $(form)[0].reset();
                    if (typeof resetFunc == "function") {
                        try{ resetFunc(); }catch (e){console.log(e.message)}
                    }
                }
                if (typeof successFunc == "function") try{ successFunc(); }catch (e){}
            }else{
                for (var camp in json){
                    $(form).find('#v'+camp).text(json[camp]).fadeIn('slow').addClass('erro').removeClass('valid_msg');
                }
            }

        },
        error: function(erro){
            jqValidate_errocomunicacao(erro);
        }
    });
}

function jqValidate_sending(){
    try{ blockUI_wait("Salvando..."); }catch (e){}
    $('#erro').hide();
    $('#success').hide();
    $('.erro').delay('1200').fadeOut('slow');
    $('.valid_msg').delay('1200').fadeOut('slow');
}

function jqValidate_messages(json){
    var msg  = '';
    var blui = '';
    if(json.status == 1 || json.status == '1'){
        if (typeof json.success != 'undefined' && json.success != '') {msg = json.success; blui = 's';}
        else if(typeof json.alert != 'undefined' && json.alert != '') {msg = json.alert;   blui = 'a';}
        else {msg = 'Dados enviados corretamente, mas sem a confirmação do servidor.';     blui = 'e';} 
    }else{
        if     (typeof json.erro  != 'undefined' && json.erro  != '') {msg = json.erro;  blui = 'e';}
        else if(typeof json.alert != 'undefined' && json.alert != '') {msg = json.alert; blui = 'a';}
        else {msg = 'Ocorreu algum problema ao enviar os dados para o servidor. Recarregue a página e tente novamente.'; blui = 'e';} 
    }

    try{
        switch (blui){
            case 's':blockUI_success(msg); break;
            case 'e':blockUI_error(msg); break;
            case 'a':blockUI_alert(msg); break;
        }
    }catch(e){ alert(msg);}
}

function jqValidate_errocomunicacao(erro){
    try{
        blockUI_unwait();
        blockUI_error("Erro na comunicação com o site.<hr/> Detalhes: "+erro['responseText']);
    }catch(e){
        alert("Erro na comunicação com o site.<hr/> Detalhes: "+erro['responseText']);
    }
}

function jqValidate_submit(tform, rles, msgs, successFunc, resetFunc){
    if(tform.find('#ajax').length === 0){
        tform.append('<input type=\"hidden\" id=\"ajax\" name=\"ajax\" value=\"true\" />');
    }

    tform.validate({
        rules:    rles,
        messages: msgs,
        success: function(label) {
            tform.find('#v'+label.attr('for')).text('Ok!').removeClass('erro').addClass('valid_msg');
        },
        errorPlacement: function(label, element) {
            tform.find('#v'+label.attr('for')).html(label.text()).removeClass('valid_msg').addClass('erro'); 
        },                    
        submitHandler: function(form) {
            jqValidate_sendform(form, successFunc, resetFunc);
        }
     });  
}