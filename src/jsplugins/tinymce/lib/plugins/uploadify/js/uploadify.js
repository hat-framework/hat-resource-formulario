tinyMCEPopup.requireLangPack();

var UploadifyDialog = {
    init : function(ed) {
            tinyMCEPopup.resizeToInnerSize();
    },

    insert : function(file) {
        var url = file;
        $.ajax({
            url: url,
            dataType: 'json',
            success: function(json) {
                //alert(json.img);
                var ed = tinyMCEPopup.editor, dom = ed.dom;
                tinyMCEPopup.restoreSelection();
                tinyMCEPopup.execCommand("mceInsertContent", false, '<a href="'+json.img+'" class="img"><img src="'+json.img_max+'"/></a>');
                tinyMCEPopup.close();
            },
            error: function(erro){
                alert('Erro na comunicação com o site');
            }
        });
    }
};

tinyMCEPopup.onInit.add(UploadifyDialog.init, UploadifyDialog);
