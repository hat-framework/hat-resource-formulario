<?php
    require_once '../../../../../../../init.php';
    require_once 'php/UploadifyClass.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>{#emotions_dlg.title}</title>
        <script type="text/javascript" src="../../tiny_mce_popup.js"></script>
        <script type="text/javascript" src="js/uploadify.js"></script>
        <script type="text/javascript" src="js/form.js"></script>
        <?php 
            $class   = new UploadifyClass(); 
            $usuario = $class->getUsuario();
            $album   = $class->getAlbum($usuario);
            $uify    = $class->getUify();
            $uify->configure("album", $album, $usuario, $folder = 'editor');
        ?>
        <style>
            .img_container .album_geturl, .album_geturl{display: table !important;}
        </style>
</head>
<body>
    <?php
        $uify->drawForm("album");
        $class->flush();
    ?>
</body>
</html>
