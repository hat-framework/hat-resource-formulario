cepClass = function(){
    this.tmpCache = {};
}; 

cepClass.prototype.getEndereco = function(cep, fn){
    if(typeof fn !== 'function' || $.trim(cep)=='' || $.trim(cep).length != 8){return;}
    var rcep = this.getItemInCache("cep/"+cep);
    if(typeof rcep === 'object' && rcep !== null){
        return fn(rcep);
    }
    var self = this;
    
    self.findCepAddresLOCALHOSTWebservice(cep, function(result){
        if(typeof(result['status'])==='undefined' || result['status'] !== 0){self.saveItemInCache("cep/"+cep, result); return fn(result);}
        self.findCepAddresCORREIOSWebservice(cep, function(result2){
            if(typeof(result2['status'])==='undefined' || result2['status'] !== 0){self.saveItemInCache("cep/"+cep, result2); return fn(result2);}
            self.findCepAddresRepublicaVirtualWebservice(cep, function(result3){
                if(typeof(result3['status'])==='undefined' || result3['status'] !== 0){self.saveItemInCache("cep/"+cep, result3); return fn(result3);}
                self.message_erro("\
                    Não foi possível buscar o seu cep!\n\
                    Verifique se ele foi digitado corretamente \n\
                    Ou se você permanece conectado a internet!\n\
                ");
            });
        });

    });
    return false;
};
    
cepClass.prototype.findCepAddresLOCALHOSTWebservice = function(cep, fn){
    if(typeof fn !== 'function'){return false;}
    var url = getBaseURL() + "usuario/endereco/getcep/"+cep;
    $.ajax({
        url: url,
        timeout:1000,
        dataType: "json",
        success: function(json){
            if(json.resultado != 1){return fn({status:0});}
            fn({
                'logradouro':unescape(json.rua),
                'bairro'    :unescape(json.bairro),
                'cidade'    :unescape(json.cidade),
                'uf'        :unescape(json.estado.toUpperCase())
            });
            return true;
        },
        error: function(){fn({status:0});}
    });
};

cepClass.prototype.findCepAddresKINGHOSTWebservice = function(cep, fn){
    if(typeof fn !== 'function'){return false;}
    var url  = 'http://webservice.kinghost.net/web_cep.php?auth=12473be26ed7caa39d0830b97dbea02a&formato=json&cep='+cep;
    $.ajax({
        url: url,
        timeout:1000,
        dataType: "json",
        success: function(json){
            if(json.result != 1){return fn({status:0});}
            fn({
                'logradouro':unescape(json.tp_logradouro)+': '+unescape(json.logradouro),
                'bairro'    :unescape(json.bairro),
                'cidade'    :unescape(json.cidade),
                'uf'        :unescape(json.uf.toUpperCase())
            });
            return true;
        },
        error: function(){fn({status:0});}
    });
};

cepClass.prototype.findCepAddresCORREIOSWebservice = function(cep, fn){
    if(typeof fn !== 'function'){return false;}
    var url = getResourceUrl('formulario') + "/src/jsplugins/cep/webservice/cep.php?cep="+cep;
    $.ajax({
        url: url,
        timeout:1000,
        dataType: "json",
        success: function(json){
            if(json.result != 1){return fn({status:0});}
            fn({
                'logradouro':unescape(json.logradouro),
                'bairro'    :unescape(json.bairro),
                'cidade'    :unescape(json.cidade),
                'uf'        :unescape(json.uf.toUpperCase())
            });
            return true;
        },
        error: function(){fn({status:0});}
    });
};

cepClass.prototype.findCepAddresRepublicaVirtualWebservice = function(cep, fn){
    if(typeof fn !== 'function'){return false;}
    $.getScript('http://cep.republicavirtual.com.br/web_cep.php?formato=javascript&cep='+cep, function(){
        if(resultadoCEP["resultado"] == 1){
            if(unescape(resultadoCEP['tipo_logradouro']) == "XXX") {
                return fn({status:0, error: 'Caro usuário o site dos correios bloquearam as consultas devido a uso excessivo.'});
            }
            fn({
                'logradouro':unescape(resultadoCEP["tipo_logradouro"])+": "+unescape(resultadoCEP["logradouro"]),
                'bairro'    :unescape(resultadoCEP["bairro"]),
                'cidade'    :unescape(resultadoCEP["cidade"]),
                'uf'        :unescape(resultadoCEP["uf"])
            });
            return true;
        }
        fn({
            status:0, 
            error: 'Erro ao recuperar o CEP utilizando a base dos correios!'+unescape(resultadoCEP['resultado_txt'])
        });
    });
};
    
cepClass.prototype.saveItemInCache = function(key, data){
    try{
        localStorage.setItem(key, JSON.stringify(data));
    }catch(e){
        tmpCache[key] = data;
    }
};

cepClass.prototype.getItemInCache = function(key){
    try{
        return JSON.parse(localStorage.getItem(key));
    }catch(e){
        return typeof(tmpCache[cep] !== 'undefined')?tmpCache[cep]:null;
    }
};

cepClass.prototype.dropItemInCache = function(cep){
    try{
        localStorage.removeItem(cep);
    }catch(e){
        if(typeof(this.tmpCache[cep] === 'undefined')){return;}
        delete this.tmpCache[cep];
    }
};

cepClass.prototype.clearLocalCache = function(){
    try{
        localStorage.clear();
    }catch(e){
        this.tmpCache = {};
    }
};

cepClass.prototype.message_erro = function(msg){
    if(typeof message_erro == 'function'){
        message_erro(msg);
    }else {alert(msg);}
};

var cepUniqueObjectWebservice = new cepClass();
function getEndereco(cep_id, rua_id, bairro_id, cidade_id, estado_id){
    var cep  = $('#'+cep_id).val()+'';
    $("#v"+cep_id).removeClass('erro').html('');
    var fn = function(result){
        if(result['status'] === 0){
            var err = (typeof result['error'] !== 'undefined')?result['error']:'Erro ao buscar cep!';
            message_erro(err);
            return false;
        }
        $('#'+rua_id).val(result['logradouro']);
        $('#'+bairro_id).val(result["bairro"]);
        $('#'+cidade_id).val(result["cidade"]);
        $('#'+estado_id).val(result["uf"]);
        try{
            $('#'+estado_id).trigger('chosen:updated');
        }catch(e){}
    };
    
    return cepUniqueObjectWebservice.getEndereco(cep, fn);
}