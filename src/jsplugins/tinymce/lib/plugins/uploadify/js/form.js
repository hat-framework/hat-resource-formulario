$(document).ready(function() { 
    $('.album_geturl').live('click', function(){
        var url = $(this).attr('href');
        var id  = $(this).attr('id');
        $.ajax({
            url: url,
            dataType: 'json',
            success: function(json) {
                alert(json.img);
            },
            error: function(erro){
                alert('Erro na comunicação com o site');
            }
        });
        return false;
    });
});