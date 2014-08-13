function __redactor_plugin(campo, upload_url, but){
    $(campo).redactor({
        lang: 'pt_br', focus: false, iframe: true,
        keyupCallback: function (obj, event) {
           var conteudo = obj.$el.getCode();
        },
        //imageGetJson: upload_url+'/getImage.php',
        imageUpload : upload_url+'/image.php',
        //fileUpload  : upload_url+'/file.php',
        wym: true, buttons: but
    });
}