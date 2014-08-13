function getBaseURL() {
    return window.location.protocol +"//"+ window.location.host+"/";  // entire url including querystring - also: window.location.href;
}


function findCepAddresLOCALHOSTWebservice(cep, rua_id, bairro_id, cidade_id, estado_id){
    var url = getBaseURL() + "usuario/endereco/getcep/"+cep;
    $.ajax({
        url: url,
        dataType: "json",
        success: function(json){
            if(json.resultado == 1){
                $('#'+rua_id).val(unescape(json.rua));
                $('#'+bairro_id).val(unescape(json.bairro));
                $('#'+cidade_id).val(unescape(json.cidade));
                $('#'+estado_id).val(unescape(json.estado.toUpperCase()));
            }else{
                findCepAddresCORREIOSWebservice(cep, rua_id, bairro_id, cidade_id, estado_id)
                //findCepAddresXTENDSWebservice(cep, rua_id, bairro_id, cidade_id, estado_id)
                //findCepAddresKINGHOSTWebservice(cep, rua_id, bairro_id, cidade_id, estado_id);
            }
        },
        error: function(){
            findCepAddresCORREIOSWebservice(cep, rua_id, bairro_id, cidade_id, estado_id)
            //findCepAddresXTENDSWebservice(cep, rua_id, bairro_id, cidade_id, estado_id)
            //findCepAddresKINGHOSTWebservice(cep, rua_id, bairro_id, cidade_id, estado_id)
        }
    });
}

/*
function findCepAddresKINGHOSTWebservice(cep, rua_id, bairro_id, cidade_id, estado_id){
    var url  = 'http://webservice.kinghost.net/web_cep.php?auth=12473be26ed7caa39d0830b97dbea02a&formato=json&cep='+cep.substring(0,5) +'-'+cep.substring(5,8);
    $.ajax({
        url: url,
        dataType: "json",
        success: function(json){
            if(json.result == 1){
                $('#'+rua_id).val(unescape(json.tp_logradouro)+': '+unescape(json.logradouro));
                $('#'+bairro_id).val(unescape(json.bairro));
                $('#'+cidade_id).val(unescape(json.cidade));
                $('#'+estado_id).val(unescape(json.uf.toUpperCase()));
            }else{
                findCepAddresXTENDSWebservice(cep, rua_id, bairro_id, cidade_id, estado_id);
            }
        },
        error: function(){
            findCepAddresXTENDSWebservice(cep, rua_id, bairro_id, cidade_id, estado_id)
        }
    });
}

function findCepAddresXTENDSWebservice(cep, rua_id, bairro_id, cidade_id, estado_id){
    var url  = 'http://xtends.com.br/webservices/cep/json/'+cep.substring(0,5) +'-'+cep.substring(5,8);
    $.ajax({
        url: url,
        dataType: "json",
        success: function(json){
            if(json.result == 1){
                $('#'+rua_id).val(unescape(json.tp_logradouro)+': '+unescape(json.logradouro));
                $('#'+bairro_id).val(unescape(json.bairro));
                $('#'+cidade_id).val(unescape(json.cidade));
                $('#'+estado_id).val(unescape(json.uf.toUpperCase()));
            }else{
                findCepAddresCORREIOSWebservice(cep, rua_id, bairro_id, cidade_id, estado_id);
            }
        },
        error: function(){
            findCepAddresCORREIOSWebservice(cep, rua_id, bairro_id, cidade_id, estado_id);
        }
    });
}*/

function findCepAddresCORREIOSWebservice(cep, rua_id, bairro_id, cidade_id, estado_id){
    var url = getBaseURL() + "recursos/formulario/jsplugins/cep/webservice/cep.php?cep="+cep;
    $.ajax({
        url: url,
        dataType: "json",
        success: function(json){
            if(json.result == 1){
                $('#'+rua_id).val(unescape(json.logradouro));
                $('#'+bairro_id).val(unescape(json.bairro));
                $('#'+cidade_id).val(unescape(json.cidade));
                $('#'+estado_id).val(unescape(json.uf.toUpperCase()));
            }else{
                findCepAddresRepublicaVirtualWebservice(cep, rua_id, bairro_id, cidade_id, estado_id);
            }
        },
        error: function(){
            findCepAddresRepublicaVirtualWebservice(cep, rua_id, bairro_id, cidade_id, estado_id);
        }
    });
}


function findCepAddresRepublicaVirtualWebservice(cep, rua_id, bairro_id, cidade_id, estado_id){
    $.getScript('http://cep.republicavirtual.com.br/web_cep.php?formato=javascript&cep='+cep, function(){
        if(resultadoCEP["resultado"] == 1){
            if(unescape(resultadoCEP['tipo_logradouro']) == "XXX") {
                $("#erro").slideDown('slow').html('Caro usuário o site dos correios bloquearam as consultas devido a uso excessivo.');
                return false;
            }
            else{
                $('#'+rua_id).val(unescape(resultadoCEP["tipo_logradouro"])+": "+unescape(resultadoCEP["logradouro"]));
                $('#'+bairro_id).val(unescape(resultadoCEP["bairro"]));
                $('#'+cidade_id).val(unescape(resultadoCEP["cidade"]));
                $('#'+estado_id).val(unescape(resultadoCEP["uf"]));
            }
        }else{
            $("#v"+rua_id).addClass('erro').html('Erro ao recuperar o CEP utilizando a base dos correios!');
            $('#erro').fadeIn('slow').val(unescape(resultadoCEP['resultado_txt']));
        }
    });
}


function getEndereco(cep_id, rua_id, bairro_id, cidade_id, estado_id){
    var cep  = $('#'+cep_id).val()+'';
    if($.trim(cep)!=''){
        if($.trim(cep).length == 8){
            $("#v"+cep_id).removeClass('erro').html('');
            findCepAddresLOCALHOSTWebservice(cep, rua_id, bairro_id, cidade_id, estado_id);
        }
    }
}