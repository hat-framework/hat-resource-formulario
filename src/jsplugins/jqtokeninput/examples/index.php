<?php

if(isset($_POST) && !empty ($_POST)){
    print_r($_POST);
    die();
}

?>
<html>
<head>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="src/jquery.tokeninput.js"></script>

    <link rel="stylesheet" href="styles/token-input.css" type="text/css" />
    <link rel="stylesheet" href="styles/token-input-facebook.css" type="text/css" />
    
</head>

<body>
    <h1>jQuery Tokeninput Demos</h1>
    
    <h2>Simple Server-Backed Search</h2>
    <div>
        <form action='index.php' method='post' id='form' class='formulario'>
            <input type="text" id="demo-input" name="blah" />
            <input type="submit" value="Submit" />
        </form>
        
        <script type="text/javascript">
        $(document).ready(function() {
           // $("#demo-input").tokenInput("http://shell.loopj.com/tokeninput/tvshows.php", {
            $("#demo-input").tokenInput("http://localhost/hat/recursos/formulario/jsplugins/jqtokeninput/php/completar.php?model=loja/categoria&k1=cod_categoria&k2=catnome&limit=10", {
           
                //theme: "facebook",
                hintText: "Digite um texto...",
                noResultsText: "Nenhum resultado encontrado",
                searchingText: "Pesquisando...",
                searchDelay: 500,
                minChars: 1,
                preventDuplicates: true,
                onAdd: function (item) {
                    //alert('id ' + item.id + ' name ' + item.name);
                },
                onDelete: function (item) {
                    //alert('Deleted ' + item.id);
                }
            });
        });
        </script>
    </div>
</body>
</html>