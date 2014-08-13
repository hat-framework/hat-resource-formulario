function getFrete() {
    
    if($.trim($("#cep").val())!=""){
       if($.trim($("#cep").val()).length == 8){
           var url = "http://localhost/thom/mvc/layout/Plugins/formulario/frete/freteloader.php?cep="
               +$("#cep").val()
               +"&peso=" +$("#peso").val()
               +"&comp=" +$("#comp").val()
               +"&larg=" +$("#larg").val()
               +"&alt="  +$("#alt").val()
               +"&valor="+$("#valor").val();
           $("#loading").show();
           $('#erro_frete').hide();
           $('#calculo_frete').hide();
           $.getScript(url,
                function(){
                    if(resultado["erro"] == 0){
                        $('#erro_frete').hide();
                        $('#calculo_frete').show(2500);
                        $("#valor_frete").html(unescape(resultado["valor"]));
                        $("#prazo_frete").html(unescape(resultado["prazoentrega"]));
                    }else {
                        $('#erro_frete').show(1500);
                        $('#calculo_frete').hide();
                        if(resultado["erro"] == 1){
                            $("#erro_frete_msg").html(unescape(resultado["msgerro"]));
                        }
                        else{
                            $("#erro_frete_msg").html("Erro na conexao com o servidor");
                        }
                    }
                    $("#loading").hide(3000);
                }
                
           );
           
       }
    }
    
    $.loader('close');
}