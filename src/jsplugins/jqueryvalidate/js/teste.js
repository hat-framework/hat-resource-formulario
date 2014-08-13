$('input[type="submit"]').on('click', function(){            
     $($(this).parents('form')).validate({rules:{
        "enunciado":{ required: true }, 
        "qtipo":{ required: true }, 
        "multiplas_opt":{ required: true }, 
        "escala_elementos":{ required: true } } , 

        messages:{ "enunciado":{ required: 'O campo Título da Pergunta deve ser preenchido' }, 
        "qtipo":{ required: 'O campo Tipo deve ser preenchido' }, 
        "multiplas_opt":{ required: 'O campo Limite de Alternativas deve ser preenchido' }, 
        "escala_elementos":{ required: 'O campo Número de Elementos deve ser preenchido' }},

        success: function(label) {
            $('#v'+label.attr('for')).text('Ok!').removeClass('erro').addClass('valid_msg');
        },

        errorPlacement: function(label, element) {
            $('#v'+label.attr('for')).html(label.text()).removeClass('valid_msg').addClass('erro'); 
        }, 
        
        submitHandler: function(form) {

            $.ajax({
                url: $('#'+form.id).attr('action'),
                type: 'POST',
                data: $('#'+form.id).serialize(),
                dataType: 'json',
                beforeSend: function(){
                    blockUI_wait("Enviando dados...");
                    $('#erro').hide();
                    $('#success').hide();
                    $('.erro').delay('1200').fadeOut('slow');
                    $('.valid_msg').delay('1200').fadeOut('slow');
                },
                success: function(json) {
                    blockUI_unwait();
                    if(typeof json.redirect != 'undefined') {
                        window.location.href = json.redirect;
                    }

                    if(json.status == 1){
                        if(json.is_editing != 1){                                    
                            $('form#form_questionario_show_formulario1')[0].reset();

                        }

                        if (typeof json.success != 'undefined' && json.success != '') blockUI_success(json.success);
                        else if(typeof json.alert != 'undefined' && json.alert != '') blockUI_alert(json.alert);
                        else blockUI_error('Operação concluída, mas sem resposta do servidor!');
                    }else{
                        if (typeof json.erro != 'undefined') blockUI_error(json.erro);
                        else blockUI_error('Operação concluída, mas sem resposta do servidor!');
                        for (var camp in json){
                             $('#v'+camp).text(json[camp]).fadeIn('slow').addClass('erro').removeClass('valid_msg');
                        }
                    }

                },
                error: function(erro){
                    blockUI_unwait();
                    blockUI_error("Erro na comunicação com o site");
                }
            });
        }
    });
});